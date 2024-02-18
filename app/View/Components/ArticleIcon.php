<?php

namespace App\View\Components;

use App\Models\KBArticle;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ArticleIcon extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public KBArticle $article,
        public bool $small = false,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.article-icon');
    }

    public function getSize(): string
    {
        return match ($this->article->icon_mode) {
            'heroicon' => $this->small ? 'size-4' : 'size-8',
            'emoji' => $this->small ? 'text-3xl' : 'text-6xl',
            'custom' => $this->small ? 'size-4' : 'size-8',
        };
    }
}
