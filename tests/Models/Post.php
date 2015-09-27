<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 24.
 * Time: 5:10
 */
class Post extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'posts';

    protected $guarded = ['id', 'author_id'];
    protected $casts = [
        'id' => 'int',
        'author_id' => 'int',
    ];

    protected $hidden = ['author_id'];
    protected $appends = ['hash'];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function getHashAttribute()
    {
        return md5(Post::class . $this->id);
    }
}