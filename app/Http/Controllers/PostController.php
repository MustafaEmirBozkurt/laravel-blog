<?php

namespace App\Http\Controllers;

use App\Services\PostStore;
use App\Support\Access;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(private PostStore $store)
    {
    }

    public function index()
    {
        return view('posts.index', ['posts' => $this->store->all()]);
    }

    public function show(int $id)
    {
        $post = $this->store->find($id) ?? abort(404);
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.form', ['post' => null]);
    }

    public function store(Request $request)
    {
        $post = $this->store->create($this->validated($request), Access::user());

        return redirect()->route('posts.show', $post['id'])->with('success', 'Yazı eklendi.');
    }

    public function edit(int $id)
    {
        $post = $this->ownedPost($id);
        return view('posts.form', compact('post'));
    }

    public function update(Request $request, int $id)
    {
        $this->ownedPost($id);
        $this->store->update($id, $this->validated($request));

        return redirect()->route('posts.show', $id)->with('success', 'Yazı güncellendi.');
    }

    public function destroy(int $id)
    {
        $this->ownedPost($id);
        $this->store->delete($id);

        return redirect()->route('posts.index')->with('success', 'Yazı silindi.');
    }

    private function ownedPost(int $id): array
    {
        $post = $this->store->find($id) ?? abort(404);
        abort_unless(Access::canManage($post), 403, 'Bu yazıyı değiştirme yetkiniz yok.');
        return $post;
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:150',
            'body'  => 'required|string',
        ], [
            'title.required' => 'Başlık boş bırakılamaz.',
            'body.required'  => 'İçerik boş bırakılamaz.',
        ]);
    }
}
