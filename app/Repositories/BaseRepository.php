<?php

namespace App\Repositories;

use App\Helpers\Constant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct()
    {
        $this->setModel();
    }

    private function setModel()
    {
        $model = $this->getModel();
        if (! ($model instanceof Model)) {
            throw new \Exception('Model not found');
        }
        $this->model = $model;
    }

    abstract protected function getModel(): Model;

    public function get(array $params = []): Collection
    {
        return $this->filter($params)->get();
    }

    public function filter(array $params): Builder
    {
        $query = $this->newQuery();

        // query common field
        $whereEquals = $this->buildWhereEqual(array_merge($params['wheres'] ?? []));
        $whereLikes = $this->buildWhereLike(array_merge($params['where_likes'] ?? [], $params['likes'] ?? []));
        $whereIns = $this->buildWhereIn($params['where_ins'] ?? []);
        $whereNotIns = $this->buildWhereIn($params['where_not_ins'] ?? []);
        $whereHas = $this->cleanValueNull($params['where_has'] ?? []);
        $orWheres = $this->cleanValueNull($params['or_wheres'] ?? []);
        $sort = $this->buildSort($params['sort'] ?? '');
        $relates = $params['relates'] ?? null;
        $relatesCount = $params['relates_count'] ?? null;

        // multi sort
        $sorts = [];
        $sortsParam = ($params['sorts'] ?? []);
        if (is_array($sortsParam)) {
            foreach ($sortsParam as $sortParam) {
                $sorts[] = $this->buildSort($sortParam);
            }
        }

        $query
            ->when($whereEquals, function ($query) use ($whereEquals) {
                $query->where($whereEquals);
            })
            ->when($whereIns, function ($query) use ($whereIns) {
                foreach ($whereIns as $key => $in)
                    $query->whereIn($key, $in);
            })
            ->when($whereNotIns, function ($query) use ($whereNotIns) {
                foreach ($whereNotIns as $key => $in)
                    $query->whereNotIn($key, $in);
            })
            ->when($whereLikes, function ($query) use ($whereLikes) {
                $query->where($whereLikes);
            })
            ->when(! empty($whereHas), function ($query) use ($whereHas) {
                foreach ($whereHas as $relateName => $conditions) {
                    if (! empty($conditions)) {
                        if (is_array($conditions)) {
                            $query->whereHas($relateName, function ($subQuery) use ($conditions) {
                                foreach ($conditions as $column => $condition) {
                                    if (is_array($condition) && ($condition[0] ?? false) && ($condition[2] ?? false) && strtoupper($condition[1] ?? false) === 'IN') {
                                        $subQuery->whereIn($condition[0], $condition[2]);
                                    } else if (is_callable($condition)) {
                                        $subQuery->where($condition);
                                    } else if (is_array($condition) && $condition[0] === 'LIKE') {
                                        $subQuery->where($column, 'LIKE', "%$condition[1]%");
                                    } else if (is_array($condition)) {
                                        $subQuery->where([$condition]);
                                    } else {
                                        $subQuery->where($column, $condition);
                                    }
                                }
                            });
                        } else {
                            $query->whereHas($relateName, $conditions);
                        }
                    }
                }
            })
            ->when(! empty($orWheres), function ($query) use ($orWheres) {
                $query->where(function ($query) use ($orWheres) {
                    foreach ($orWheres as $value) {
                        $query->orWhere($value);
                    }
                });
            })
            ->when(! empty($sorts), function ($query) use ($sorts) {
                foreach ($sorts as $sort) {
                    if (! empty($sort)) {
                        if (str_contains($sort['column'], 'raw|')) {
                            $sort['column'] = str_replace('raw|', '', $sort['column']);
                            $query->orderByRaw($sort['column'] . ' ' . $sort['direction']);
                        } else {
                            $query->orderBy($sort['column'], $sort['direction']);
                        }
                    }
                }
            })
            ->when(! empty($sort), function ($query) use ($sort) {
                if (str_contains($sort['column'], 'raw|')) {
                    $sort['column'] = str_replace('raw|', '', $sort['column']);
                    $query->orderByRaw($sort['column'] . ' ' . $sort['direction']);
                } else {
                    $query->orderBy($sort['column'], $sort['direction']);
                }
            })
            ->when(! empty($relates), function ($query) use ($relates) {
                $query->with($relates);
            })
            ->when(! empty($relatesCount), function ($query) use ($relatesCount) {
                $query->withCount($relatesCount);
            });

        return $query;
    }

    public function newQuery(): Builder
    {
        return $this->getModel()->newQuery();
    }

    protected function buildWhereEqual(array $params)
    {
        return $this->cleanValueNull($params);
    }

    protected function cleanValueNull($params)
    {
        return array_filter($params, function ($value) {
            return ! is_null($value);
        });
    }

    protected function buildWhereLike(array $params)
    {
        $wheres = [];
        $params = $this->cleanValueNull($params);
        foreach ($params as $key => $value) {
            $wheres[] = [$key, 'LIKE', '%' . $value . '%'];
        }

        return $wheres;
    }

    protected function buildWhereIn(array $params)
    {
        return $this->cleanValueNull($params);
    }

    protected function buildSort($sort)
    {
        if (empty($sort) || ! str_contains($sort, ':')) return [];
        $sorts = explode(':', $sort);

        if (count($sorts) !== 2 || ! in_array($sorts[1], ['asc', 'desc', 'ASC', 'DESC'])) {
            return [];
        }
        return [
            'column' => $sorts[0],
            'direction' => $sorts[1],
        ];
    }

    public function paginate(array $params = [], $limit = Constant::DEFAULT_LIMIT): LengthAwarePaginator
    {
        return $this->filter($params)->paginate($limit);
    }

    public function create(array $params): Model
    {
        return $this->newQuery()->create($params);
    }

    public function update($id, array $params): ?Model
    {
        $result = $this->newQuery()->find($id);
        $result->fill($params);
        $saved = $result->save();

        return $saved ? $result : null;
    }

    public function find(int|string $id): ?Model
    {
        return $this->findBy($id);
    }

    public function findBy($value, $column = 'id'): ?Model
    {
        return $this->newQuery()->where($column, $value)->first();
    }

    public function createOrUpdate(array $params, $instance = null): Model
    {
        $model = $this->getModel();
        if (! empty($instance) && $instance instanceof $model) {
            $model = $instance;
        }

        $model->fill($params);
        $model->save();

        return $model;
    }

    public function deleteAll(array $ids, string $column = 'id')
    {
        return $this->newQuery()->whereIn($column, $ids)->delete();
    }

    public function delete($id)
    {
        $model = $this->newQuery()->find($id);
        if ($model) {
            return $model->delete();
        }
        return false;
    }

    public function insert(array $params)
    {
        return $this->newQuery()->insert($params);
    }

    public function getTable()
    {
        return $this->newQuery()->getTable();
    }

    public function updateOrCreate(array $values, array $attributes = []): ?Model
    {
        return $this->newQuery()->updateOrCreate($attributes, $values);
    }

    public function upsert(array $params, array $uniqueByColumns, array $updatedColumns = null)
    {
        return $this->model->upsert($params, $uniqueByColumns, $updatedColumns);
    }

    public function restore($ids, string $column = 'id'): int
    {
        $query = $this->newQuery()->onlyTrashed();

        if (is_array($ids)) {
            return $query->whereIn($column, $ids)->restore();
        }

        return $query->where($column, $ids)->restore();
    }
}
