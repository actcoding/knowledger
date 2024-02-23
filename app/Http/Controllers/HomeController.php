<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        return redirect()->route('login');
    }

    private function collectDomains(): Collection
    {
        return collect();
        /* return Documentation::query()
            ->select('domain')
            ->distinct()
            ->get()
            ->map(fn (Documentation $obj) => $obj->domain); */
    }
}
