<div class="text-center py-10">
    <svg class="mx-auto h-12 w-12 text-green-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    <h3 class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">Application {{ ucfirst($application->status) }}</h3>
    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
        Your application (Ref: {{ $application->reference_number }}) has been {{ $application->status }}.
    </p>
    @if($application->completed_at)
    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
        Completed on: {{ $application->completed_at->format('F j, Y, g:i a') }}
    </p>
    @endif
    <div class="mt-6">
        <a href="{{ route('applications.index') }}"
           class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            View My Applications
        </a>
    </div>
</div> 