<div>
    @if (!empty($step->form_schema))
        @if(isset($step->form_schema['confirmation_message']))
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">{{ $step->form_schema['confirmation_message'] }}</p>
        @endif

        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" wire:model.defer="responseData.confirmed" value="1"
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $step->form_schema['confirmation_label'] ?? 'I confirm' }} @if($step->is_required)<span class="text-red-500">*</span>@endif</span>
            </label>
            @error('responseData.confirmed') <span class="block text-xs text-red-500 mt-1 ml-6">{{ $message }}</span> @enderror
        </div>

        @if(isset($step->form_schema['date_field_label']))
            <div class="mt-4">
                <label for="responseData.action_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step->form_schema['date_field_label'] }} @if(Arr::get($step->form_schema, 'date_field_required', false))<span class="text-red-500">*</span>@endif</label>
                <input type="date" id="responseData.action_date" wire:model.defer="responseData.action_date"
                       class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
                @error('responseData.action_date') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        @endif

        @if(isset($step->form_schema['text_input_label']))
            <div class="mt-4">
                <label for="responseData.text_input" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step->form_schema['text_input_label'] }} @if(Arr::get($step->form_schema, 'text_input_required', false))<span class="text-red-500">*</span>@endif</label>
                <input type="text" id="responseData.text_input" wire:model.defer="responseData.text_input"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
                @error('responseData.text_input') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        @endif
    @else
        <p class="text-gray-500 dark:text-gray-400">Confirmation form schema not defined correctly.</p>
    @endif
</div> 