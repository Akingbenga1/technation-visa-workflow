<div>

    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp

    @if (!empty($step_form_schema) && isset($step_form_schema['appointment_type']) && isset($step_form_schema['date_field_label']) && isset($step_form_schema['location_field_label']) && isset($step_form_schema['confirmation_label']))
        @php
            $dateFieldId = Arr::get($step_form_schema, 'date_field_id', 'appointment_date');
            $locationFieldId = Arr::get($step_form_schema, 'location_field_id', 'appointment_location');
            $confirmationFieldId = Arr::get($step_form_schema, 'confirmation_field_id', 'appointment_attended');
        @endphp

        <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md">
            <h4 class="text-md font-semibold text-gray-800 dark:text-gray-200">{{ $step_form_schema['appointment_type'] }}</h4>
        </div>

        <div class="mt-4">
            <label for="responseData.{{ $dateFieldId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step_form_schema['date_field_label'] }} @if(Arr::get($step_form_schema, 'date_field_required', $step->is_required))<span class="text-red-500">*</span>@endif</label>
            <input type="date" id="responseData.{{ $dateFieldId }}" wire:model.defer="responseData.{{ $dateFieldId }}"
                   class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
            @error('responseData.' . $dateFieldId) <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="responseData.{{ $locationFieldId }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step_form_schema['location_field_label'] }} @if(Arr::get($step_form_schema, 'location_field_required', false))<span class="text-red-500">*</span>@endif</label>
            <input type="text" id="responseData.{{ $locationFieldId }}" wire:model.defer="responseData.{{ $locationFieldId }}"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm"
                   placeholder="e.g., City, Country or Centre Name">
            @error('responseData.' . $locationFieldId) <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" wire:model.defer="responseData.{{ $confirmationFieldId }}" value="1"
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $step_form_schema['confirmation_label'] }} @if($step->is_required)<span class="text-red-500">*</span>@endif</span>
            </label>
            @error('responseData.' . $confirmationFieldId) <span class="block text-xs text-red-500 mt-1 ml-6">{{ $message }}</span> @enderror
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Appointment confirmation form schema not defined correctly. Requires 'appointment_type', 'date_field_label', 'location_field_label', and 'confirmation_label'.</p>
    @endif
</div> 