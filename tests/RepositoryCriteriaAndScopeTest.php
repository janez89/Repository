<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 26.
 * Time: 6:12
 */

class RepositoryCriteriaAndScopeTest extends RelationalTestCase
{
    public function testCriteriaAndScope()
    {
        $authorRepository = new AuthorRepository();
        $authorRepository->criteria([WithPostsCriteria::class])->scope(function ($query) {
            $query->where('id', '>', 1);
        });

        $authors = $authorRepository->all();

        $this->assertTrue(count($authors) == 2);
        $this->assertTrue(isset($authors[0]));
        $this->assertTrue(isset($authors[0]['posts']));
    }
}