<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'APS Distribution')</title>
    
    <!-- Browser Tab Favicon -->
    <link rel="icon" type="image/webp" href="{{ asset('images/APS-logo.webp') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Top Navigation Bar -->
    <nav class="bg-slate-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between items-center h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="/dashboard" class="text-xl font-bold tracking-tight text-white flex items-center gap-2">
                        <img src="{{ asset('images/APS-logo.webp') }}" alt="APS Logo" class="h-8 w-auto">
                        <span>APS Distribution</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-2">
                    <a href="/dashboard" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Dashboard
                    </a>
                    <a href="/orders" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('orders*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Orders
                    </a>
                    <a href="/delivery-log" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('delivery-log*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Delivery Log
                    </a>
                    <a href="/products" 
                       class="px-3 py-2 rounded-md text-sm font-medium {{ request()->is('products*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        Inventory
                    </a>
                    <a href="/order-form" 
                       class="ml-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-semibold shadow-sm transition">
                        + New Order
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Page Content -->
    <main class="flex-grow py-8">
        @yield('content')
    </main>

    @livewireScripts
</body>
</html>