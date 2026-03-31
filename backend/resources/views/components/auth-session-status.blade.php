@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-800 dark:text-green-200 bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 rounded-xl p-4 mb-4 shadow-sm flex items-center gap-2']) }}>
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        {{ $status }}
    </div>
@endif
