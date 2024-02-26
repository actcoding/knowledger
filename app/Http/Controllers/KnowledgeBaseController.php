<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Documentation;
use App\Models\KBArticle;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use function App\tailwindColor;

class KnowledgeBaseController extends Controller
{
    public function home(Request $request, string $slug): Response|View|Factory
    {
        $kb = $this->findKBBySlug($slug, true);
        if ($kb === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        // TODO: Guest authentication
        if ($kb->password !== null)
        {
            return response()->view('error.public.403', [], 403);
        }

        if (app()->isLocal()) {
            \Barryvdh\Debugbar\Facades\Debugbar::disable();
        }

        return view('knowledge-base.preview', [
            'kb' => $kb,
            'articles' => $kb->articles()->where('status', '=', 'active')->get(),
            'public' => true,
        ]);
    }

    public function article(Request $request, string $slug, string $articleSlug): Response|View|Factory
    {
        $kb = $this->findKBBySlug($slug, true);
        if ($kb === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        $article = $this->findArticleBySlug($kb, $articleSlug);
        if ($article === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        return view('knowledge-base.preview-article', [
            'kb' => $kb,
            'article' => $article,
            'public' => true,
        ]);
    }

    public function manifest(Request $request, string $slug): Response|ResponseFactory|JsonResponse
    {
        $kb = $this->findKBBySlug($slug, true);
        if ($kb === null)
        {
            return response(null, 404);
        }

        $icons = [];
        try {
            $image = public_path('storage/' . $kb->logo);
            list($width, $height) = getimagesize($image);

            $icons[] = [
                'type' => 'image/png',
                'src' => $kb->publicLogoPath(),
                'sizes' => $width . 'x' . $height,
                'purpose' => 'any',
            ];
        } catch (\Throwable $th) {
            // ignore
        }

        return response()->json([
            'id' => str($kb->id),
            'name' => $kb->name,
            'start_url' => $kb->route(true),
            'display' => 'fullscreen',
            'lang' => 'en',
            'orientation' => 'landscape',
            'icons' => $icons,
            'theme_color' => match ($kb->theme_color->value) {
                'slate' => '#cbd5e1',
                'gray' => '#d1d5db',
                'zinc' => '#d4d4d8',
                'neutral' => '#d4d4d4',
                'stone' => '#d6d3d1',
                'red' => '#fca5a5',
                'orange' => '#fdba74',
                'amber' => '#fcd34d',
                'yellow' => '#fde047',
                'lime' => '#bef264',
                'green' => '#86efac',
                'emerald' => '#6ee7b7',
                'teal' => '#5eead4',
                'cyan' => '#67e8f9',
                'sky' => '#7dd3fc',
                'blue' => '#93c5fd',
                'indigo' => '#a5b4fc',
                'violet' => '#c4b5fd',
                'purple' => '#d8b4fe',
                'fuchsia' => '#f0abfc',
                'pink' => '#f9a8d4',
                'rose' => '#fda4af',
                default => ''
            },
            'background_color' => match ($kb->theme_color->value) {
                'slate' => '#f1f5f9',
                'gray' => '#f3f4f6',
                'zinc' => '#f4f4f5',
                'neutral' => '#f5f5f5',
                'stone' => '#f5f5f4',
                'red' => '#fee2e2',
                'orange' => '#ffedd5',
                'amber' => '#fef3c7',
                'yellow' => '#fef9c3',
                'lime' => '#ecfccb',
                'green' => '#dcfce7',
                'emerald' => '#d1fae5',
                'teal' => '#ccfbf1',
                'cyan' => '#cffafe',
                'sky' => '#e0f2fe',
                'blue' => '#dbeafe',
                'indigo' => '#e0e7ff',
                'violet' => '#ede9fe',
                'purple' => '#f3e8ff',
                'fuchsia' => '#fae8ff',
                'pink' => '#fce7f3',
                'rose' => '#ffe4e6',
                default => ''
            },
            // 'protocol_handlers' => [
            //     [
            //         'protocol' => 'web+knowledger',
            //         'url' => '/article?kb=%s&article=%s',
            //     ],
            // ],
        ]);
    }

    public function svg(Request $request, string $slug, string $name)
    {
        $kb = $this->findKBBySlug($slug, true);
        if ($kb === null)
        {
            return response(null, 404);
        }

        $replacements = [
            '#6c63ff' => tailwindColor($kb->theme_color, 400),
            '#e6e6e6' => tailwindColor($kb->theme_color, 700),
            '#f2f2f2' => tailwindColor($kb->theme_color, 300),
        ];

        $path = "img/$name.svg";
        $fullPath = resource_path($path);
        $slug = str($fullPath)->slug();

        $contents = '';
        if (Cache::has($slug) && ! app()->isLocal())
        {
            $contents = Cache::get($slug);
        }
        else {
            $contents = file_get_contents($fullPath);
            if ($contents === false)
            {
                throw new Exception("Failed to read file at $path !");
            }

            $contents = str($contents);

            foreach ($replacements as $key => $value) {
                $contents = $contents->replace($key, $value);
            }

            Cache::put($slug, $contents, 3600);
        }

        return response($contents->toString(), headers: [ 'Content-Type' => 'image/svg+xml' ]);
    }


    public function preview(Request $request, string $slug): Response|View|Factory
    {
        $kb = $this->findKBBySlug($slug);
        if ($kb === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        return view('knowledge-base.preview', [
            'kb' => $kb,
            'articles' => $kb->articles,
            'public' => false,
        ]);
    }

    public function previewArticle(Request $request, string $slug, string $articleSlug): Response|View|Factory
    {
        $kb = $this->findKBBySlug($slug);
        if ($kb === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        $article = $this->findArticleBySlug($kb, $articleSlug);
        if ($article === null)
        {
            return response()->view('error.public.404', [], 404);
        }

        return view('knowledge-base.preview-article', [
            'kb' => $kb,
            'article' => $article,
            'public' => false,
        ]);
    }


    private function findKBBySlug(string $slug, bool $public = false): ?Documentation
    {
        return Documentation::query()
            ->where('slug', '=', $slug)
            ->where(function (Builder $query) use ($public) {
                if ($public) {
                    $query->whereNull('deleted_at')->where('status', '=', 'active');
                }
            })
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
