<?php

namespace App\Models;

use App\Util\Colors;
use Database\Factories\KBFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Documentation extends Model
{
    use HasFactory,
        LogsActivity,
        Prunable,
        Searchable,
        SoftDeletes;

    public static function getLabel(): string
    {
        return 'Knowledge Base';
    }

    public static function getScoutOptions(): array
    {
        return [
            'filterableAttributes'=> ['name', 'slug', 'domains'],
            'sortableAttributes' => ['created_at', 'updated_at'],
        ];
    }

    protected $fillable = [
        'name', 'slug',
        'logo', 'theme_color',
        'password',
        'domains',
    ];

    protected $casts = [
        'theme_color' => Colors::class,
        'domains' => 'array',
    ];

    protected static function newFactory(): KBFactory
    {
        return KBFactory::new();
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: function (?string $value) {
                if ($value === null || str($value)->length() === 0)
                {
                    return null;
                }

                return Hash::make($value);
            }
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug']);
    }

    public function prunable(): Builder
    {
        return static::query()->whereNotNull('deleted_at');
    }

    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'name' => $this->name,
            'slug' => $this->slug,
            'domains' => $this->domains,
        ];
    }

    public function publicLogoPath(): ?string
    {
        if ($this->logo === null)
        {
            return null;
        }

        return asset('storage/' . $this->logo);
    }

    public function articles(): HasMany
    {
        // TODO: Order by numeric attribute
        return $this->hasMany(KBArticle::class)->orderBy('title', 'asc');
    }

    public function route(bool $public): string
    {
        $name = $public ? 'kb.home' : 'kb.preview';
        return route($name, [ 'slug' => $this->slug ]);
    }
}
