<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp

    {{-- @if (!empty($step_form_schema) && isset($step_form_schema['status_options_label']) && isset($step_form_schema['options'])) --}}
    @if (!empty($step_form_schema) && isset($step_form_schema['options']))
        @php
            $statusFieldId = "gbenga"; //Arr::get($step_form_schema, 'status_field_id', 'status_value');
            $dateFieldId = "gbenga"; //Arr::get($step_form_schema, 'date_field_id', 'status_date');
        @endphp
        <div class="mt-4">
            {{-- <label for="responseData.{{ $statusFieldId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step->form_schema['status_options_label'] }} @if($step->is_required)<span class="text-red-500">*</span>@endif</label> --}}
            <select id="responseData.{{ $statusFieldId }}" wire:model.defer="responseData.{{ $statusFieldId }}"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
                <option value="">-- Select Status --</option>
                @foreach ($step_form_schema['options'] as $value => $optionLabel)
                    <option value="{{ $value }}">{{ $optionLabel }}</option>
                @endforeach
            </select>
            @error('responseData.' . $statusFieldId) <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        @if(isset($step_form_schema['date_field_label']))
        <div class="mt-4">
            <label for="responseData.{{ $dateFieldId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step_form_schema['date_field_label'] }} @if(Arr::get($step->form_schema, 'date_field_required', $step->is_required))<span class="text-red-500">*</span>@endif</label>
            <input type="date" id="responseData.{{ $dateFieldId }}" wire:model.defer="responseData.{{ $dateFieldId }}"
                   class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
            @error('responseData.' . $dateFieldId) <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
        @endif

        @if(isset($step_form_schema['notes_label']))
        <div class="mt-4">
            <label for="responseData.status_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step_form_schema['notes_label'] }}</label>
            <textarea id="responseData.status_notes" wire:model.defer="responseData.status_notes" rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm"></textarea>
            @error('responseData.status_notes') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
        @endif
    @else
        <p class="text-gray-500 dark:text-gray-400">Status update form schema not defined correctly. Requires 'status_options_label' and 'options'.</p>
    @endif
</div> 