<div>
    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp
    
    @if (!empty($step_form_schema) && isset($step_form_schema['content']) && isset($step_form_schema['acknowledgement_label']))
        @php
            $acknowledgementFieldId = Arr::get($step_form_schema, 'acknowledgement_field_id', 'acknowledged');
        @endphp

        <div class="prose dark:prose-invert max-w-none p-4 mb-4 bg-gray-50 dark:bg-gray-700/50 rounded-md">
            {!! nl2br(e($step_form_schema['content'])) !!}
        </div>

        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" wire:model.defer="responseData.{{ $acknowledgementFieldId }}" value="1"
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $step_form_schema['acknowledgement_label'] }} @if($step->is_required)<span class="text-red-500">*</span>@endif</span>
            </label>
            @error('responseData.' . $acknowledgementFieldId) <span class="block text-xs text-red-500 mt-1 ml-6">{{ $message }}</span> @enderror
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Information acknowledgement form schema not defined correctly. Requires 'content' and 'acknowledgement_label'.</p>
    @endif
</div> 