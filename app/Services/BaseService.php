<?php

namespace App\Services;

use App\Helpers\Constant;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

abstract class BaseService
{
    protected BaseRepository|null $repository = null;

    public function __construct()
    {
        $this->setRepository();
    }

    private function setRepository()
    {
        $repository = $this->getRepository();
        if (!($repository instanceof BaseRepository)) {
            throw new \Exception('Repository not found');
        }
        $this->repository = $repository;
    }

    abstract protected function getRepository(): BaseRepository;

    /**
     * @return array{
     *     where_equals: ?array<mixed>,
     *     wheres: ?array<mixed>,
     *     where_likes: ?array<mixed>
     *     likes: ?array<mixed>
     *     where_ins: ?array<string, array>
     *     where_not_ins: ?array<string, array>
     *     where_has: ?array<mixed>
     *     or_wheres: ?array<mixed>
     *     sort: ?string
     *     sorts: array<string, mixed>
     *     relates: ?array<mixed>
     * }
     */
    abstract protected function buildFilterParams(array $params): array;

    public function search(array $params = [], $limit = Constant::DEFAULT_LIMIT): LengthAwarePaginator
    {
        return $this->paginate($params, $limit);
    }
    public function paginate(array $params = [], $limit = Constant::DEFAULT_LIMIT): LengthAwarePaginator
    {
        if (!empty($params['limit'])) {
            $limit = $params['limit'];
        }

        return $this->filter($params)->paginate($limit);
    }

    public function filter(array $params = []): Builder
    {
        return $this->getRepository()->filter($this->buildFilterParams($params));
    }

    public function find(int|string $id): ?Model
    {
        return $this->getRepository()->find($id);
    }

    public function findBy(array $params = []): ?Model
    {
        return $this->filter($params)->first();
    }

    public function create(array $params = []): Model
    {
        $params = $this->hashPassword($params);
        return $this->getRepository()->create($params);
    }

    protected function hashPassword($params)
    {
        if (!empty(($value = Arr::get($params, 'password')))) {
            if ($salt = Arr::get($params, 'password_salt')) {
                $params['password'] = Hash::make($value, ['salt' => $salt]);
            } else {
                $params['password'] = Hash::make($value);
            }
        } else {
            // remove null or empty string password
            unset($params['password']);
            unset($params['password_salt']);
        }

        return $params;
    }

    public function get(array $params = []): Collection
    {
        return $this->filter($params)->get();
    }

    public function insert(array $params = [])
    {
        return $this->getRepository()->insert($params);
    }

    public function update(int|string $id, array $params = []): Model
    {
        $params = $this->hashPassword($params);

        return $this->getRepository()->update($id, $params);
    }

    public function delete($id)
    {
        return $this->getRepository()->delete($id);
    }

    public function deleteAll(array $ids)
    {
        return $this->getRepository()->deleteAll($ids);
    }

    public function createOrUpdate($params, $instance = null)
    {
        return $this->getRepository()->createOrUpdate($params, $instance);
    }

    public function updateOrCreate(array $values, array $attributes = [])
    {
        return $this->getRepository()->updateOrCreate($values, $attributes);
    }

    public function targetPage(array $params)
    {
        $current_page = isset($params['current_page']) ? (int) $params['current_page'] : 1;

        $total = $this->getRepository()->paginate()->total();
        $per_page = $this->getRepository()->paginate()->perPage();
        $last_page = (int) ceil($total / $per_page);

        $targetPage = $current_page > $last_page ? $last_page : $current_page;
        $targetPage = $targetPage < 1 ? 1 : $targetPage;

        $result = [
            'page' => $targetPage,
        ];

        if (!empty($params['search'])) {
            $result['search'] = $params['search'];
        }

        return $result;
    }
}
