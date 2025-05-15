<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'author'];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
