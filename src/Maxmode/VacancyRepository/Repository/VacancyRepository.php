<?php
namespace Maxmode\VacancyRepository\Repository;

/**
 * Vacancy repository
 *
 * Class decorates a real implementation of RepositoryInterface so that several data sources can be used
 */
class VacancyRepository implements RepositoryInterface
{
    /**
     * @var RepositoryInterface[]
     */
    protected $dataSources;

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
        foreach ($this->getDataSourcesByStrategy(static::STRATEGY_GET_SINGLE) as $dataSource) {
            $entity = $dataSource->getById($id);
            if ($entity) {
                return $entity;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function search($searchString, $facets, $orderBy, $limit, $offset)
    {
        $entities = [];
        foreach ($this->getDataSourcesByStrategy(static::STRATEGY_SEARCH) as $dataSource) {
            $entities = array_merge($entities, $dataSource->search($searchString, $facets, $orderBy, $limit, $offset));
        }

        return $entities;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($id)
    {
        foreach ($this->getDataSourcesByStrategy(static::STRATEGY_DELETE) as $dataSource) {
            $dataSource->delete($id);
        }
    }

    /**
     * @param RepositoryInterface $dataSource
     */
    public function addDataSource(RepositoryInterface $dataSource)
    {
        $this->dataSources[] = $dataSource;
    }

    /**
     * @param RepositoryInterface[] $dataSources
     */
    public function setDataSources($dataSources)
    {
        $this->dataSources = $dataSources;
    }

    /**
     * @param integer $strategy
     *
     * @return RepositoryInterface[]
     *
     * @throws \Exception in case Data sources are not configured
     */
    public function getDataSourcesByStrategy($strategy)
    {
        $dataSources = [];
        foreach ($this->dataSources as $dataSource) {
            $dataSourceStrategy = $dataSource->getStrategy();
            if ($dataSourceStrategy & $strategy) {
                $dataSources[] = $dataSource;
            }
        }

        return $dataSources;
    }
}
