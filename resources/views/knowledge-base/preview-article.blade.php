<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

    <title>📚 {{ $article->title }} | {{ $kb->name }}</title>

    {{-- <link rel="manifest" href="/manifest.json"> --}}

    @if($kb->logo !== null)
    <link rel="apple-touch-icon" href="{{ $kb->publicLogoPath() }}">
    <link rel="shortcut icon" href="{{ $kb->publicLogoPath() }}">
    <link rel="preload" href="{{ $kb->publicLogoPath() }}" as="image">
    @endif

    @if($article->header_image !== null)
    <link rel="preload" href="{{ $article->publicHeaderImagePath() }}" as="image">
    @endif

    @vite('resources/css/app.css')
</head>
<body class="bg-{{ $kb->theme_color }}-100">

    @if($article->header_image !== null)
    <img src="{{ $article->publicHeaderImagePath() }}" class="w-screen h-[384px] object-cover object-center" />
    @endif

    <div class="container mx-auto max-w-screen-md my-24">

        <a
            class="
                inline-flex items-center gap-x-3
                px-6 py-3 text-white
                rounded border border-{{ $kb->theme_color }}-600 bg-{{ $kb->theme_color }}-600
                transition-all
                hover:bg-transparent hover:text-{{ $kb->theme_color }}-600 focus:outline-none focus:ring
            "
            href="{{ route('kb.preview', ['slug' => $kb->slug]) }}"
        >
            @svg('heroicon-o-arrow-long-left', 'size-5')

            <span class="text-sm font-medium">
                Go back
            </span>
        </a>

        <div class="flex flex-row gap-x-8 mt-8">
            @if ($article->icon_mode !== null)
                @switch($article->icon_mode)
                @case('heroicon')
                    @svg($article->icon, 'w-16 h-16')
                    @break
                @case('emoji')
                    <p class="text-6xl">{{ $article->icon }}</p>
                    @break
                @case('custom')
                    <img src="{{ asset('storage/' . $article->icon) }}" class="w-8 h-8" />
                    @break
                @endswitch
            @endif

            <div>
                <p class="text-6xl font-bold">
                    {{ $article->title }}
                </p>

                @if($article->subtitle !== null)
                    <p class="text-2xl font-medium mt-4">
                        {{ $article->subtitle }}
                    </p>
                @endif
            </div>
        </div>

        <hr class="border-{{ $kb->theme_color }}-300 my-8" />

        <x-markdown class="prose proxe-xl max-w-none">
            {!! $article->content !!}
        </x-markdown>

    </div>

</body>
</html>
