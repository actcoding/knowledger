<div class="relative w-[512px]">

    <div class="relative">
        <label for="Search" class="sr-only">
            Search
        </label>

        <input
            type="text"
            id="Search"
            placeholder="Search for..."
            class="w-full rounded-md border-gray-200 py-2.5 pe-10 shadow-sm sm:text-sm"
            autocomplete="off"
            wire:model.live.debounce.150ms="query"
        />

        <span class="absolute inset-y-0 end-0 grid w-10 place-content-center">
            <button type="button" class="text-gray-600 hover:text-gray-700">
                <span class="sr-only">Search</span>

                <div wire:loading class="animate-spin">
                    @svg('heroicon-o-arrow-path', 'w-4 h-4')
                </div>
                <div wire:loading.remove>
                    @svg('heroicon-o-magnifying-glass', 'w-4 h-4')
                </div>
            </button>
        </span>
    </div>

    @if ($results != null)
    <div class="mt-2 w-full rounded-md bg-white border-gray-200 p-2.5 absolute drop-shadow-lg z-20 max-h-64 overflow-x-hidden overflow-y-auto">
        @if (count($results) > 0)
        @foreach ($results as $article)
        <a href="{{ $article->routePreview() }}" class="p-2.5 rounded-md hover:bg-slate-200 transition-colors grid grid-cols-search grid-rows-2 gap-x-3 items-center">
            <div class="row-span-2">
                <x-article-icon :article="$article" small />
            </div>
            <span>{{ $article->title }}</span>
            <span class="text-sm text-slate-500">{{ str($article->subtitle)->limit(50) }}</span>
        </a>
        @endforeach
        @else
        <p class="text-center text-slate-500">
            Nothing found...
        </p>
        @endif
    </div>
    @endif

</div>
