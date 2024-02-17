<?php

namespace App\Http\Controllers;

use App\Models\Documentation;
use App\Models\KBArticle;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class KnowledgeBaseController extends Controller
{
    public function home(Request $request, string $slug): Response|ResponseFactory
    {
        $kb = $this->findKBBySlug($slug);
        if ($kb === null)
        {
            return response(null, 404);
        }

        if ($kb->password !== null)
        {
            return response(null, 403);
        }

        return response($kb->name);
    }

    public function preview(Request $request, string $slug): Response|View|Factory
    {
        $kb = $this->findKBBySlug($slug);
        if ($kb === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        // TODO: Guest authentication
        if ($kb->password !== null)
        {
            return response()->view('error.public.403', [], 403);
        }

        return view('knowledge-base.preview', [
            'kb' => $kb,
        ]);
    }

    public function previewArticle(Request $request, string $slug, string $articleSlug): Response|View|Factory
    {
        $kb = $this->findKBBySlug($slug);
        if ($kb === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        // TODO: Guest authentication
        if ($kb->password !== null)
        {
            return response()->view('error.public.403', [], 403);
        }

        $article = $this->findArticleBySlug($kb, $articleSlug);
        if ($article === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        return view('knowledge-base.preview-article', [
            'kb' => $kb,
            'article' => $article,
        ]);
    }

    private function manifest(Documentation $kb): string
    {
        return json_encode([
            "name" => $kb->name,
            "start_url" => $kb->publicUrl(),
            "theme_color" => "#ecfccb",
            "display" => "minimal-ui",
            "lang" => "en",
            "orientation" => "landscape",
            "icons" => [
                [
                    "type" => "image/png",
                    "src" => $kb->publicLogoPath(),
                    "sizes" => "128x128"
                ]
            ]
        ]);
    }

    private function findKBBySlug(string $slug): ?Documentation
    {
        return Documentation::query()
            ->where('slug', '=', $slug)
            ->first();
    }

    private function findArticleBySlug(Documentation $kb, string $slug): ?KBArticle
    {
        return KBArticle::query()
            ->where('documentation_id', '=', $kb->id)
            ->where('slug', '=', $slug)
            ->first();
    }
}
