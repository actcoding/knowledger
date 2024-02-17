<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <title>📚 {{ $kb->name }}</title>

    {{-- <link rel="manifest" href="/manifest.json"> --}}

    @if($kb->logo !== null)
    <link rel="apple-touch-icon" href="{{ $kb->publicLogoPath() }}">
    <link rel="shortcut icon" href="{{ $kb->publicLogoPath() }}">
    <link rel="preload" href="{{ $kb->publicLogoPath() }}">
    @endif

    @vite('resources/css/app.css')
</head>
<body class="bg-{{ $kb->theme_color }}-100">

    <div class="container mx-auto max-w-screen-xl">
        <div class="flex flex-col items-center py-16 gap-y-8">

            @if($kb->logo !== null)
            <img src="{{ $kb->publicLogoPath() }}" width="128" />
            @endif

            <p class="text-6xl font-bold">
                {{ $kb->name }}
            </p>

            <p class="text-lg font-medium">
                Welcome to this <strong>Knowledge Base</strong> (KB)
            </p>

            <div class="relative w-[512px]">
                <label for="Search" class="sr-only">
                    Search
                </label>

                <input type="text" id="Search" placeholder="Search for..." class="w-full rounded-md border-gray-200 py-2.5 pe-10 shadow-sm sm:text-sm" />

                <span class="absolute inset-y-0 end-0 grid w-10 place-content-center">
                    <button type="button" class="text-gray-600 hover:text-gray-700">
                        <span class="sr-only">Search</span>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </button>
                </span>
            </div>

            <p class="text-2xl font-medium mt-12">
                Featured Articles
            </p>

            <div class="grid grid-cols-4 gap-8">
            @forelse($kb->articles as $article)
                <a
                    class="bg-white border border-slate-200 rounded-lg py-6 px-16 drop-shadow transition-transform hover:-translate-y-2"
                    href={{ route('kb.preview.article', [ 'slug' => $kb->slug, 'article' => $article->slug, ]) }}
                >
                    {{ $article->title }}
                </a>
            @empty

            @endforelse
            </div>

        </div>
    </div>

</body>
</html>
