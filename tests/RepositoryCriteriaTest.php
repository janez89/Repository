<?php

/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 24.
 * Time: 4:56
 */



class RepositoryCriteriaTest extends RelationalTestCase
{

    public function testSimpleCriteria()
    {
        $authorRepository = new AuthorRepository();
        $authors = $authorRepository->criteria(WithPostsCriteria::class)->all();

        $this->assertTrue(count($authors) == count($this->authors));
        $this->assertTrue($authorRepository->hasCriteria());
        $this->assertTrue(isset($authors[0]['posts']));
    }

    public function testOwnCreatedCriteria()
    {
        $authorRepository = new AuthorRepository();
        $criteria = new WithPostsCriteria();
        $authors = $authorRepository->criteria($criteria)->all();

        $this->assertTrue(count($authors) == count($this->authors));
        $this->assertTrue(isset($authors[0]['posts']));
        $this->assertTrue($authorRepository->hasCriteria());
    }


    public function testMoreCriteria()
    {
        $authorRepository = new AuthorRepository();
        $authors = $authorRepository->criteria([
            WithPostsCriteria::class,
            OldThen2000Criteria::class,
        ])->all();

        if (!count($authors))
            return;

        $this->assertTrue(isset($authors[0]));
        $this->assertTrue((new \DateTime($authors[0]->created_at)) <= new DateTime(OldThen2000Criteria::DATE));
        $this->assertTrue(isset($authors[0]['posts']));
    }

    public function testHasCriteria()
    {
        $authorRepository = new AuthorRepository();
        $authorRepository->criteria([
            WithPostsCriteria::class,
            OldThen2000Criteria::class,
        ]);

        $this->assertTrue($authorRepository->hasCriteria());

        $authorRepository->reset();
        $this->assertFalse($authorRepository->hasCriteria());
    }

    public function testCriteriaStringParameter()
    {
        $authorRepository = new AuthorRepository();
        $authorRepository->criteria([
            'WithPostsCriteria'
        ]);


        $this->assertTrue($authorRepository->hasCriteria());
        $this->assertTrue($authorRepository->count() == count($this->authors));

        $authors = $authorRepository->all();
        $this->assertTrue(isset($authors[0]['posts']));
    }

}