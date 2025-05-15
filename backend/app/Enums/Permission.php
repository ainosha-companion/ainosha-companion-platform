<?php

declare(strict_types=1);

namespace App\Enums;

enum Permission: string
{
    // Article permissions
    case ARTICLE_VIEW = 'article.view';
    case ARTICLE_CREATE = 'article.create';
    case ARTICLE_UPDATE = 'article.update';
    case ARTICLE_DELETE = 'article.delete';
    case ARTICLE_PUBLISH = 'article.publish';
    case ARTICLE_FEATURE = 'article.feature';

    // Category permissions
    case CATEGORY_VIEW = 'category.view';
    case CATEGORY_CREATE = 'category.create';
    case CATEGORY_UPDATE = 'category.update';
    case CATEGORY_DELETE = 'category.delete';

    // Tag permissions
    case TAG_VIEW = 'tag.view';
    case TAG_CREATE = 'tag.create';
    case TAG_UPDATE = 'tag.update';
    case TAG_DELETE = 'tag.delete';

    // User management permissions
    case USER_VIEW = 'user.view';
    case USER_CREATE = 'user.create';
    case USER_UPDATE = 'user.update';
    case USER_DELETE = 'user.delete';

    // Role management permissions
    case ROLE_VIEW = 'role.view';
    case ROLE_CREATE = 'role.create';
    case ROLE_UPDATE = 'role.update';
    case ROLE_DELETE = 'role.delete';
    case ROLE_ASSIGN = 'role.assign';

    // Permission management
    case PERMISSION_VIEW = 'permission.view';
    case PERMISSION_ASSIGN = 'permission.assign';

    // Analytics permissions
    case ANALYTICS_VIEW = 'analytics.view';
    case ANALYTICS_EXPORT = 'analytics.export';

    // Admin permissions
    case ADMIN_VIEW = 'admin.view';
    case ADMIN_CREATE = 'admin.create';
    case ADMIN_UPDATE = 'admin.update';
    case ADMIN_DELETE = 'admin.delete';

    /**
     * Get all permissions in a specific category
     *
     * @param string $category The category prefix (e.g., 'article', 'user')
     * @return array<string> Array of permission values in that category
     */
    public static function getByCategory(string $category): array
    {
        return array_filter(
            array_map(
                fn($case) => $case->value,
                self::cases()
            ),
            fn($value) => str_starts_with($value, $category . '.')
        );
    }

    /**
     * Get all permission cases in a specific category
     *
     * @param string $category The category prefix (e.g., 'article', 'user')
     * @return array<self> Array of permission cases in that category
     */
    public static function getCasesByCategory(string $category): array
    {
        return array_filter(
            self::cases(),
            fn($case) => str_starts_with($case->value, $category . '.')
        );
    }
}