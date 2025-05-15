<?php

namespace app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['article_id', 'author', 'content'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
