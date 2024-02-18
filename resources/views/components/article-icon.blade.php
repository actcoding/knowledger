@if ($article->icon_mode !== null)
    @switch($article->icon_mode)
    @case('heroicon')
        @svg($article->icon, '{{ $getSize() }}')
        @break
    @case('emoji')
        <span class="{{ $getSize() }}">{{ $article->icon }}</span>
        @break
    @case('custom')
        <img src="{{ asset('storage/' . $article->icon) }}" class="{{ $getSize() }}" />
        @break
    @endswitch
@endif
