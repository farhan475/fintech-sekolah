<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Fintech Sekolah</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Login ke Fintech Sekolah</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-500">
            </div>

            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center text-sm">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <span class="ml-2 text-gray-600">Remember Me</span>
                </label>
                {{-- Optional: Forgot password --}}
                {{-- <a href="#" class="text-sm text-blue-600 hover:underline">Forgot Password?</a> --}}
            </div>

            <div class="mb-4">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                    Login
                </button>
            </div>

            <div class="text-center">
                <span class="text-sm text-gray-600">Belum punya akun?</span>
                <a href="{{ route('register') }}"
                    class="text-sm text-blue-600 hover:underline font-medium ml-1">Daftar</a>
            </div>
        </form>
    </div>

</body>
</html>
