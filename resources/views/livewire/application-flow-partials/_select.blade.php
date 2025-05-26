<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp
    @if (!empty($step_form_schema) && isset($step_form_schema['label']) && isset($step_form_schema['options']))
        @php
            $fieldId = "gbenga"; // Arr::get($step->form_schema, 'id', 'selected_option');
        @endphp
        <div class="mt-4">
            <label for="responseData.{{ $fieldId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step_form_schema['label'] }} @if($step->is_required)<span class="text-red-500">*</span>@endif</label>
            <select id="responseData.{{ $fieldId }}" wire:model.defer="responseData.{{ $fieldId }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
                <option value="">-- Please select --</option>
                @foreach ($step_form_schema['options'] as $value => $optionLabel)
                    <option value="{{ $value }}">{{ $optionLabel }}</option>
                @endforeach
            </select>
            @error('responseData.' . $fieldId) <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Select form schema not defined correctly. Requires 'label' and 'options'.</p>
    @endif
</div> 