<?php
/**
 * Created by PhpStorm.
 * User: janez
 * Date: 2015. 09. 23.
 * Time: 23:22
 */

namespace Janez89\Repository\Traits;

use Illuminate\Support\Collection;
use Janez89\Repository\Contracts\CriteriaInterface;
use Janez89\Repository\Contracts\EloquentCriteriaInterface;
use Janez89\Repository\RepositoryException;

trait CriteriaHelper
{
    /**
     * @var Collection
     */
    protected $criteriaCollection;

    /**
     * @return Collection
     */
    protected function getCriteriaCollection()
    {
        if ($this->criteriaCollection == NULL)
            $this->criteriaCollection = new Collection();

        return $this->criteriaCollection;
    }

    /**
     * @param $criteria
     * @throws RepositoryException
     */
    protected function addCriteria($criteria)
    {
        if (!is_object($criteria))
            $criteria = $this->makeInstance($criteria);

        if (!($criteria instanceof CriteriaInterface) && !($criteria instanceof EloquentCriteriaInterface))
            throw new RepositoryException('The criteria don\'t implement Criteria Interface');

        $this->getCriteriaCollection()->push($criteria);
    }

    /**
     * @param string|array $criteria
     * @return $this
     * @throws RepositoryException
     */
    public function criteria($criteria)
    {
        if (is_array($criteria))
            foreach ($criteria as $criterion)
                $this->addCriteria($criterion);
        else
            $this->addCriteria($criteria);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCriteria()
    {
        return $this->criteriaCollection !== NULL && count($this->criteriaCollection);
    }

    /**
     * @param $query
     * @return void
     */
    protected function applyCriteria($query)
    {
        $criterias = $this->getCriteriaCollection();
        foreach ($criterias as $criteria)
            $criteria->apply($query, $this);

        return $query;
    }

    /**
     * clear criteria collection
     */
    public function clearCriteria()
    {
        $this->criteriaCollection = new Collection();
    }
}