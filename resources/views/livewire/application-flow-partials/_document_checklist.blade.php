<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp

    @if (!empty($step_form_schema) && array_key_exists('items', $step_form_schema) && isset($step_form_schema['items']))
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Please confirm you have prepared the following documents:</p>
        @foreach ($step_form_schema['items'] as $item)
            <div class="mt-4">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.defer="responseData.{{ $item['id'] }}" value="1"
                           class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $item['label'] }} @if(Arr::get($item, 'required', $step->is_required))<span class="text-red-500">*</span>@endif</span>
                </label>
                @if(isset($item['description']))
                    <p class="ml-6 text-xs text-gray-500 dark:text-gray-400">{{ $item['description'] }}</p>
                @endif
                @error('responseData.' . $item['id']) <span class="block text-xs text-red-500 mt-1 ml-6">{{ $message }}</span> @enderror
            </div>
        @endforeach
    @else
        <p class="text-gray-500 dark:text-gray-400">Document checklist schema not defined correctly. Requires 'items' array.</p>
    @endif
</div> 