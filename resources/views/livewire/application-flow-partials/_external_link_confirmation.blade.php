<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp
    @if (!empty($step_form_schema) && isset($step_form_schema['link_url']) && isset($step_form_schema['link_text']) && isset($step_form_schema['confirmation_label']))
        <div class="mb-4 p-4 border border-indigo-200 dark:border-indigo-700 rounded-md bg-indigo-50 dark:bg-gray-800">
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                Please complete the required action on the following external website:
            </p>
            <a href="{{ $step_form_schema['link_url'] }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition dark:bg-indigo-500 dark:hover:bg-indigo-400">
                {{ $step_form_schema['link_text'] }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>

        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" wire:model.defer="responseData.confirmed" value="1"
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $step_form_schema['confirmation_label'] }} @if($step->is_required)<span class="text-red-500">*</span>@endif</span>
            </label>
            @error('responseData.confirmed') <span class="block text-xs text-red-500 mt-1 ml-6">{{ $message }}</span> @enderror
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">External link confirmation form schema not defined correctly. Requires 'link_url', 'link_text', and 'confirmation_label'.</p>
    @endif
</div> 