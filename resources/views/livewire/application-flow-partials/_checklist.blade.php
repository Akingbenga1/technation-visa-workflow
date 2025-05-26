<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp

    @if (!empty($step_form_schema) && isset($step_form_schema['questions']))
        @foreach ($step_form_schema['questions'] as $question)
            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.defer="responseData.{{ $question['id'] }}" value="1"
                           class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $question['text'] }} @if(Arr::get($question, 'required', $step->is_required))<span class="text-red-500">*</span>@endif</span>
                </label>
                @error('responseData.' . $question['id']) <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        @endforeach
    @else
        <p class="text-gray-500 dark:text-gray-400">Checklist schema not defined correctly.</p>
    @endif
</div> 