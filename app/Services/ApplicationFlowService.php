<?php

namespace App\Services;

use App\Interfaces\ApplicationFlowServiceInterface;
use App\Models\User;
use App\Models\UserApplication;
use App\Models\ApplicationStage;
use App\Models\ApplicationStep;
use App\Models\ApplicationResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationFlowService implements ApplicationFlowServiceInterface
{
    public function startApplication(User $user): UserApplication
    {
        // Check if user already has an active application (optional, based on rules)
        $existingApplication = UserApplication::where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'rejected_final']) // Define your final statuses
            ->first();

        if ($existingApplication) {
            // Potentially return existing or throw exception, based on desired logic
            // For now, let's assume one active application at a time is fine, or return it.
            // Or, allow multiple applications if that's a requirement.
            // For this example, we'll create a new one or return the draft.
            if ($existingApplication->status === 'draft') {
                return $existingApplication;
            }
            // If another status, decide behavior. For now, let's allow a new one if not draft.
        }

        $firstStage = ApplicationStage::orderBy('order', 'asc')->first();
        if (!$firstStage) {
            throw new \Exception("No application stages defined.");
        }

        $firstStep = $firstStage->steps()->orderBy('order', 'asc')->first();
        if (!$firstStep) {
            throw new \Exception("No application steps defined for the first stage.");
        }

        return UserApplication::create([
            'user_id' => $user->id,
            'status' => 'draft',
            'current_stage_id' => $firstStage->id,
            'current_step_id' => $firstStep->id,
        ]);
    }

    public function getCurrentStep(UserApplication $application): ?ApplicationStep
    {
        return $application->currentStep;
    }

    public function getNextStep(UserApplication $application): ?ApplicationStep
    {
        $currentStep = $application->currentStep;
        if (!$currentStep) {
            return null;
        }

        // Try next step in current stage
        $nextStep = ApplicationStep::where('stage_id', $currentStep->stage_id)
            ->where('order', '>', $currentStep->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextStep) {
            return $nextStep;
        }

        // Try first step of next stage
        $currentStage = $application->currentStage;
        if (!$currentStage) return null;

        $nextStage = ApplicationStage::where('order', '>', $currentStage->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextStage) {
            return $nextStage->steps()->orderBy('order', 'asc')->first();
        }

        return null; // No next step, application might be complete
    }

    public function getPreviousStep(UserApplication $application): ?ApplicationStep
    {
        $currentStep = $application->currentStep;
        if (!$currentStep) {
            return null;
        }

        // Try previous step in current stage
        $prevStep = ApplicationStep::where('stage_id', $currentStep->stage_id)
            ->where('order', '<', $currentStep->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($prevStep) {
            return $prevStep;
        }

        // Try last step of previous stage
        $currentStage = $application->currentStage;
         if (!$currentStage) return null;

        $prevStage = ApplicationStage::where('order', '<', $currentStage->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($prevStage) {
            return $prevStage->steps()->orderBy('order', 'desc')->first();
        }

        return null; // No previous step, likely at the beginning
    }

    public function completeStep(UserApplication $application, ApplicationStep $step, array $responseData): ApplicationResponse
    {
        // Ensure this step belongs to the application's current stage/step logic if strict progression is needed
        if ($application->current_step_id !== $step->id) {
            // Handle error or redirect, e.g., trying to complete a step out of order
            Log::warning("Attempt to complete step out of order.", [
                'application_id' => $application->id,
                'expected_step_id' => $application->current_step_id,
                'actual_step_id' => $step->id
            ]);
            // Potentially throw an exception or return an error indicator
        }

        $response = ApplicationResponse::updateOrCreate(
            [
                'user_application_id' => $application->id,
                'step_id' => $step->id,
            ],
            [
                'response_data' => $responseData,
                'is_completed' => true,
            ]
        );

        // Move to next step automatically
        $nextStep = $this->getNextStep($application);
        if ($nextStep) {
            $application->current_step_id = $nextStep->id;
            $application->current_stage_id = $nextStep->stage_id; // Ensure stage is also updated
            if ($application->status === 'draft' && $step->is_required) { // Basic status progression
                 // If all required steps in first stage are done, move to 'submitted_endorsement' etc.
                 // This logic can be more sophisticated.
            }
        } else {
            // No next step, application might be fully completed
            $application->status = 'submitted'; // Or a more specific completed status
            $application->completed_at = now();
        }
        $application->save();

        return $response;
    }

    public function moveToStep(UserApplication $application, ApplicationStep $targetStep): bool
    {
        // Add validation: ensure targetStep is valid for this application (e.g., not skipping mandatory steps)
        $application->current_step_id = $targetStep->id;
        $application->current_stage_id = $targetStep->stage_id;
        return $application->save();
    }

    public function updateApplicationStatus(UserApplication $application, string $status): bool
    {
        // Add validation for allowed status transitions if necessary
        $application->status = $status;
        if ($status === 'submitted' && !$application->submitted_at) {
            $application->submitted_at = now();
        }
        // Add other status-specific timestamp updates
        return $application->save();
    }
} 