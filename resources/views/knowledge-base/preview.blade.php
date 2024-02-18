<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <title>📚 {{ $kb->name }}</title>

    {{-- <link rel="manifest" href="/manifest.json"> --}}

    @if($kb->logo !== null)
    <link rel="apple-touch-icon" href="{{ $kb->publicLogoPath() }}" as="image">
    <link rel="shortcut icon" href="{{ $kb->publicLogoPath() }}" as="image">
    <link rel="preload" href="{{ $kb->publicLogoPath() }}" as="image">
    @endif

    @vite('resources/css/app.css')
</head>
<body class="bg-{{ $kb->theme_color }}-100 select-none">

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

                        @svg('heroicon-o-magnifying-glass', 'w-4 h-4')
                    </button>
                </span>
            </div>

            <p class="text-2xl font-medium mt-12">
                Featured Articles
            </p>

            <div class="grid grid-cols-4 gap-8">
            @forelse($kb->articles as $article)
                <a
                    class="bg-white border border-slate-200 rounded-lg py-6 px-16 drop-shadow transition-transform hover:-translate-y-2 flex flex-col gap-y-4 items-center"
                    href={{ route('kb.preview.article', [ 'slug' => $kb->slug, 'article' => $article->slug, ]) }}
                >
                    <x-article-icon :$article small />
                    <span class="text-xl text-center">
                        {{ $article->title }}
                    </span>
                </a>
            @empty

            @endforelse
            </div>

        </div>
    </div>

</body>
</html>
