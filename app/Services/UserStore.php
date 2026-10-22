<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class UserStore
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/users.json');
    }

    public function all(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->path), true);
        return is_array($data) ? $data : [];
    }

    public function findByUsername(string $username): ?array
    {
        foreach ($this->all() as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return $user;
            }
        }
        return null;
    }

    public function create(string $username, string $password): array
    {
        $users = $this->all();
        $user = [
            'username'   => $username,
            'password'   => Hash::make($password),
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];
        $users[] = $user;
        file_put_contents(
            $this->path,
            json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
        return $user;
    }
}
