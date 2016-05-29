<?php
namespace Maxmode\VacancyRepository\Repository\DataSource;

use Maxmode\VacancyRepository\Repository\RepositoryInterface;

/**
 * Data source to get vacancies from Redis
 */
class Redis implements RepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getStrategy()
    {
        return static::STRATEGY_GET_SINGLE | static::STRATEGY_DELETE;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($id)
    {
        //todo: implement
    }

    /**
     * {@inheritdoc}
     */
    public function search($searchString, $facets, $orderBy, $limit, $offset)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        //todo: implement
    }
}
