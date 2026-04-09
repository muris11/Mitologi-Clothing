 @forelse($adminNotifications as $notif)
 <div class="px-5 py-4 hover:bg-gray-50 transition-all duration-200 cursor-pointer border-b border-gray-50 last:border-b-0 active:bg-gray-100">
    <div class="flex items-start gap-3">
        <div class="p-2 rounded-full flex-shrink-0
            @if($notif['color'] === 'blue') bg-blue-50  text-blue-600
            @elseif($notif['color'] === 'yellow') bg-yellow-50  text-yellow-600
            @elseif($notif['color'] === 'red') bg-red-50  text-red-600
            @elseif($notif['color'] === 'green') bg-green-50  text-green-600
            @elseif($notif['color'] === 'amber') bg-amber-50  text-amber-600
            @endif
        ">
            @if($notif['icon'] === 'shopping-bag')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            @elseif($notif['icon'] === 'exclamation')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
            @elseif($notif['icon'] === 'x-circle')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @elseif($notif['icon'] === 'user-plus')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            @elseif($notif['icon'] === 'receipt-refund')
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            @endif
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-sm text-gray-800  font-medium truncate">{{ $notif['title'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">{{ $notif['subtitle'] }} · {{ $notif['time'] }}</p>
        </div>
    </div>
</div>
@empty
<div class="px-4 py-8 text-center">
    <svg class="w-10 h-10 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
    <p class="text-sm text-gray-400">Tidak ada notifikasi baru</p>
</div>
@endforelse

