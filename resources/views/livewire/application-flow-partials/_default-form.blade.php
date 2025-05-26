<div>
    <p class="text-gray-600 dark:text-gray-400">This step type (<code>{{ $step->form_type }}</code>) doesn't have a specific form template yet. Please define one or check the form schema.</p>
    @if (!empty($step->form_schema) && isset($step->form_schema['fields']))
        @foreach ($step->form_schema['fields'] as $field)
            <div class="mt-4">
                <label for="responseData.{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field['label'] ?? Str::title(str_replace('_', ' ', $field['id'])) }} @if(Arr::get($field, 'required', $step->is_required))<span class="text-red-500">*</span>@endif</label>
                @if ($field['type'] === 'textarea')
                    <textarea id="responseData.{{ $field['id'] }}" wire:model.defer="responseData.{{ $field['id'] }}" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm"></textarea>
                @elseif ($field['type'] === 'select' && isset($field['options']))
                     <select id="responseData.{{ $field['id'] }}" wire:model.defer="responseData.{{ $field['id'] }}"
                             class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
                        <option value="">Select an option</option>
                        @foreach ($field['options'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                @else {{-- Default to text input --}}
                    <input type="{{ $field['type'] ?? 'text' }}" id="responseData.{{ $field['id'] }}" wire:model.defer="responseData.{{ $field['id'] }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
                @endif
                @error('responseData.' . $field['id']) <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
            </div>
        @endforeach
    @elseif (!empty($step->form_schema) && isset($step->form_schema['content']))
        <div class="prose dark:prose-invert max-w-none">
            {!! $step->form_schema['content'] !!}
        </div>
    @endif
</div> 