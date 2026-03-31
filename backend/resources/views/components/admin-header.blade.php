@props(['title', 'action_text' => null, 'action_url' => null, 'breadcrumbs' => []])

<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8 border-b border-gray-200/80 pb-5">
    <div>
        @if(count($breadcrumbs) > 0)
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    @foreach($breadcrumbs as $breadcrumb)
                        <li class="inline-flex items-center">
                            @if(isset($breadcrumb['url']))
                                <a href="{{ $breadcrumb['url'] }}" class="text-[11px] uppercase tracking-[0.2em] font-semibold text-gray-500 hover:text-mitologi-navy dark:text-gray-400 dark:hover:text-white transition-colors">
                                    {{ $breadcrumb['title'] }}
                                </a>
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            @else
                                <span class="text-[11px] uppercase tracking-[0.2em] font-semibold text-gray-400 dark:text-gray-500">
                                    {{ $breadcrumb['title'] }}
                                </span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
        <h2 class="text-3xl font-display font-semibold text-mitologi-navy dark:text-white tracking-tight leading-none">
            {{ $title }}
        </h2>
    </div>
    
    @if($action_text && $action_url)
        <a href="{{ $action_url }}" class="inline-flex items-center justify-center w-full sm:w-auto px-5 py-3 bg-mitologi-navy border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-[0.18em] hover:bg-mitologi-navy-light focus:outline-none focus:ring-2 focus:ring-mitologi-gold focus:ring-offset-2 transition-colors duration-150 shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            {{ $action_text }}
        </a>
    @endif
</div>
