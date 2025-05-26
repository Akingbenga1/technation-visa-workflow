<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp
    
    @if (!empty($step_form_schema) && isset($step_form_schema['label']))
        @php
            $fieldId = Arr::get($step_form_schema, 'id', 'input_date');
            $isRequired = Arr::get($step_form_schema, 'required', $step->is_required);
        @endphp
        <div class="mt-4">
            <label for="responseData.{{ $fieldId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step_form_schema['label'] }} @if($isRequired)<span class="text-red-500">*</span>@endif</label>
            <input type="date" id="responseData.{{ $fieldId }}" wire:model.defer="responseData.{{ $fieldId }}"
                   class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
            @error('responseData.' . $fieldId) <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
         @if(isset($step_form_schema['notes_label']))
        <div class="mt-4">
            <label for="responseData.date_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step_form_schema['notes_label'] }}</label>
            <textarea id="responseData.date_notes" wire:model.defer="responseData.date_notes" rows="2"
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm"></textarea>
            @error('responseData.date_notes') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
        @endif
    @else
        <p class="text-gray-500 dark:text-gray-400">Date input form schema not defined correctly. Requires 'label'.</p>
    @endif
</div> 