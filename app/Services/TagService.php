<?php

namespace App\Services;

use App\Models\Tag;
use App\Repository\TagRepository;

class TagService
{
    public function __construct(protected TagRepository $repo) {}

    public function createTag(array $data): Tag
    {
        return $this->repo->create($data);
    }

    public function updateTag(Tag $tag, array $data): Tag
    {
        return $this->repo->update($tag, $data);
    }

    public function deleteTag(Tag $tag): void
    {
        $this->repo->delete($tag);
    }

    public function getAllTags()
    {
        return $this->repo->all();
    }

    public function attachToArticle(Tag $tag, int $articleId)
    {
        $tag->articles()->attach($articleId);
    }

    public function detachFromArticle(Tag $tag, int $articleId)
    {
        $tag->articles()->detach($articleId);
    }
}
