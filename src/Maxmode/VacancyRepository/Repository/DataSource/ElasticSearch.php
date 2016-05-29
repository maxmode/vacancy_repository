<?php
namespace Maxmode\VacancyRepository\Repository\DataSource;

use Maxmode\VacancyRepository\Repository\RepositoryInterface;

/**
 * Data source to get vacancies from some external API
 */
class ElasticSearch implements RepositoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function getStrategy()
    {
        return static::STRATEGY_GET_SINGLE | static::STRATEGY_DELETE | static::STRATEGY_SEARCH;
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
        //todo: implement
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        //todo: implement
    }
}
