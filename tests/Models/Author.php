<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 24.
 * Time: 5:10
 */
class Author extends Illuminate\Database\Eloquent\Model
{
    protected $table = 'authors';

    protected $guarded = ['id'];
    protected $casts = [
        'id' => 'int'
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'author_id');
    }
}