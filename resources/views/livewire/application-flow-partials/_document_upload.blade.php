<div>

    @php
    $step_form_schema = json_decode($step->form_schema, true);
    @endphp
    @if (!empty($step_form_schema) && isset($step_form_schema['fields']))
        @foreach ($step_form_schema['fields'] as $field)
            @if ($field['type'] === 'file')
                <div class="mt-6 p-4 border dark:border-gray-700 rounded-md">
                    <label for="uploadedFiles.{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field['label'] ?? Str::title(str_replace('_', ' ', $field['id'])) }} @if(Arr::get($field, 'required', $step->is_required))<span class="text-red-500">*</span>@endif</label>
                    @if (isset($responseData[$field['id']]) && !empty($responseData[$field['id']]))
                        <div class="mt-2 text-sm">
                            <p class="text-green-600 dark:text-green-400">Current file(s):</p>
                            @if(is_array($responseData[$field['id']]))
                                <ul>
                                @foreach($responseData[$field['id']] as $filePath)
                                    <li class="ml-4"><a href="{{ Storage::url($filePath) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200">{{ basename($filePath) }}</a></li>
                                @endforeach
                                </ul>
                            @else
                                <p class="ml-4"><a href="{{ Storage::url($responseData[$field['id']]) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200">{{ basename($responseData[$field['id']]) }}</a></p>
                            @endif
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">To replace, upload a new file below.</p>
                        </div>
                    @endif

                    <input type="file" id="uploadedFiles.{{ $field['id'] }}"
                           wire:model="uploadedFiles.{{ $field['id'] }}"
                           @if(Arr::get($field, 'multiple')) multiple @endif
                           accept="{{ Arr::get($field, 'accept', '*') }}"
                           class="mt-2 block w-full text-sm text-gray-900 dark:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 focus:outline-none focus:border-indigo-500 focus:ring-indigo-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-l-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 dark:file:bg-indigo-800 file:text-indigo-700 dark:file:text-indigo-200
                                  hover:file:bg-indigo-100 dark:hover:file:bg-indigo-700">

                    <div wire:loading wire:target="uploadedFiles.{{ $field['id'] }}" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Uploading...</div>

                    @if (isset($uploadedFiles[$field['id']]))
                        <div class="mt-2 text-xs">
                            @if(is_array($uploadedFiles[$field['id']]))
                                <p>Selected files for {{ $field['label'] ?? $field['id'] }}:</p>
                                <ul>
                                    @foreach($uploadedFiles[$field['id']] as $tempFile)
                                    <li>{{ $tempFile->getClientOriginalName() }} ({{ round($tempFile->getSize() / 1024, 2) }} KB)</li>
                                    @endforeach
                                </ul>
                            @else
                                <p>Selected file for {{ $field['label'] ?? $field['id'] }}: {{ $uploadedFiles[$field['id']]->getClientOriginalName() }} ({{ round($uploadedFiles[$field['id']]->getSize() / 1024, 2) }} KB)</p>
                            @endif
                        </div>
                    @endif
                    @error('uploadedFiles.' . $field['id']) <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                    @error('uploadedFiles.' . $field['id'] . '.*') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror <!-- For multiple file errors -->
                </div>
            @else
                 {{-- Handle other field types within a document_upload form if any --}}
                <div class="mt-4">
                    <label for="responseData.{{ $field['id'] }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field['label'] ?? Str::title(str_replace('_', ' ', $field['id'])) }} @if(Arr::get($field, 'required', $step->is_required))<span class="text-red-500">*</span>@endif</label>
                    <input type="{{ $field['type'] ?? 'text' }}" id="responseData.{{ $field['id'] }}" wire:model.defer="responseData.{{ $field['id'] }}"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
                    @error('responseData.' . $field['id']) <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            @endif
        @endforeach
    @else
        <p class="text-gray-500 dark:text-gray-400">Document upload schema not defined correctly.</p>
    @endif
</div> 