<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp
    
    @if (!empty($step_form_schema) && isset($step_form_schema['items']) && isset($step_form_schema['confirmation_label']))
        @php
            $confirmationFieldId = Arr::get($step_form_schema, 'confirmation_field_id', 'all_documents_confirmed');
        @endphp

        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Please confirm each document has been submitted/uploaded as required:</p>

        @foreach ($step_form_schema['items'] as $item)
            @php
                // Individual item is required if its own 'required' flag is true,
                // OR if the 'required' flag is not set and the overall step is required.
                $itemIsRequired = Arr::get($item, 'required', $step->is_required && !isset($item['required']));
            @endphp
            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.defer="responseData.{{ $item['id'] }}" value="1"
                           class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $item['label'] }} @if($itemIsRequired)<span class="text-red-500">*</span>@endif</span>
                </label>
                @if(isset($item['description']))
                    <p class="ml-6 text-xs text-gray-500 dark:text-gray-400">{{ $item['description'] }}</p>
                @endif
                @error('responseData.' . $item['id']) <span class="block text-xs text-red-500 mt-1 ml-6">{{ $message }}</span> @enderror
            </div>
        @endforeach

        <hr class="my-6 border-gray-300 dark:border-gray-600">

        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" wire:model.defer="responseData.{{ $confirmationFieldId }}" value="1"
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                <span class="ml-2 text-sm font-medium text-gray-800 dark:text-gray-200">{{ $step_form_schema['confirmation_label'] }} @if($step->is_required)<span class="text-red-500">*</span>@endif</span>
            </label>
            @error('responseData.' . $confirmationFieldId) <span class="block text-xs text-red-500 mt-1 ml-6">{{ $message }}</span> @enderror
        </div>

    @else
        <p class="text-gray-500 dark:text-gray-400">Document checklist confirmation schema not defined correctly. Requires 'items' array and 'confirmation_label'.</p>
    @endif
</div> 