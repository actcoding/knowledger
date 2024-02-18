<?php

namespace App\Livewire;

use App\Models\Documentation;
use App\Models\KBArticle;
use Illuminate\Support\Str;
use Livewire\Component;

class KBSearchBar extends Component
{
    public Documentation $kb;
    public string $query = '';

    public function render()
    {
        $results = null;

        if (Str::length($this->query) > 0)
        {
            $results = KBArticle::search($this->query)
                ->where('documentation_id', $this->kb->id)
                ->get();
        }

        return view('livewire.kb-search-bar', [
            'results' => $results,
        ]);
    }

    public function open(KBArticle $article)
    {
        return $this->redirect($article->routePreview());
    }
}
