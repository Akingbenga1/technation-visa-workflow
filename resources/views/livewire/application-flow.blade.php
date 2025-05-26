<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Global Talent Visa Application - {{ $application->reference_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">

                    @if (session()->has('status'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                     @if (session()->has('info'))
                        <div class="mb-4 p-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-200 dark:text-blue-800" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif

                    <!-- Progress Bar / Stepper -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Application Progress</h3>
                        <nav aria-label="Progress">
                            <ol role="list" class="flex items-center space-x-2 sm:space-x-4">
                                @foreach ($allStages as $stage)
                                    @php
                                        $isCurrentStage = $currentStep && $currentStep->stage_id == $stage->id;
                                        $isCompletedStage = $application->currentStage && $application->currentStage->order > $stage->order;
                                        // A stage is also completed if all its required steps are done and we are past it.
                                        // More complex logic can be added here to check if all steps in a stage are completed.
                                    @endphp
                                    <li class="flex-1">
                                        <a href="#" class="group flex flex-col items-center py-2 px-1 border-l-4 {{ $isCurrentStage ? 'border-indigo-600 dark:border-indigo-400' : ($isCompletedStage ? 'border-green-600 dark:border-green-400' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300 dark:hover:border-gray-500') }}">
                                            <span class="text-xs font-semibold uppercase tracking-wide {{ $isCurrentStage ? 'text-indigo-600 dark:text-indigo-400' : ($isCompletedStage ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200') }}">
                                                {{ $stage->name }}
                                            </span>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $stage->description }}</span>
                                        </a>
                                        {{-- You could expand this to show steps within each stage --}}
                                    </li>
                                @endforeach
                            </ol>
                        </nav>
                    </div>


                    @if ($currentStep)
                        <div class="mb-6">
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $currentStep->stage->name }} - {{ $currentStep->name }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $currentStep->description }}</p>
                            @if($currentStep->instructions)
                            <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-md">
                                <p class="text-sm text-blue-700 dark:text-blue-300">{!! nl2br(e($currentStep->instructions)) !!}</p>
                            </div>
                            @endif
                        </div>

                        <form wire:submit.prevent="submitStep">
                            @csrf
                            <div>
                                @include($formPartial, ['step' => $currentStep, 'responseData' => $responseData, 'uploadedFiles' => $uploadedFiles])
                            </div>

                            <div class="mt-8 flex justify-between items-center">
                                <div>
                                    @if ($this->applicationFlowService->getPreviousStep($application))
                                        <button type="button" wire:click="goToPreviousStep"
                                                class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-100 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-500 focus:outline-none focus:border-gray-400 focus:ring focus:ring-gray-200 active:bg-gray-400 disabled:opacity-25 transition">
                                            Previous
                                        </button>
                                    @endif
                                </div>
                                <div>
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-700 disabled:opacity-25 transition">
                                        {{ $this->applicationFlowService->getNextStep($application) ? 'Save and Continue' : 'Submit Application' }}
                                        <span wire:loading wire:target="submitStep" class="ml-2">
                                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    @elseif($application->status === 'submitted' || $application->status === 'completed')
                         @include('livewire.application-flow-partials._completed-application', ['application' => $application])
                    @else
                        <div class="text-center py-10">
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300">Application Issue</h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-2">
                                We could not determine the current step for your application.
                                Please <a href="{{ route('application.start') }}" class="text-indigo-600 hover:text-indigo-800">try starting your application again</a> or contact support.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> 