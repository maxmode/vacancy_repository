Vacancy repository
==================
## Stability

[![Build Status](https://travis-ci.org/maxmode/vacancy_repository.png)](https://travis-ci.org/maxmode/vacancy_repository)

## About
This is a demo project of VacancyRepository.
The approach could be used to build real application with multiple data sources.
Each data source can implement one of following strategies:

1. Get vacancy By ID. First will be called Redis (cache), then ElasticSearch (search engine) and if the vacancy was not found in that data sources - will be called Mysql (database)
1. Search vacancies using search text, facets, sorting, pagination. Will be called only elasticSearch.
1. Delete vacancy by ID. Vacancy will be deleted in all data sources, one by one.

The repository (class Maxmode\VacancyRepository\Repository\VacancyRepository) implements Observer pattern and can work with multiple Subscribers (data sources) 
The repository is a sort of Decorator for a real data source because it implements
the same interface (Maxmode\VacancyRepository\Repository\RepositoryInterface) as all data sources,
so it's possible to build nested structure or Repositories.

## Requirements

1. PHP 5.4+
2. composer

## Installation

```composer install```

## Run tests
```bin/phpunit```

## Example of usage
```
use Maxmode\VacancyRepository\Repository\VacancyRepository;
use Maxmode\VacancyRepository\Repository\DataSource\Redis;
use Maxmode\VacancyRepository\Repository\DataSource\Mysql;
use Maxmode\VacancyRepository\Repository\DataSource\ElasticSearch;


//Configuration
$repository = new VacancyRepository();
$repository->addDataSource(new Redis());
$repository->addDataSource(new ElasticSearch());
$repository->addDataSource(new MySql());

//to get vacancy by ID
$vacancy = $repository->getById($id)

//to search
$vacancyList = $repository->search(...)

//to delete vacancy in all data sources
$repository->delete($id);
```


