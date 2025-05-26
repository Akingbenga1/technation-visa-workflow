<div>
    @if (!empty($step->form_schema))
        @if(isset($step->form_schema['payment_details_text']))
            <div class="prose dark:prose-invert max-w-none p-4 mb-4 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                {!! nl2br(e($step->form_schema['payment_details_text'])) !!}
            </div>
        @endif

        <div class="mt-4">
            <label for="responseData.transaction_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step->form_schema['transaction_id_label'] ?? 'Transaction ID / Reference' }} @if(Arr::get($step->form_schema, 'transaction_id_required', $step->is_required))<span class="text-red-500">*</span>@endif</label>
            <input type="text" id="responseData.transaction_id" wire:model.defer="responseData.transaction_id"
                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
            @error('responseData.transaction_id') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="responseData.payment_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $step->form_schema['payment_date_label'] ?? 'Payment Date' }} @if(Arr::get($step->form_schema, 'payment_date_required', $step->is_required))<span class="text-red-500">*</span>@endif</label>
            <input type="date" id="responseData.payment_date" wire:model.defer="responseData.payment_date"
                   class="mt-1 block w-full sm:w-1/2 rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200 sm:text-sm">
            @error('responseData.payment_date') <span class="block text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="mt-6">
            <label class="flex items-center">
                <input type="checkbox" wire:model.defer="responseData.payment_confirmed" value="1"
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:checked:bg-indigo-500">
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $step->form_schema['confirmation_label'] ?? 'I confirm the payment has been made as described.' }} @if($step->is_required)<span class="text-red-500">*</span>@endif</span>
            </label>
            @error('responseData.payment_confirmed') <span class="block text-xs text-red-500 mt-1 ml-6">{{ $message }}</span> @enderror
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-400">Payment confirmation schema not defined correctly.</p>
    @endif
</div> 