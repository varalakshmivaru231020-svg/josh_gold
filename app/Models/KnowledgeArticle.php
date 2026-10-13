<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// A guide on the public Knowledge page (/knowledge)
class KnowledgeArticle extends Model
{
    protected $fillable = [
        'tag',
        'title',
        'intro',
        'points',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    // The bullet points, one per stored line, blank lines skipped
    public function pointList(): array
    {
        return self::splitLines((string) $this->points);
    }

    // Normalise pasted text to one trimmed point per line ("\n" line endings, no blank lines)
    public static function normalisePoints(?string $text): ?string
    {
        $lines = self::splitLines((string) $text);

        return $lines ? implode("\n", $lines) : null;
    }

    private static function splitLines(string $text): array
    {
        return array_values(array_filter(
            array_map('trim', preg_split('/\R/u', $text) ?: []),
            fn ($line) => $line !== ''
        ));
    }
}
