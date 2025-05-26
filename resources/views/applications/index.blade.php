<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    @if (session('status'))
                        <div class="mb-4 p-3 bg-green-100 text-green-700 dark:bg-green-700 dark:text-green-100 rounded-md">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold text-gray-900 dark:text-white">Your Visa Applications</h3>
                        <a href="{{ route('application.start') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Start New Application
                        </a>
                    </div>

                    @if($applications->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">You have not started any applications yet.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reference</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Current Stage</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Updated</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($applications as $app)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $app->reference_number }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($app->status == 'draft') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                                    @elseif($app->status == 'submitted' || $app->status == 'under_review') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                                    @elseif($app->status == 'approved') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                    @elseif($app->status == 'rejected') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200 @endif">
                                                    {{ Str::title(str_replace('_', ' ', $app->status)) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $app->currentStage->name ?? 'N/A' }} <br> <small>{{ $app->currentStep->name ?? ''}}</small></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $app->updated_at->format('M d, Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                @if($app->status !== 'approved' && $app->status !== 'rejected' && $app->status !== 'completed') {{-- Adjust conditions as needed --}}
                                                    <a href="{{ route('application.show', $app->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                                        {{ ($app->status == 'draft' || $app->status == 'under_review') ? 'Continue' : 'View' }}
                                                    </a>
                                                @else
                                                     <a href="{{ route('application.show', $app->id) }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">View Details</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $applications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 