<?php

namespace App\Services;


class PostStore
{
    private string $path;

    public function __construct()
    {
        $this->path = storage_path('app/posts.json');
    }

    public function all(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->path), true);
        $posts = is_array($data) ? $data : [];
        usort($posts, fn ($a, $b) => strcmp($b['created_at'], $a['created_at']));
        return $posts;
    }

    public function find(int $id): ?array
    {
        foreach ($this->all() as $post) {
            if ($post['id'] === $id) {
                return $post;
            }
        }
        return null;
    }

    public function create(array $data, string $author): array
    {
        $posts = $this->all();
        $nextId = $posts ? max(array_column($posts, 'id')) + 1 : 1;
        $post = [
            'id'         => $nextId,
            'title'      => $data['title'],
            'body'       => $data['body'],
            'author'     => $author,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];
        $posts[] = $post;
        $this->save($posts);
        return $post;
    }

    public function update(int $id, array $data): bool
    {
        $posts = $this->all();
        foreach ($posts as &$post) {
            if ($post['id'] === $id) {
                $post['title'] = $data['title'];
                $post['body'] = $data['body'];
                $post['updated_at'] = now()->format('Y-m-d H:i:s');
                $this->save($posts);
                return true;
            }
        }
        return false;
    }

    public function delete(int $id): void
    {
        $posts = array_values(array_filter($this->all(), fn ($p) => $p['id'] !== $id));
        $this->save($posts);
    }

    private function save(array $posts): void
    {
        file_put_contents(
            $this->path,
            json_encode(array_values($posts), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
    }
}
