<x-layout.minimal>


<x-slot:title>{{ $kb->name }}</x-slot:title>

<x-slot:head>
    @if ($public)
    <link rel="manifest" href="{{ route('kb.manifest', [ 'slug' => $kb->slug ]) }}">
    @endif

    @if($kb->logo !== null)
    <link rel="apple-touch-icon" href="{{ $kb->publicLogoPath() }}" as="image">
    <link rel="shortcut icon" href="{{ $kb->publicLogoPath() }}" as="image">
    <link rel="preload" href="{{ $kb->publicLogoPath() }}" as="image">
@endif
</x-slot:head>

<x-slot:bodyClass>bg-{{ $kb->theme_color }}-100</x-slot:bodyClass>

<x-slot:body>
    @if (!$public)
    <x-ribbon.preview />
    @endif

    <div class="container mx-auto max-w-screen-xl h-screen">
        <div class="flex flex-col items-center py-8 gap-y-8">

            @if($kb->logo !== null)
            <img src="{{ $kb->publicLogoPath() }}" class="h-48" />
            @endif

            <p class="text-6xl font-bold">
                {{ $kb->name }}
            </p>

            @if ($articles->count() > 0)
                <p class="text-lg font-medium">
                    Welcome to this <strong>Knowledge Base</strong> (KB)
                </p>

                @livewire(KBSearchBar::class, ['kb' => $kb, 'public' => $public])

                <p class="text-2xl font-medium mt-12">
                    Featured Articles
                </p>
            @else
                <img
                    src="{{ route('kb.svg', [ 'slug' => $kb->slug, 'name' => 'undraw_empty_re_opql' ]) }}"
                    width="384"
                    class="mt-8"
                />
            @endif

            <div class="grid grid-cols-4 gap-8">
            @forelse($articles as $article)
                <a
                    class="bg-white border border-slate-200 rounded-lg py-6 px-16 drop-shadow transition-transform hover:-translate-y-2 flex flex-col gap-y-4 items-center"
                    href={{ $article->route($public) }}
                >
                    <x-article-icon :$article small />
                    <span class="text-xl text-center">
                        {{ $article->title }}
                    </span>
                </a>
            @empty
                <p class="text-center font-medium text-lg col-span-full">
                    This KB does not have any articles yet …
                </p>
                @endforelse
            </div>

        </div>
    </div>
</x-slot:body>


</x-layout.minimal>
