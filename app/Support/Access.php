<?php

namespace App\Support;

/**
 * Oturumdaki kullanıcıya göre yetki kontrolleri.
 */
class Access
{
    public static function isAdmin(): bool
    {
        return session('blog_auth') && session('blog_role') === 'admin';
    }

    public static function user(): ?string
    {
        return session('blog_user');
    }

    /** Yönetici her yazıyı, normal kullanıcı yalnızca kendi yazısını yönetebilir. */
    public static function canManage(array $post): bool
    {
        if (!session('blog_auth')) {
            return false;
        }
        if (self::isAdmin()) {
            return true;
        }
        $author = $post['author'] ?? config('blog.username');
        return strcasecmp((string) $author, (string) self::user()) === 0;
    }
}
