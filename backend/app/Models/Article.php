<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Content\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * Database table constants
     */
    final public const TABLE = 'articles';
    final public const ID = 'id';
    final public const USER_ID = 'user_id';
    final public const CATEGORY_ID = 'category_id';
    final public const TITLE = 'title';
    final public const SLUG = 'slug';
    final public const THUMBNAIL = 'thumbnail';
    final public const ABSTRACT = 'abstract';
    final public const HTML = 'html';
    final public const MARKDOWN = 'markdown';
    final public const STATUS = 'status';
    final public const CREATED_AT = 'created_at';
    final public const UPDATED_AT = 'updated_at';
    final public const DELETED_AT = 'deleted_at';

    /**
     * The table associated with the model.
     */
    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::USER_ID,
        self::CATEGORY_ID,
        self::TITLE,
        self::SLUG,
        self::THUMBNAIL,
        self::ABSTRACT,
        self::HTML,
        self::MARKDOWN,
        self::STATUS,
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        self::STATUS => ArticleStatus::class,
        self::CREATED_AT => 'datetime',
        self::UPDATED_AT => 'datetime',
        self::DELETED_AT => 'datetime',
    ];

    /**
     * Get the user that owns the article.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }

    /**
     * Get the category that owns the article.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, self::CATEGORY_ID);
    }

    /**
     * The tags that belong to the article.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished($query)
    {
        return $query->where(self::STATUS, ArticleStatus::PUBLISHED);
    }

    /**
     * Scope a query to only include draft articles.
     */
    public function scopeDraft($query)
    {
        return $query->where(self::STATUS, ArticleStatus::DRAFT);
    }

    /**
     * Scope a query to only include archived articles.
     */
    public function scopeArchived($query)
    {
        return $query->where(self::STATUS, ArticleStatus::ARCHIVED);
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            self::TITLE => $this->{self::TITLE},
            self::ABSTRACT => $this->{self::ABSTRACT},
            self::HTML => $this->{self::HTML},
            self::MARKDOWN => $this->{self::MARKDOWN},
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->status === ArticleStatus::PUBLISHED;
    }

    /**
     * Get the name of the index associated with the model.
     */
    public function searchableAs(): string
    {
        return 'articles_index';
    }

    /**
     * Get the queue connection that should be used when syncing.
     */
    public function syncsWithSearchUsingQueue(): string
    {
        return 'redis';
    }
}