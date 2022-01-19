<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

class EntityRepository
{

  protected string $entityClassName;
  protected string $defaultSort;
  protected array $allowedIncludes = [];
  protected array $allowedFilters = [];
  protected array $allowedSorts = [];
  protected array $allowedFields = [];
  protected array $allowedScopes = [];
  protected array $allowedAppends = [];
  protected array $exactFilters = [];
  protected array $partialFilters = [];
  protected array $translatableJsonFilters = [];

  public function __construct()
  {
    $this->setAllowedFilters();
  }

  /**
   * @return array
   */
  private function setAllowedFilters()
  {
    $exactFilters = array_map(function ($item) {
      return AllowedFilter::exact($item);
    }, $this->exactFilters);

    $partialFilters = array_map(function ($item) {
      return AllowedFilter::partial($item);
    }, $this->partialFilters);

    $scopeFilters = array_map(function ($item) {
      return AllowedFilter::scope($item);
    }, $this->allowedScopes);

    $this->allowedFilters = array_merge($exactFilters, $partialFilters, $scopeFilters);
  }

  /**
   * @inheritDoc
   */
  public function getEntityClassName(): string
  {
    return $this->entityClassName;
  }

  /**
   * @inheritDoc
   */
  public function find($id)
  {
    return $this->getQueryBuilder()->find($id);
  }

  /**
   * @inheritDoc
   */
  public function findAll()
  {
    return $this->getQueryBuilder()->get();
  }

  /**
   * @inheritDoc
   */
  public function findBy(array $criteria, ?array $orderBy = [], ?int $limit = null, ?int $offset = null)
  {
    $qb = $this->getEntityClassName()::query();

    foreach ($criteria as $attribute => $value)
    {
      $qb = $qb->where($attribute, $value);
    }

    if (blank($orderBy) && !blank($this->defaultSort)) {
      $orderBy = $this->defaultSort[0] !== '-'
        ? [$this->defaultSort => 'asc']
        : [substr($this->defaultSort, 1, strlen($this->defaultSort)) => 'desc'];
    }

    foreach ($orderBy as $attribute => $direction)
    {
      $qb = $qb->orderBy($attribute, $direction);
    }

    if ($limit !== null) {
      $qb = $qb->limit($limit);
    }

    if ($offset !== null) {
      $qb = $qb->offset($offset);
    }

    return $qb->get();
  }

  /**
   * @inheritDoc
   */
  public function findOneBy(array $criteria, ?array $orderBy = [])
  {
    return $this->findBy($criteria, $orderBy, 1)->first();
  }

  /**
   * @inheritDoc
   */
  public function count(?array $criteria = []): int
  {
    $qb = $this->getEntityClassName()::query();

    foreach ($criteria as $attribute => $value)
    {
      $qb = $qb->where($attribute, $value);
    }

    return $qb->count();
  }

  /**
   * @inheritDoc
   */
  public function findWithQueryBuilder()
  {
    return $this->getQueryBuilder()->get();
  }

  /**
   * @inheritDoc
   */
  public function findOneWithQueryBuilder()
  {
    return $this->getQueryBuilder()->first();
  }

  /**
   * @inheritDoc
   */
  public function getQueryBuilder(?bool $applyCustomFilters = true): QueryBuilder
  {
    $queryBuilder = QueryBuilder::for($this->getEntityClassName(), QueryBuilderRequest::fromRequest(app(Request::class)))
    //  ->allowedFields($this->allowedFields)
      ->allowedIncludes($this->allowedIncludes)
     // ->allowedAppends($this->allowedAppends)
      ->allowedFilters($this->allowedFilters);
     // ->allowedSorts($this->allowedSorts)
     // ->defaultSort($this->defaultSort);

    return $queryBuilder;
  }

  /**
   * @param QueryBuilderRequest $queryBuilder
   * @return QueryBuilderRequest
   */
  protected function applyQueryBuilderCustomFilters(QueryBuilder $queryBuilder): QueryBuilder
  {
    return $queryBuilder;
  }

  /**
   * @inheritDoc
   */
  public function paginate(): LengthAwarePaginator
  {
    return $this->getQueryBuilder()->paginate();
  }

}
