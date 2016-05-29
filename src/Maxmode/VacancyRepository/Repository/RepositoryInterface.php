<?php
namespace Maxmode\VacancyRepository\Repository;

use Maxmode\VacancyRepository\Model\Vacancy;

/**
 * Interface RepositoryInterface
 */
interface RepositoryInterface
{
    const STRATEGY_GET_SINGLE = 1;
    const STRATEGY_SEARCH = 2;
    const STRATEGY_DELETE = 4;

    /**
     * Get strategy of current Data source
     *
     * @return int
     */
    public function getStrategy();

    /**
     * Load vacancy by ID
     *
     * @param string $id
     *
     * @return null|Vacancy
     */
    public function getById($id);

    /**
     * Search for vacancies
     *
     * @param string  $searchString
     * @param array   $facets
     * @param array   $orderBy
     * @param integer $limit
     * @param integer $offset
     * @return array
     * @throws \Exception
     */
    public function search($searchString, $facets, $orderBy, $limit, $offset);

    /**
     * Delete vacancy
     *
     * @param string $id
     */
    public function delete($id);
}
