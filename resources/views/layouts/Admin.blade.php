<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Fintech Sekolah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    <style>
        /* Custom styles if needed, though Tailwind aims to avoid this */
        .sidebar-item-active {
            background-color: #3B82F6; /* blue-500 */
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal flex min-h-screen">

    <div class="flex w-full">
        <div class="bg-gray-800 text-white w-64 min-h-screen p-4 flex flex-col justify-between">
            <div>
                <div class="sidebar-heading text-xl font-bold pb-4 border-b border-gray-700 mb-4">Fintech Sekolah</div>
                <nav class="space-y-2">
                    <a href="{{ url('/admin/dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700
                        {{ Request::is('admin/dashboard') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ url('/admin/users') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700
                        {{ Request::is('admin/users*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-users mr-2"></i>Manajemen Pengguna
                    </a>
                    <a href="{{ url('admin/report') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700
                        {{ Request::is('admin/reports') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-chart-line mr-2"></i>Laporan Harian
                    </a>
                    {{-- Tambahkan link menu lain di sini --}}
                </nav>
            </div>

            <div class="mt-auto pt-4 border-t border-gray-700">
                <form id="logout-form-sidebar" action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-2 rounded-lg hover:bg-red-700 bg-red-600 text-white transition-colors duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
        <div id="page-content-wrapper" class="flex-1 flex flex-col">
            <nav class="bg-white shadow-sm py-4 px-6 flex justify-between items-center">
                <button id="sidebarToggle" class="text-gray-600 hover:text-gray-900 focus:outline-none md:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="relative ml-auto">
                    <button class="flex items-center text-gray-600 focus:outline-none dropdown-toggle" id="navbarDropdown" type="button" data-dropdown-toggle="userDropdown">
                        <i class="fas fa-user-circle text-2xl mr-2"></i> <span class="hidden md:inline">Admin Name</span> <i class="fas fa-chevron-down ml-2 text-sm"></i>
                    </button>
                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10" aria-labelledby="navbarDropdown">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <hr class="my-1 border-gray-200">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                           onclick="event.preventDefault(); document.getElementById('logout-form-navbar').submit();">
                            Logout
                        </a>
                        <form id="logout-form-navbar" action="{{ url('/logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </nav>

            <main class="p-6 flex-1">
                @yield('content')
            </main>

            <footer class="bg-white p-4 text-center text-gray-600 border-t border-gray-200">
                &copy; {{ date('Y') }} Fintech Sekolah. All rights reserved.
            </footer>
        </div>
        </div>
    <script>
        // Basic dropdown toggle
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownToggle = document.getElementById('navbarDropdown');
            const dropdownMenu = document.getElementById('userDropdown');

            if (dropdownToggle && dropdownMenu) {
                dropdownToggle.addEventListener('click', function () {
                    dropdownMenu.classList.toggle('hidden');
                });

                // Close dropdown if clicked outside
                window.addEventListener('click', function(e) {
                    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }

            // Simple sidebar toggle for small screens (Tailwind doesn't have built-in sidebar)
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar-wrapper');
            const wrapper = document.getElementById('wrapper');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('hidden'); // Hide/show sidebar on small screens
                // You might want more sophisticated responsive handling for sidebar
                // For a simple example, just hide/show. For better UX, use transform/translate
            });

            // Initial hide for mobile view if preferred
            const mediaQuery = window.matchMedia('(max-width: 768px)'); // md breakpoint
            function handleMediaQueryChange(e) {
                if (e.matches) {
                    // On mobile, hide sidebar initially
                    sidebar.classList.add('hidden');
                } else {
                    // On desktop, ensure sidebar is visible
                    sidebar.classList.remove('hidden');
                }
            }

            handleMediaQueryChange(mediaQuery); // Run on initial load
            mediaQuery.addListener(handleMediaQueryChange); // Listen for changes
        });
    </script>

    @stack('scripts')
</body>
</html>