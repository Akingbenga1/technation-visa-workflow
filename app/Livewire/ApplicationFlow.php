<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserApplication;
use App\Models\ApplicationStep;
use App\Models\ApplicationResponse;
use App\Interfaces\ApplicationFlowServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;


class ApplicationFlow extends Component
{
    use WithFileUploads;

    public UserApplication $application;
    public ?ApplicationStep $currentStep;
    public $responseData = [];
    public $uploadedFiles = []; // For handling file uploads specifically

    protected ApplicationFlowServiceInterface $applicationFlowService;

    public function boot(ApplicationFlowServiceInterface $flowService)
    {
        $this->applicationFlowService = $flowService;
    }

    public function mount($application_id)
    {
        $application = UserApplication::with(['currentStage.steps', 'currentStep.stage', 'responses.step'])
            ->findOrFail($application_id);

        // Ensure the logged-in user owns this application
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $form_schema = json_decode($application->currentStep->form_schema, true);
        // dd($form_schema, $application->currentStep);
        $this->application = $application;
        $this->currentStep = $this->application->currentStep;

        if (!$this->currentStep) {
            // This might happen if the application is completed or in an error state.
            // Redirect or show a completion message.
            if ($this->application->status === 'submitted' || $this->application->status === 'completed') {
                 session()->flash('status', 'Application already completed or submitted.');
                // Consider redirecting to a summary page or dashboard
                // For now, let's just prevent further interaction if no current step.
                // $this->redirectRoute('applications.index');
                // return;
            } else {
                // Try to recover or redirect
                $firstStage = \App\Models\ApplicationStage::orderBy('order')->first();
                if ($firstStage && $firstStep = $firstStage->steps()->orderBy('order')->first()) {
                    $this->application->current_stage_id = $firstStage->id;
                    $this->application->current_step_id = $firstStep->id;
                    $this->application->save();
                    $this->currentStep = $this->application->fresh()->currentStep;
                } else {
                    session()->flash('error', 'Cannot determine the current application step. Please contact support.');
                    // $this->redirectRoute('dashboard');
                    // return;
                }
            }
        }
        $this->loadCurrentStepResponse();
    }

    protected function loadCurrentStepResponse()
    {
        if ($this->currentStep) {
            $response = ApplicationResponse::where('user_application_id', $this->application->id)
                ->where('step_id', $this->currentStep->id)
                ->first();
            $this->responseData = $response ? $response->response_data : [];

            // Initialize uploadedFiles for file fields if they exist in responseData
            $this->uploadedFiles = [];
            if ($this->currentStep->form_type === 'document_upload' && isset($this->currentStep->form_schema['fields'])) {
                foreach ($this->currentStep->form_schema['fields'] as $field) {
                    if ($field['type'] === 'file' && isset($this->responseData[$field['id']])) {
                        // Assuming responseData stores file info (e.g., path, name)
                        // For Livewire's temporary preview, this needs to be handled carefully.
                        // If files are already stored, you might just display them, not re-upload.
                        // For simplicity, we'll clear uploadedFiles and rely on re-upload if user wants to change.
                        // Or, you can store temporary URLs if files are already on server.
                    }
                }
            }
        } else {
            $this->responseData = [];
            $this->uploadedFiles = [];
        }
    }


    public function submitStep()
    {
        if (!$this->currentStep) {
            session()->flash('error', 'No current step to submit.');
            return;
        }

        $this->validateStepData();

        // Handle file uploads
        $processedResponseData = $this->responseData;
        if ($this->currentStep->form_type === 'document_upload' && isset($this->currentStep->form_schema['fields'])) {
            foreach ($this->currentStep->form_schema['fields'] as $field) {
                if ($field['type'] === 'file' && isset($this->uploadedFiles[$field['id']])) {
                    $fileOrFiles = $this->uploadedFiles[$field['id']];
                    if (is_array($fileOrFiles)) { // Multiple files
                        $paths = [];
                        foreach ($fileOrFiles as $file) {
                            $paths[] = $this->storeUploadedFile($file, $field['id']);
                        }
                        $processedResponseData[$field['id']] = $paths; // Store array of paths
                    } else { // Single file
                        $processedResponseData[$field['id']] = $this->storeUploadedFile($fileOrFiles, $field['id']);
                    }
                }
            }
        }

        $this->applicationFlowService->completeStep($this->application, $this->currentStep, $processedResponseData);
        $this->application->refresh();
        $this->currentStep = $this->application->currentStep;

        if ($this->currentStep) {
            $this->loadCurrentStepResponse(); // Load data for the new current step
            session()->flash('status', 'Step completed successfully! Proceed to the next step.');
        } else {
            // Application completed
            session()->flash('status', 'Application submitted successfully!');
            // Potentially redirect to a summary or thank you page
            $this->redirectRoute('applications.index');
        }
    }

