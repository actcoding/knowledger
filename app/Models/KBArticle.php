<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class KBArticle extends Model
{
    use HasFactory,
        LogsActivity,
        Searchable,
        SoftDeletes;

    public static function getLabel(): string
    {
        return 'Article';
    }

    public static function getScoutOptions(): array
    {
        return [
            'filterableAttributes'=> ['title', 'subtitle', 'slug', 'category', 'status', 'documentation_id'],
            'sortableAttributes' => ['created_at', 'updated_at'],
        ];
    }

    protected $table = 'kb_articles';

    protected $fillable = [
        'documentation_id',
        'title', 'subtitle', 'slug',
        'icon_mode', 'icon', 'header_image',
        'content', 'category', 'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'subtitle', 'content']);
    }

    public function toSearchableArray()
    {
        return $this->makeHidden([
            'deleted_at', 'content',
            'icon_mode', 'icon', 'header_image',
        ])->toArray();
    }

    public function knowledgeBase(): BelongsTo
    {
        return $this->belongsTo(Documentation::class, 'documentation_id');
    }

    public function publicHeaderImagePath(): ?string
    {
        if ($this->header_image === null)
        {
            return null;
        }

        return asset('storage/' . $this->header_image);
    }

    public function route(bool $public): string
    {
        $name = $public ? 'kb.article' : 'kb.preview.article';
        return route($name, [ 'slug' => $this->knowledgeBase->slug, 'article' => $this->slug, ]);
    }
}
