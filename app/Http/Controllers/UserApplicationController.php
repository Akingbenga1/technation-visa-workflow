<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\ApplicationFlowServiceInterface;
use App\Models\UserApplication;

class UserApplicationController extends Controller
{
    protected ApplicationFlowServiceInterface $applicationFlowService;

    public function __construct(ApplicationFlowServiceInterface $applicationFlowService)
    {
        $this->applicationFlowService = $applicationFlowService;
    }

    public function startOrResumeApplication(Request $request)
    {
        $user = Auth::user();
        $application = UserApplication::where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'rejected']) // Adjust statuses as needed
            ->orderBy('updated_at', 'desc')
            ->first();

        if (!$application || $application->status === 'submitted') { // Or other conditions to start new
            $application = $this->applicationFlowService->startApplication($user);
        }

        if (!$application->current_step_id && $application->currentStage) {
            // If current_step_id is somehow null but stage is set, try to set to first step of stage
            $firstStepOfCurrentStage = $application->currentStage->steps()->orderBy('order')->first();
            if ($firstStepOfCurrentStage) {
                $application->current_step_id = $firstStepOfCurrentStage->id;
                $application->save();
            } else {
                // This case should ideally not happen if stages always have steps
                // Potentially redirect to an error page or dashboard with a message
                session()->flash('error', 'Application setup issue. Please contact support.');
                return redirect()->route('dashboard');
            }
        } elseif (!$application->current_step_id && !$application->currentStage) {
             // This means the application is freshly created and has no step/stage yet,
             // which startApplication should handle. If it still happens, it's an issue.
            session()->flash('error', 'Could not determine the current application step. Please try starting again or contact support.');
            return redirect()->route('dashboard'); // Or a more appropriate error view
        }


        return redirect()->route('application.show', ['application_id' => $application->id]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $applications = UserApplication::where('user_id', $user->id)
            ->with('currentStage', 'currentStep')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        // A simple view to list applications.
        // You would create this Blade file: resources/views/applications/index.blade.php
        return view('applications.index', compact('applications'));
    }
} 