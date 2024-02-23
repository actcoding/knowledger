<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <title>🛂 Login | Knowledger</title>

    {{-- <link rel="manifest" href="/manifest.json"> --}}

    {{-- <link rel="apple-touch-icon" href="{{ $kb->publicLogoPath() }}">
    <link rel="shortcut icon" href="{{ $kb->publicLogoPath() }}">
    <link rel="preload" href="{{ $kb->publicLogoPath() }}"> --}}

    @vite('resources/css/app.css')
</head>
<body class="select-none bg-violet-300">

<div class="container mx-auto mt-48">
    <div class="bg-white rounded-lg drop-shadow-lg max-w-screen-sm mx-auto p-8">
        <p class="text-4xl font-bold">Welcome back!</p>
        <p class="text-lg font-medium">Please sign in.</p>

        <form action="{{ route('login') }}" method="post" class="mt-8 flex flex-col gap-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-medium text-gray-700">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="john.doe@example.org"
                    class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"
                />
            </div>

            <div>
                <label for="password" class="block text-xs font-medium text-gray-700">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="************"
                    class="mt-1 w-full rounded-md border-gray-200 shadow-sm sm:text-sm"
                />
            </div>

            <button
                class="mt-8 inline-block rounded border border-indigo-600 bg-indigo-600 px-12 py-3 text-sm font-medium text-white hover:bg-transparent hover:text-indigo-600 focus:outline-none focus:ring active:text-indigo-500"
                type="submit"
            >
                Sign in
            </button>

            <span class="relative flex justify-center my-4">
                <div
                  class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-transparent bg-gradient-to-r from-transparent via-gray-500 to-transparent opacity-75"
                ></div>

                <span class="relative z-10 bg-white px-6 uppercase">or</span>
            </span>


            <a
                class="inline-block rounded border border-blue-600 bg-blue-600 px-12 py-3 text-sm font-medium text-white text-center hover:bg-transparent hover:text-blue-600 focus:outline-none focus:ring active:text-blue-500"
                href="{{ route('auth.redirect') }}"
            >
                Use Open ID Connect
            </a>
        </form>
    </div>
</div>

</body>
</html>
