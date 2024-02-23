<?php

namespace App\Livewire;

use App\Models\Documentation;
use App\Models\KBArticle;
use Illuminate\Support\Str;
use Livewire\Component;

class KBSearchBar extends Component
{
    public Documentation $kb;
    public bool $public;

    public string $query = '';

    public function render()
    {
        $results = null;

        if (Str::length($this->query) > 0)
        {
            $query = KBArticle::search($this->query)
                ->where('documentation_id', $this->kb->id);

            if ($this->public) {
                $query = $query->where('status', 'active');
            }

            $results = $query->get();
        }

        return view('livewire.kb-search-bar', [
            'results' => $results,
        ]);
    }

    public function open(KBArticle $article)
    {
        return $this->redirect($article->route($this->public));
    }
}