    protected function storeUploadedFile($file, $fieldId)
    {
        $originalName = $file->getClientOriginalName();
        $filename = pathinfo($originalName, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $safeFilename = Str::slug($filename) . '-' . uniqid() . '.' . $extension;

        // Store in 'user_documents/{user_id}/{application_id}/{field_id}/filename.ext'
        $path = $file->storeAs(
            'user_documents/' . Auth::id() . '/' . $this->application->id . '/' . $this->currentStep->id . '/' . $fieldId,
            $safeFilename,
            'public' // Make sure 'public' disk is configured for public access if needed
        );

        // Create a Document record
         \App\Models\Document::create([
            'user_id' => Auth::id(),
            // 'application_response_id' => $response->id, // This needs to be set after response is created.
            // For now, let's link directly to user and store path in response_data.
            // A better approach would be to create Document after ApplicationResponse is saved,
            // and store an array of document IDs in response_data.
            'name' => $originalName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'document_type' => $fieldId, // Or a more generic type from schema
        ]);

        return $path; // Store the path in response_data
    }


    protected function validateStepData()
    {
        if (!$this->currentStep || !is_array($this->currentStep->form_schema)) {
            return; // No schema to validate against
        }

        $rules = [];
        $messages = [];
        $attributes = [];

        // General rules based on form_type
        switch ($this->currentStep->form_type) {
            case 'checklist':
            case 'document_checklist':
            // case 'document_checklist_confirmation': // This was in previous code, but seeder uses document_checklist
                if (isset($this->currentStep->form_schema['questions']) && is_array($this->currentStep->form_schema['questions'])) {
                    foreach ($this->currentStep->form_schema['questions'] as $question) {
                        if (Arr::get($question, 'required', $this->currentStep->is_required)) {
                            $rules['responseData.' . $question['id']] = 'required|accepted'; // Checkboxes are often 'accepted' if true is required
                            $attributes['responseData.' . $question['id']] = $question['text'] ?? $question['label'] ?? $question['id'];
                        } else {
                            $rules['responseData.' . $question['id']] = 'nullable|boolean';
                        }
                    }
                } elseif (isset($this->currentStep->form_schema['items']) && is_array($this->currentStep->form_schema['items'])) { // For document_checklist
                     foreach ($this->currentStep->form_schema['items'] as $item) {
                        if (Arr::get($item, 'required', $this->currentStep->is_required)) {
                            $rules['responseData.' . $item['id']] = 'required|accepted';
                             $attributes['responseData.' . $item['id']] = $item['label'] ?? $item['id'];
                        } else {
                            $rules['responseData.' . $item['id']] = 'nullable|boolean';
                        }
                    }
                }
                break;

            case 'select':
                if (isset($this->currentStep->form_schema['label'])) {
                    $fieldId = Arr::get($this->currentStep->form_schema, 'id', 'selected_option');
                    if ($this->currentStep->is_required || Arr::get($this->currentStep->form_schema, 'required', false)) {
                        $rules['responseData.' . $fieldId] = 'required';
                        $attributes['responseData.' . $fieldId] = $this->currentStep->form_schema['label'];
                    } else {
                        $rules['responseData.' . $fieldId] = 'nullable';
                         $attributes['responseData.' . $fieldId] = $this->currentStep->form_schema['label'];
                    }
                    if (isset($this->currentStep->form_schema['options'])) {
                         $rules['responseData.' . $fieldId][] = \Illuminate\Validation\Rule::in(array_keys($this->currentStep->form_schema['options']));
                    }
                }
                break;

            case 'document_upload':
                if (isset($this->currentStep->form_schema['fields']) && is_array($this->currentStep->form_schema['fields'])) {
                    foreach ($this->currentStep->form_schema['fields'] as $field) {
                        if ($field['type'] === 'file') {
                            $fileRule = [];
                            if (Arr::get($field, 'required', $this->currentStep->is_required)) {
                                // If file already exists in responseData, it's not required to re-upload
                                if (!isset($this->responseData[$field['id']]) || empty($this->responseData[$field['id']])) {
                                    $fileRule[] = 'required';
                                }
                            }
                            if (isset($field['accept'])) {
                                $mimes = str_replace('.', '', $field['accept']);
                                $fileRule[] = 'mimes:' . $mimes;
                            }
                            // Add max size validation if needed, e.g., 'max:10240' (for 10MB)

                            if (!empty($fileRule)) {
                                $rules['uploadedFiles.' . $field['id'] . (Arr::get($field, 'multiple') ? '.*' : '')] = $fileRule;
                                $attributes['uploadedFiles.' . $field['id'] . (Arr::get($field, 'multiple') ? '.*' : '')] = $field['label'] ?? $field['id'];
                            }
                        } else { // For other non-file fields within a document_upload form_type
                             if (Arr::get($field, 'required', $this->currentStep->is_required)) {
                                $rules['responseData.' . $field['id']] = 'required';
                                $attributes['responseData.' . $field['id']] = $field['label'] ?? $field['id'];
                            }
                        }
                    }
                }
                break;

            case 'information': // Assuming 'information' type from seeder also needs acknowledgement if required
            case 'information_acknowledgement':
                 if ($this->currentStep->is_required) {
                    $rules['responseData.acknowledged'] = 'accepted';
                    $attributes['responseData.acknowledged'] = $this->currentStep->form_schema['acknowledgement_label'] ?? 'Acknowledgement';
                 }
                break;

            case 'confirmation':
                if ($this->currentStep->is_required || Arr::get($this->currentStep->form_schema, 'confirmation_required', $this->currentStep->is_required)) {
                    $rules['responseData.confirmed'] = 'accepted';
                    $attributes['responseData.confirmed'] = $this->currentStep->form_schema['confirmation_label'] ?? 'Confirmation';
                }
                if (isset($this->currentStep->form_schema['date_field_label'])) {
                    $dateRequired = Arr::get($this->currentStep->form_schema, 'date_field_required', false);
                    $rules['responseData.action_date'] = $dateRequired ? 'required|date' : 'nullable|date';
                    $attributes['responseData.action_date'] = $this->currentStep->form_schema['date_field_label'];
                }
                if (isset($this->currentStep->form_schema['text_input_label'])) {
                    $textInputRequired = Arr::get($this->currentStep->form_schema, 'text_input_required', false);
                    $rules['responseData.text_input'] = $textInputRequired ? 'required|string|max:255' : 'nullable|string|max:255';
                    $attributes['responseData.text_input'] = $this->currentStep->form_schema['text_input_label'];
                }
                break;
            
            case 'payment_confirmation':
                $transactionIdRequired = Arr::get($this->currentStep->form_schema, 'transaction_id_required', $this->currentStep->is_required);
                $paymentDateRequired = Arr::get($this->currentStep->form_schema, 'payment_date_required', $this->currentStep->is_required);
                $confirmationRequired = $this->currentStep->is_required;

                $rules['responseData.transaction_id'] = $transactionIdRequired ? 'required|string|max:255' : 'nullable|string|max:255';
                $attributes['responseData.transaction_id'] = $this->currentStep->form_schema['transaction_id_label'] ?? 'Transaction ID';
                
                $rules['responseData.payment_date'] = $paymentDateRequired ? 'required|date' : 'nullable|date';
                $attributes['responseData.payment_date'] = $this->currentStep->form_schema['payment_date_label'] ?? 'Payment Date';

                if ($confirmationRequired) {
                    $rules['responseData.payment_confirmed'] = 'accepted';
                    $attributes['responseData.payment_confirmed'] = $this->currentStep->form_schema['confirmation_label'] ?? 'Payment Confirmation';
                }
                break;

            case 'status_update':
                $statusFieldId = Arr::get($this->currentStep->form_schema, 'status_field_id', 'status_value');
                $dateFieldId = Arr::get($this->currentStep->form_schema, 'date_field_id', 'status_date');

                if ($this->currentStep->is_required || Arr::get($this->currentStep->form_schema, 'status_field_required', $this->currentStep->is_required)) {
                    $rules['responseData.' . $statusFieldId] = 'required';
                    $attributes['responseData.' . $statusFieldId] = $this->currentStep->form_schema['status_options_label'] ?? 'Status';
                    if (isset($this->currentStep->form_schema['options'])) {
                        $rules['responseData.' . $statusFieldId][] = \Illuminate\Validation\Rule::in(array_keys($this->currentStep->form_schema['options']));
                    }
                } else {
                     $rules['responseData.' . $statusFieldId] = 'nullable';
                     $attributes['responseData.' . $statusFieldId] = $this->currentStep->form_schema['status_options_label'] ?? 'Status';
                     if (isset($this->currentStep->form_schema['options'])) {
                        $rules['responseData.' . $statusFieldId][] = \Illuminate\Validation\Rule::in(array_keys($this->currentStep->form_schema['options']));
                    }
                }

                if (isset($this->currentStep->form_schema['date_field_label'])) {
                     $dateRequired = Arr::get($this->currentStep->form_schema, 'date_field_required', $this->currentStep->is_required);
                     $rules['responseData.' . $dateFieldId] = $dateRequired ? 'required|date' : 'nullable|date';
                     $attributes['responseData.' . $dateFieldId] = $this->currentStep->form_schema['date_field_label'];
                }
                 if (isset($this->currentStep->form_schema['notes_label'])) {
                    $rules['responseData.status_notes'] = 'nullable|string|max:1000';
                    $attributes['responseData.status_notes'] = $this->currentStep->form_schema['notes_label'];
                }
                break;

            case 'date_input':
                if (isset($this->currentStep->form_schema['label'])) {
                    $fieldId = Arr::get($this->currentStep->form_schema, 'id', 'input_date');
                    if (Arr::get($this->currentStep->form_schema, 'required', $this->currentStep->is_required)) {
                        $rules['responseData.' . $fieldId] = 'required|date';
                        $attributes['responseData.' . $fieldId] = $this->currentStep->form_schema['label'];
                    } else {
                        $rules['responseData.' . $fieldId] = 'nullable|date';
                        $attributes['responseData.' . $fieldId] = $this->currentStep->form_schema['label'];
                    }
                    if (isset($this->currentStep->form_schema['notes_label'])) {
                        $rules['responseData.date_notes'] = 'nullable|string|max:1000';
                        $attributes['responseData.date_notes'] = $this->currentStep->form_schema['notes_label'];
                    }
                }
                break;

            // Add more cases for other form_types based on their schema structure
            // Default for simple text fields etc., if schema defines them
            default:
                if (isset($this->currentStep->form_schema['fields']) && is_array($this->currentStep->form_schema['fields'])) {
                    foreach ($this->currentStep->form_schema['fields'] as $field) {
                        if (Arr::get($field, 'required', $this->currentStep->is_required)) {
                            $rules['responseData.' . $field['id']] = 'required';
                            $attributes['responseData.' . $field['id']] = $field['label'] ?? $field['id'];
                            // Add more specific validation based on $field['type'] (e.g., email, numeric)
                        }
                    }
                }
                break;
        }


        if (!empty($rules)) {
            try {
                $this->validate($rules, $messages, $attributes);
            } catch (ValidationException $e) {
                // The validation errors will automatically be flashed to the session
                // and be available in the $errors variable in the Blade view.
                throw $e;
            }
        }
    }


    public function goToPreviousStep()
    {
        $previousStep = $this->applicationFlowService->getPreviousStep($this->application);
        if ($previousStep) {
            $this->applicationFlowService->moveToStep($this->application, $previousStep);
            $this->application->refresh();
            $this->currentStep = $this->application->currentStep;
            $this->loadCurrentStepResponse();
            session()->flash('status', 'Moved to the previous step.');
        } else {
            session()->flash('info', 'You are already at the first step.');
        }
    }

    public function goToNextStep() // Typically called after submitStep, but can be manual if a step is optional and not "completed"
    {
        // Check if current step is completed or optional before allowing manual next
        $currentResponse = ApplicationResponse::where('user_application_id', $this->application->id)
            ->where('step_id', $this->currentStep->id)
            ->first();

        if (!$this->currentStep->is_required || ($currentResponse && $currentResponse->is_completed)) {
            $nextStep = $this->applicationFlowService->getNextStep($this->application);
            if ($nextStep) {
                $this->applicationFlowService->moveToStep($this->application, $nextStep);
                $this->application->refresh();
                $this->currentStep = $this->application->currentStep;
                $this->loadCurrentStepResponse();
                session()->flash('status', 'Moved to the next step.');
            } else {
                session()->flash('info', 'You are at the last step. Submit to complete the application.');
            }
        } else {
             session()->flash('error', 'Please complete the current step before proceeding.');
        }
    }
    
    #[On('file-upload-error')] // Listen for event from child component
    public function handleFileUploadError($message)
    {
        session()->flash('error', $message);
    }


    public function render()
    {
        // Determine which partial view to load based on currentStep->form_type
        $formPartial = 'livewire.application-flow-partials._default-form'; // Fallback
        if ($this->currentStep) {
            $partialName = str_replace('_', '-', $this->currentStep->form_type);
            if (view()->exists('livewire.application-flow-partials._' . $partialName)) {
                $formPartial = 'livewire.application-flow-partials._' . $partialName;
            } elseif (view()->exists('livewire.application-flow-partials._' . $this->currentStep->form_type)) {
                 $formPartial = 'livewire.application-flow-partials._' . $this->currentStep->form_type;
            }
        } elseif ($this->application->status === 'submitted' || $this->application->status === 'completed') {
            $formPartial = 'livewire.application-flow-partials._completed-application';
        }


        return view('livewire.application-flow', [
            'formPartial' => $formPartial,
            'allStages' => \App\Models\ApplicationStage::with('steps')->orderBy('order')->get(),
        ])->layout('layouts.app'); // Assuming Jetstream's default layout
    }
} 