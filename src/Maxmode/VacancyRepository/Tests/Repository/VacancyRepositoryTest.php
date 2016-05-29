<?php
namespace Maxmode\VacancyRepository\Tests\Repository;

use Maxmode\VacancyRepository\Model\Vacancy;
use Maxmode\VacancyRepository\Repository\VacancyRepository;
use Maxmode\VacancyRepository\Repository\DataSource\Redis;
use Maxmode\VacancyRepository\Repository\DataSource\Mysql;
use Maxmode\VacancyRepository\Repository\DataSource\ElasticSearch;
use Maxmode\VacancyRepository\Repository\RepositoryInterface;

/**
 * Test for
 */
class VacancyRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VacancyRepository
     */
    protected $service;

    /**
     * @var Redis|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $redisMock;

    /**
     * @var Mysql|\PHPUnit_Framework_MockObject_MockObject $mySqlMock
     */
    protected $mySqlMock ;

    /**
     * @var ElasticSearch|\PHPUnit_Framework_MockObject_MockObject $apiMock
     */
    protected $elasticSearchMock;

    protected function setUp()
    {
        $this->redisMock = $this->getMockBuilder(Redis::class)->getMock();
        $this->redisMock->expects($this->any())->method('getStrategy')
            ->willReturn(RepositoryInterface::STRATEGY_GET_SINGLE | RepositoryInterface::STRATEGY_DELETE);

        $this->mySqlMock = $this->getMockBuilder(Mysql::class)->getMock();
        $this->mySqlMock->expects($this->any())->method('getStrategy')
            ->willReturn(RepositoryInterface::STRATEGY_GET_SINGLE | RepositoryInterface::STRATEGY_DELETE);

        $this->elasticSearchMock = $this->getMockBuilder(ElasticSearch::class)->getMock();
        $this->elasticSearchMock->expects($this->any())->method('getStrategy')
            ->willReturn(
                RepositoryInterface::STRATEGY_GET_SINGLE
                | RepositoryInterface::STRATEGY_DELETE
                | RepositoryInterface::STRATEGY_SEARCH
            );

        $this->service = new VacancyRepository();
        $this->service->addDataSource($this->redisMock);
        $this->service->addDataSource($this->elasticSearchMock);
        $this->service->addDataSource($this->mySqlMock);
    }

    /**
     * Test for VacancyRepository::getDataSourcesByStrategy()
     *
     * @param integer $strategy
     * @param integer $expectedCount
     *
     * @dataProvider getDataSourcesByStrategyDataSource
     */
    public function testGetDataSourcesByStrategy($strategy, $expectedCount)
    {
        $this->assertCount($expectedCount, $this->service->getDataSourcesByStrategy($strategy));
    }

    /**
     * @return array
     */
    public function getDataSourcesByStrategyDataSource()
    {
        return [
            'case get single' => [
                'strategy' => RepositoryInterface::STRATEGY_GET_SINGLE,
                'expectedDataSourcesCount' => 3,
            ],
            'case search' => [
                'strategy' => RepositoryInterface::STRATEGY_SEARCH,
                'expectedDataSourcesCount' => 1,
            ],
            'case delete' => [
                'strategy' => RepositoryInterface::STRATEGY_DELETE,
                'expectedDataSourcesCount' => 3,
            ],
        ];
    }

    /**
     * Test for VacancyRepository::getById()
     *
     * Test checks that data source will be asked one by one. In this example vacancy should be found in elasticSearch
     */
    public function testGetById()
    {
        $id = 567;
        $vacancyMock = $this->getMockBuilder(Vacancy::class)->getMock();

        $this->redisMock->expects($this->once())->method('getById')->with($id)->willReturn(null);
        $this->elasticSearchMock->expects($this->once())->method('getById')->with($id)->willReturn($vacancyMock);
        $this->mySqlMock->expects($this->never())->method('getById');

        $this->assertEquals($vacancyMock, $this->service->getById($id));
    }

    /**
     * Test for VacancyRepository::search()
     *
     * Test checks that vacancy is being searched in all data sources, desired for search
     */
    public function testSearch()
    {
        $vacancyMock = $this->getMockBuilder(Vacancy::class)->getMock();
        $searchString = 'qwerty';
        $facets = [];
        $orderBy = [];
        $limit = 10;
        $offset = 0;
        $expectedResults = [$vacancyMock];

        $this->redisMock->expects($this->never())->method('search');
        $this->elasticSearchMock->expects($this->once())->method('search')->willReturn($expectedResults);
        $this->mySqlMock->expects($this->never())->method('search');

        $this->assertEquals($expectedResults, $this->service->search($searchString, $facets, $orderBy, $limit, $offset));
    }

    /**
     * Test for VacancyRepository::delete()
     *
     * Test checks that the vacancy will be deleted from all data sources (with all who supports delete strategy)
     */
    public function testDelete()
    {
        $id = 567;

        $this->redisMock->expects($this->once())->method('delete')->with($id);
        $this->elasticSearchMock->expects($this->once())->method('delete')->with($id);
        $this->mySqlMock->expects($this->once())->method('delete')->with($id);

        $this->service->delete($id);
    }

}