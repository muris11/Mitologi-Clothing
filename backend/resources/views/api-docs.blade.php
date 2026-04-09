@extends('layouts.api-docs')

@section('content')
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>

<div x-data="{ open: false }" class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100  ">
    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/95  backdrop-blur-xl border-b border-gray-200  shadow-soft">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between gap-3">
                <!-- Left: Menu Toggle + Logo -->
                <div class="flex items-center gap-3">
                    <!-- Mobile Menu Toggle -->
                    <button
                        @click="open = !open"
                        type="button"
                        class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-gray-100  transition-colors"
                        aria-label="Toggle navigation menu"
                    >
                        <i class="fa-solid fa-bars text-mitologi-navy  text-xl"></i>
                    </button>

                    <!-- Logo -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="w-9 h-9 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light flex items-center justify-center shadow-lg flex-shrink-0">
                            <i class="fa-solid fa-book-open text-mitologi-gold text-sm sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-lg sm:text-2xl font-bold text-mitologi-navy  truncate">API Documentation</h1>
                            <p class="text-xs sm:text-sm text-gray-500  hidden sm:block">Mitologi Clothing REST API v1.0</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Base URL + JSON Button -->
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                    <code class="hidden md:block px-3 sm:px-4 py-2 bg-gray-100  rounded-lg text-xs sm:text-sm font-mono text-mitologi-navy  border border-gray-200  truncate max-w-[200px] lg:max-w-none">
                        {{ $baseUrl }}
                    </code>
                    <a href="{{ route('api.docs.json') }}" class="flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 bg-gradient-to-r from-mitologi-navy to-mitologi-navy-light text-white rounded-lg hover:shadow-lg transition-all duration-300 text-xs sm:text-sm font-medium whitespace-nowrap">
                        <i class="fa-solid fa-code"></i>
                        <span class="hidden sm:inline">JSON</span>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar Overlay -->
    <div
        x-show="open"
        @click="open = false"
        class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"
        id="sidebar-overlay"
        :class="{ 'overlay-visible': open }"
    ></div>

    <div class="max-w-[1600px] mx-auto flex relative">
        <!-- Sidebar Navigation -->
        <aside
            id="api-sidebar"
            class="fixed lg:sticky top-[60px] sm:top-[72px] left-0 z-40 w-72 h-[calc(100vh-60px)] sm:h-[calc(100vh-72px)] bg-white/98  lg:bg-transparent lg: backdrop-blur-xl lg:backdrop-blur-none border-r border-gray-200  lg:border-none overflow-y-auto"
            :class="{ 'sidebar-open': open }"
        >
            <div class="p-4 lg:py-6 lg:px-4">
                <!-- Mobile Close Button -->
                <div class="lg:hidden flex justify-end mb-4">
                    <button @click="open = false" class="p-2 rounded-lg hover:bg-gray-100  transition-colors">
                        <i class="fa-solid fa-xmark text-gray-500  text-xl"></i>
                    </button>
                </div>

                <nav class="space-y-1">
                    @foreach($endpoints as $key => $section)
                    <a
                        href="#section-{{ $key }}"
                        @click="open = false"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200 hover:bg-white  group"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gray-100  flex items-center justify-center group-hover:bg-mitologi-gold/20 transition-colors flex-shrink-0">
                            <i class="fa-solid fa-{{ $section['icon'] }} text-gray-500  group-hover:text-mitologi-gold text-sm"></i>
                        </div>
                        <span class="text-gray-700  group-hover:text-mitologi-navy ">{{ $section['title'] }}</span>
                    </a>
                    @endforeach
                </nav>

                <!-- Quick Reference -->
                <div class="mt-6 p-4 bg-white  rounded-xl border border-gray-200 ">
                    <h3 class="text-xs font-bold text-mitologi-gold uppercase tracking-wider mb-3">Status Codes</h3>
                    <div class="space-y-2 text-xs sm:text-sm">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
                            <span class="text-gray-600 ">200 Success</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                            <span class="text-gray-600 ">201 Created</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-yellow-500 flex-shrink-0"></span>
                            <span class="text-gray-600 ">400 Bad Request</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500 flex-shrink-0"></span>
                            <span class="text-gray-600 ">401 Unauthorized</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-600 flex-shrink-0"></span>
                            <span class="text-gray-600 ">404 Not Found</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-700 flex-shrink-0"></span>
                            <span class="text-gray-600 ">422 Validation Error</span>
                        </div>
                    </div>
                </div>

                <!-- Auth Info -->
                <div class="mt-4 p-4 bg-white  rounded-xl border border-gray-200 ">
                    <h3 class="text-xs font-bold text-mitologi-gold uppercase tracking-wider mb-3">Authentication</h3>
                    <div class="space-y-3 text-xs text-gray-600 ">
                        <div>
                            <p class="font-semibold mb-1 text-gray-700 ">Bearer Token:</p>
                            <code class="block p-2 bg-gray-100  rounded text-mitologi-gold break-all text-[10px]">
                                Authorization: Bearer {token}
                            </code>
                        </div>
                        <div>
                            <p class="font-semibold mb-1 text-gray-700 ">Session Headers:</p>
                            <code class="block p-2 bg-gray-100  rounded text-mitologi-gold break-all text-[10px]">
                                X-Cart-Id: {cart_id}<br>
                                X-Session-Id: {session_id}
                            </code>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 w-full min-w-0 lg:ml-0 p-4 sm:p-6">
            <!-- Introduction -->
            <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-gradient-to-r from-mitologi-navy to-mitologi-navy-light rounded-xl sm:rounded-2xl text-white shadow-lg">
                <h2 class="text-lg sm:text-xl font-bold mb-2 flex items-center gap-2">
                    <i class="fa-solid fa-rocket text-mitologi-gold"></i>
                    Getting Started
                </h2>
                <p class="text-gray-300 text-xs sm:text-sm leading-relaxed">
                    Welcome to the Mitologi Clothing API. This documentation provides complete information about all available endpoints, request/response formats, and authentication requirements.
                </p>
            </div>

            <!-- Sections -->
            @foreach($endpoints as $key => $section)
            <section id="section-{{ $key }}" class="mb-6 sm:mb-8 scroll-mt-24">
                <!-- Section Header -->
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-4 pb-4 border-b border-gray-200 ">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl bg-gradient-to-br from-mitologi-navy to-mitologi-navy-light flex items-center justify-center shadow-md flex-shrink-0">
                            <i class="fa-solid fa-{{ $section['icon'] }} text-mitologi-gold text-lg sm:text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-lg sm:text-xl font-bold text-mitologi-navy ">{{ $section['title'] }}</h2>
                            <p class="text-xs sm:text-sm text-gray-500 ">{{ $section['description'] }}</p>
                        </div>
                    </div>
                    @if(isset($section['routes'][0]['auth']) && $section['routes'][0]['auth'])
                    <span class="sm:ml-auto self-start sm:self-center px-3 py-1 bg-amber-100  text-amber-700  rounded-full text-xs font-semibold flex items-center gap-1.5 w-fit">
                        <i class="fa-solid fa-lock"></i> <span>Auth Required</span>
                    </span>
                    @else
                    <span class="sm:ml-auto self-start sm:self-center px-3 py-1 bg-green-100  text-green-700  rounded-full text-xs font-semibold flex items-center gap-1.5 w-fit">
                        <i class="fa-solid fa-unlock"></i> <span>Public</span>
                    </span>
                    @endif
                </div>

                <!-- Endpoints -->
                <div class="space-y-3 sm:space-y-4">
                    @foreach($section['routes'] as $route)
                    <div class="bg-white  rounded-xl border border-gray-200  overflow-hidden shadow-soft hover:shadow-hover transition-shadow duration-300">
                        <!-- Endpoint Header -->
                        <button onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full p-3 sm:p-4 flex items-center gap-2 sm:gap-4 hover:bg-gray-50  transition-colors text-left">
                            <!-- HTTP Method Badge -->
                            @php
                                $methodColors = [
                                    'GET' => 'bg-blue-100 text-blue-700   border-blue-200 ',
                                    'POST' => 'bg-green-100 text-green-700   border-green-200 ',
                                    'PUT' => 'bg-amber-100 text-amber-700   border-amber-200 ',
                                    'DELETE' => 'bg-red-100 text-red-700   border-red-200 ',
                                    'PATCH' => 'bg-purple-100 text-purple-700   border-purple-200 ',
                                ];
                            @endphp
                            <span class="px-2 sm:px-3 py-1 rounded-lg text-[10px] sm:text-xs font-bold border {{ $methodColors[$route['method']] ?? 'bg-gray-100 text-gray-700' }} min-w-[50px] sm:min-w-[70px] text-center flex-shrink-0">
                                {{ $route['method'] }}
                            </span>

                            <!-- Path -->
                            <code class="flex-1 font-mono text-xs sm:text-sm text-mitologi-navy  truncate min-w-0">
                                {{ $route['path'] }}
                            </code>

                            <!-- Auth/Admin/Webhook Badges (Desktop) -->
                            <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                                @if(isset($route['auth']) && $route['auth'])
                                <i class="fa-solid fa-lock text-amber-500" title="Authentication Required"></i>
                                @endif

                                @if(isset($route['admin']) && $route['admin'])
                                <span class="px-2 py-0.5 bg-purple-100  text-purple-700  rounded text-[10px] font-semibold">
                                    ADMIN
                                </span>
                                @endif

                                @if(isset($route['webhook']) && $route['webhook'])
                                <span class="px-2 py-0.5 bg-pink-100  text-pink-700  rounded text-[10px] font-semibold">
                                    WEBHOOK
                                </span>
                                @endif
                            </div>

                            <!-- Expand Icon -->
                            <i class="fa-solid fa-chevron-down text-gray-400 flex-shrink-0"></i>
                        </button>

                        <!-- Endpoint Details (Hidden by default) -->
                        <div class="hidden border-t border-gray-200 ">
                            <div class="p-3 sm:p-4 space-y-3 sm:space-y-4">
                                <!-- Mobile Badges -->
                                <div class="sm:hidden flex flex-wrap gap-2">
                                    @if(isset($route['auth']) && $route['auth'])
                                    <span class="px-2 py-0.5 bg-amber-100  text-amber-700  rounded text-[10px] font-semibold flex items-center gap-1">
                                        <i class="fa-solid fa-lock"></i> Auth
                                    </span>
                                    @endif
                                    @if(isset($route['admin']) && $route['admin'])
                                    <span class="px-2 py-0.5 bg-purple-100  text-purple-700  rounded text-[10px] font-semibold">
                                        ADMIN
                                    </span>
                                    @endif
                                    @if(isset($route['webhook']) && $route['webhook'])
                                    <span class="px-2 py-0.5 bg-pink-100  text-pink-700  rounded text-[10px] font-semibold">
                                        WEBHOOK
                                    </span>
                                    @endif
                                </div>

                                <!-- Description -->
                                <p class="text-xs sm:text-sm text-gray-600 ">{{ $route['description'] }}</p>

                                <!-- Route Name -->
                                <div class="flex flex-wrap items-center gap-2 text-xs">
                                    <span class="text-gray-500 ">Route:</span>
                                    <code class="px-2 py-1 bg-gray-100  rounded text-mitologi-navy  text-[10px] sm:text-xs">{{ $route['name'] }}</code>
                                </div>

                                <!-- Headers -->
                                @if(isset($route['headers']))
                                <div class="bg-gray-50  rounded-lg p-3">
                                    <h4 class="text-[10px] sm:text-xs font-bold text-gray-500  uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fa-solid fa-header"></i> Required Headers
                                    </h4>
                                    <ul class="space-y-1 text-xs sm:text-sm">
                                        @foreach($route['headers'] as $header)
                                        <li class="flex items-center gap-2">
                                            <i class="fa-solid fa-check text-green-500 text-xs flex-shrink-0"></i>
                                            <code class="text-mitologi-navy  break-all">{{ $header }}</code>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <!-- Parameters -->
                                @if(!empty($route['params']))
                                <div>
                                    <h4 class="text-[10px] sm:text-xs font-bold text-gray-500  uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-list"></i> Parameters
                                    </h4>
                                    <div class="overflow-x-auto -mx-3 sm:mx-0 px-3 sm:px-0">
                                        <table class="w-full text-xs sm:text-sm min-w-[500px]">
                                            <thead>
                                                <tr class="text-left border-b border-gray-200 ">
                                                    <th class="pb-2 pr-2 sm:pr-4 text-gray-500  font-medium">Name</th>
                                                    <th class="pb-2 pr-2 sm:pr-4 text-gray-500  font-medium">Type</th>
                                                    <th class="pb-2 pr-2 sm:pr-4 text-gray-500  font-medium">Req</th>
                                                    <th class="pb-2 text-gray-500  font-medium">Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($route['params'] as $param)
                                                <tr class="border-b border-gray-100 ">
                                                    <td class="py-2 sm:py-3 pr-2 sm:pr-4">
                                                        <code class="text-mitologi-navy  font-semibold text-[10px] sm:text-xs">
                                                            {{ $param['name'] }}
                                                            @if(isset($param['location']) && $param['location'] === 'path')
                                                            <span class="text-[10px] text-amber-500 ml-1">(path)</span>
                                                            @endif
                                                        </code>
                                                    </td>
                                                    <td class="py-2 sm:py-3 pr-2 sm:pr-4">
                                                        <span class="px-1.5 sm:px-2 py-0.5 bg-gray-100  rounded text-[10px] sm:text-xs text-gray-600 ">
                                                            {{ $param['type'] }}
                                                        </span>
                                                    </td>
                                                    <td class="py-2 sm:py-3 pr-2 sm:pr-4">
                                                        @if(isset($param['required']) && $param['required'])
                                                        <span class="text-red-500 text-[10px] sm:text-xs font-semibold">Yes</span>
                        @else
                                                        <span class="text-gray-400 text-[10px] sm:text-xs">No</span>
                        @endif
                                                    </td>
                                                    <td class="py-2 sm:py-3 text-gray-600  text-[10px] sm:text-xs">
                                                        {{ $param['description'] }}
                                                        @if(isset($param['default']))
                                                        <br><span class="text-mitologi-gold">Default: {{ $param['default'] }}</span>
                        @endif
                                                        @if(isset($param['options']))
                                                        <br><span class="text-blue-500">Options: {{ implode(', ', $param['options']) }}</span>
                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endif

                                <!-- Body Example -->
                                @if(isset($route['body_example']))
                                <div class="bg-gray-900 rounded-lg p-3 sm:p-4 overflow-x-auto">
                                    <h4 class="text-[10px] sm:text-xs font-bold text-mitologi-gold uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fa-solid fa-code"></i> Request Body Example
                                    </h4>
                                    <pre class="text-[10px] sm:text-xs text-green-400 font-mono whitespace-pre-wrap sm:whitespace-pre">{{ json_encode($route['body_example'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                                @endif

                                <!-- Response -->
                                @if(isset($route['response']))
                                <div class="bg-gray-50  rounded-lg p-3">
                                    <h4 class="text-[10px] sm:text-xs font-bold text-gray-500  uppercase tracking-wider mb-2 flex items-center gap-2">
                                        <i class="fa-solid fa-reply"></i> Response
                                    </h4>
                                    <ul class="space-y-1 text-xs sm:text-sm">
                                        @foreach($route['response'] as $field => $type)
                                        <li class="flex items-start gap-2">
                                            <i class="fa-solid fa-arrow-right text-mitologi-gold text-xs mt-1 flex-shrink-0"></i>
                                            <div class="min-w-0">
                                                <code class="text-mitologi-navy  font-semibold text-xs">{{ $field }}</code>
                                                <span class="text-gray-500  text-xs"> - {{ $type }}</span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endforeach

            <!-- Footer -->
            <footer class="mt-8 sm:mt-12 py-6 sm:py-8 border-t border-gray-200  text-center">
                <p class="text-xs sm:text-sm text-gray-500 ">
                    <i class="fa-solid fa-code text-mitologi-gold mr-2"></i>
                    Mitologi Clothing API Documentation &copy; {{ date('Y') }}
                </p>
                <p class="text-[10px] sm:text-xs text-gray-400  mt-2">
                    For support, contact the development team.
                </p>
            </footer>
        </main>
    </div>
</div>
@endsection

