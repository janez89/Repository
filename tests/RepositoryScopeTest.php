<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 24.
 * Time: 4:56
 */

class RepositoryScopeTest extends RelationalTestCase
{

    public function testAddScopeAndSearch()
    {
        $authorId = 1;
        $postRepo = new PostRepository();
        $posts = $postRepo->scope(function ($query) use ($authorId) {
            $query->where('author_id', '=', $authorId);
        })->all();

        $this->assertTrue(count($posts) == $this->postByAuthor);
        $this->assertTrue($posts[0]->author_id == $authorId);
    }

    public function testAddScopeAndSearchByRelation()
    {
        $authorRepo = new AuthorRepository();
        $authors = $authorRepo->scope(function ($query) {
            $query->with('posts');
        })->all();

        $this->assertTrue(count($authors) == count($this->authors));
        $this->assertArrayHasKey('posts', $authors[0]);
        $this->assertTrue(count($authors[0]['posts']) == 3);
    }

    public function testSetAndClearScope()
    {
        $authorRepo = new AuthorRepository();
        $count = $authorRepo->scope(function ($query) {
            $query->where('id', '=', 1);
        })->reset()->count();

        $this->assertTrue($count == count($this->authors));
    }

}