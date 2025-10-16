<?php

namespace App\Repositories;

use App\Interfaces\IBase;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BaseRepository implements IBase
{
    protected $model;

    protected $associate;

    protected $orgAdminAssociate;

    protected $branchAdminAssociate;

    protected $propertyAdminAssociate;

    protected $morphTo;

    private $perpage = 15;

    protected Builder $builder;

    private string $dateControlKey = 'created_at';

    private array $dateKeys = ['updated_at', 'created_at', 'deleted_at'];

    protected int $defaultDateRange = 10; //3 Months

    private string $defaultOrderKey = 'created_at';

    private string $defaultOrder = 'desc'; //newest

    private array $trashedAllwedValues = ['without', 'with', 'only'];

    public $data;

    const SUCCESS = 'SUCCESS';

    const NO_DATA = 'NO_DATA';

    const FAILED = 'FAILED';


    public function build(): void
    {
        $this->builder = $this->model::query();
    }

    protected function searchWithUser($search): void
    {
        $this->builder = $this->builder->where(function ($query) use ($search) {
            $query->orWhereHas('user', function ($subquery) use ($search) {
                $subquery->where('first_name', 'LIKE', '%' . $search . '%');
                $subquery->orWhere('last_name', 'LIKE', '%' . $search . '%');
                $subquery->orWhere('email', 'LIKE', '%' . $search . '%');
                $subquery->orWhere('username', 'LIKE', '%' . $search . '%');
            });
        });
    }
    protected function search($search): void
    {
        $obj = new $this->model;
        $searchableKeys = $obj?->quickSearchableArray ?? [];
        if (count($searchableKeys) > 0) {
            foreach ($searchableKeys as $index => $key) {
                if ($index == 0) {
                    $this->builder = $this->builder->where($key, 'LIKE', '%'.$search.'%');
                } else {
                    $this->builder = $this->builder->orWhere($key, 'LIKE', '%'.$search.'%');
                }
            }
        }
    }

    private function trashedControl($trashed): void
    {
        $this->builder = ($trashed == 'with')
            ? $this->builder->withTrashed()
            : (($trashed == 'only')
                ? $this->builder->onlyTrashed()
                : $this->builder->withoutTrashed());
    }

    private function dateControl(
        ?string $start,
        ?string $end,
        ?string $dateKey
    ): void {
        $start = (! $start) ? now()->subMonths($this->defaultDateRange) : $start;
        $end = (! $end) ? now()->addMonths($this->defaultDateRange) : $end;
        $fieldName = (in_array($dateKey, $this->dateKeys) && $dateKey) ? $dateKey : $this->dateControlKey;
        $this->builder = $this->builder
            ->whereBetween($fieldName, [$start, $end]);
    }

    public function filter($params = null): object
    {
        // $this->builder = $this->model::query();
        $this->perpage = $params?->perpage ?? $this->perpage;
        //*==> [X] set order key
        $order = ($params?->order != $this->defaultOrder)
            ? (
                (in_array($params?->order, ['asc', 'desc']))
                    ? $params->order
                    : $this->defaultOrder
            ) : $this->defaultOrder;

        //*==> [X] check request trashed request
        if (
            $params?->has('trashed')
            && $params?->trashed != null
            && in_array($params?->trashed, $this->trashedAllwedValues)
        ) {
            $this->trashedControl($params?->trashed);
        }

        //*==> [X] Get data between two dates
        if (
            $params?->has('start_date')
            || $params?->has('end_date')
        ) {
            $this->dateControl(
                $params?->start_date ?? null,
                $params?->end_date ?? null,
                $params?->date_key ?? null
            );
        }

        //*==> [X] call search function if search requested
        if (
            $params?->has('search')
            && $params?->search != null
        ) {
            $this->search($params->search);
        }

        if (
            $params?->has('searchWithUser')
            && $params?->searchWithUser != null
        ) {
            $this->searchWithUser($params->searchWithUser);
        }

        //*==> [X] final step is set ordering records
        $this->builder = ($params?->has('order_by'))
            ? $this->builder->orderBy($params->order_by, $order)
            : $this->builder->orderBy($this->defaultOrderKey, $order);

        return $this;
    }

    public function paginate()
    {
        return $this->builder->paginate($this->perpage);
    }

    public function all()
    {
        return $this->builder->get();
    }

    public function byId(int $id): object
    {
        return $this->model::whereId($id)->first();
    }

    public function editById(array $data, int $id)
    {
        $this->model::whereId($id)->update($data);

        return $this->model::whereId($id)->firstOrFail();
    }

    public function createObject(array $object, $class, $save = true): object
    {
        $newObj = new $class;
        foreach ($object as $key => $value) {
            $newObj->$key = $value;
        }
        if ($save === true) {
            $newObj->save();
        }

        return $newObj;
    }

    protected function setResponse(string $apiStatus, $data = null, array $hints = []): object
    {

        // Include the total count from the pagination response into the hints array
        if ($data instanceof LengthAwarePaginator) {
            // If $data is a LengthAwarePaginator, include the total from pagination
            $hints = array_merge($hints, ['total' => $data->total()]);
        } elseif (is_array($data) && array_key_exists('total', $data)) {
            // If $data is an array and contains a 'total' attribute, use that value
            $hints = array_merge($hints, ['total' => $data['total']]);
        } else {
            $count = is_countable($data) ? count($data) : 0; // Get the count of $data if it's countable, otherwise set it to 0
            $hints = array_merge($hints, ['total' => $count]);
        }

        return (object) [
            'api' => $apiStatus,
            'hints' => $hints,
            'data' => $data,
        ];
    }
}
