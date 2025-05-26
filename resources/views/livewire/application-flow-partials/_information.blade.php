<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    print_r($step_form_schema);
    @endphp

    @if(!empty($step_form_schema) && array_key_exists('content', $step_form_schema) && isset($step_form_schema['content']))
        <div class="prose dark:prose-invert max-w-none p-4 bg-gray-50 dark:bg-gray-700/50 rounded-md">
            {!! nl2br(e($step_form_schema['content'])) !!}
        </div>
         <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" wire:model.defer="responseData.acknowledged" value="1"
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">I have read and understood this information. <span class="text-red-500">*</span></span>
            </label>
            @error('responseData.acknowledged') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Information content not found in schema.</p>
    @endif
</div> 