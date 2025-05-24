<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(protected TagService $service) {}

    public function index()
    {
        return response()->json($this->service->getAllTags());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|unique:tags',
            'desc' => 'nullable|string',
        ]);
        $tag = $this->service->createTag($validated);
        return response()->json($tag, 201);
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|unique:tags,title,' . $tag->id,
            'desc' => 'nullable|string',
        ]);
        $updated = $this->service->updateTag($tag, $validated);
        return response()->json($updated);
    }

    public function destroy(Tag $tag)
    {
        $this->service->deleteTag($tag);
        return response()->json(null, 204);
    }

    public function attach(Request $request, Tag $tag)
    {
        $request->validate(['article_id' => 'required|exists:articles,id']);
        $this->service->attachToArticle($tag, $request->article_id);
        return response()->json(['message' => 'Tag attached']);
    }

    public function detach(Request $request, Tag $tag)
    {
        $request->validate(['article_id' => 'required|exists:articles,id']);
        $this->service->detachFromArticle($tag, $request->article_id);
        return response()->json(['message' => 'Tag detached']);
    }
}
