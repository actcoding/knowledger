<x-layout.minimal>


<x-slot:title>{{ $kb->name }}</x-slot:title>

<x-slot:head>
    {{-- <link rel="manifest" href="/manifest.json"> --}}

    @if($kb->logo !== null)
    <link rel="apple-touch-icon" href="{{ $kb->publicLogoPath() }}" as="image">
    <link rel="shortcut icon" href="{{ $kb->publicLogoPath() }}" as="image">
    <link rel="preload" href="{{ $kb->publicLogoPath() }}" as="image">
@endif
</x-slot:head>

<x-slot:bodyClass>bg-{{ $kb->theme_color }}-100</x-slot:bodyClass>

<x-slot:body>
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

            @livewire(KBSearchBar::class, ['kb' => $kb])

            <p class="text-2xl font-medium mt-12">
                Featured Articles
            </p>

            <div class="grid grid-cols-4 gap-8">
            @forelse($kb->articles as $article)
                <a
                    class="bg-white border border-slate-200 rounded-lg py-6 px-16 drop-shadow transition-transform hover:-translate-y-2 flex flex-col gap-y-4 items-center"
                    href={{ $article->routePreview() }}
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
</x-slot:body>


</x-layout.minimal>
