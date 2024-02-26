<x-layout.minimal>


<x-slot:title>{{ $article->title }} | {{ $kb->name }}</x-slot:title>

<x-slot:head>
    @if ($public)
    <link rel="manifest" href="{{ route('kb.manifest', [ 'slug' => $kb->slug ]) }}">
    @endif

    @if($kb->logo !== null)
    <link rel="apple-touch-icon" href="{{ $kb->publicLogoPath() }}" as="image">
    <link rel="shortcut icon" href="{{ $kb->publicLogoPath() }}" as="image">
    <link rel="preload" href="{{ $kb->publicLogoPath() }}" as="image">
    @endif

    @if($article->header_image !== null)
    <link rel="preload" href="{{ $article->publicHeaderImagePath() }}" as="image">
    @endif

    @vite('resources/js/preview-toc.ts')
</x-slot:head>

<x-slot:bodyClass>bg-{{ $kb->theme_color }}-100</x-slot:bodyClass>

<x-slot:body>
    @if (!$public)
    <x-ribbon.preview />
    @endif

    <nav class="w-screen h-20 {{ $article->header_image != null ? 'drop-shadow-2xl' : 'drop-shadow' }} z-20 bg-{{ $kb->theme_color }}-300">
        <div class="container mx-auto h-full gap-x-4 flex flex-flox justify-between items-center">
            <a href="{{ $kb->route($public) }}" class="text-2xl font-bold flex flex-row items-center gap-x-4">
                @if($kb->logo !== null)
                <img src="{{ $kb->publicLogoPath() }}" width="48" />
                @endif
                <span>
                    {{ $kb->name }}
                </span>
            </a>

            @livewire(KBSearchBar::class, ['kb' => $kb, 'public' => $public])
        </div>
    </nav>

    @if($article->header_image != null)
    <img src="{{ $article->publicHeaderImagePath() }}" class="w-screen h-[384px] object-cover object-center" />
    @endif

    {{-- Header --}}
    <div class="container mx-auto mt-16">
        <a
            class="
                inline-flex items-center gap-x-3
                px-6 py-3 text-white
                rounded border border-{{ $kb->theme_color }}-600 bg-{{ $kb->theme_color }}-600
                transition-all
                hover:bg-transparent hover:text-{{ $kb->theme_color }}-600 focus:outline-none focus:ring
            "
            href="{{ $kb->route($public) }}"
        >
            @svg('heroicon-o-arrow-long-left', 'size-5')

            <span class="text-sm font-medium">
                Go back
            </span>
        </a>

        <div class="flex flex-row gap-x-8 mt-8">
            <x-article-icon :article="$article" />

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
    </div>

    <hr class="border-{{ $kb->theme_color }}-300 my-8" />

    {{-- Main --}}
    <main class="grid grid-cols-article gap-x-12 mb-48 container mx-auto">

        {{-- Table of Contents --}}
        <aside class="prose sticky top-5 bg-{{ $kb->theme_color }}-50 p-4 max-h-[90vh] overflow-y-auto" id="toc">
            <h2>Table of Contents</h2>
        </aside>

        {{-- Article --}}
        <x-markdown class="prose proxe-xl max-w-none">
            {!! $article->content !!}
        </x-markdown>
    </main>
</x-slot:body>


</x-layout.minimal>
