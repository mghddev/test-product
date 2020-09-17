<?php
namespace App\Common;

use App\Lib\Pagination\ListCriteria;
use App\Lib\Pagination\PaginatedEntityList;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RepoTrait
 * @package App\Common
 */
trait RepoTrait
{
    /**
     * @param Builder $query_builder
     * @param ListCriteria $listCriteria
     * @param Model $model
     * @return Builder
     */
    protected function buildFiltersQuery(Builder $query_builder, ListCriteria $listCriteria, Model $model)
    {
        if ( !empty($filters = $listCriteria->getFilters()) ) {

            foreach ($filters as $filter) {

                if(str_contains($filter['key'], '.')) {
                    $parts = explode('.', $filter['key']);

                    $filter['relation'] = $parts[0];
                    $filter['key'] = $parts[1];

                    $query_builder->whereHas($filter['relation'], function (Builder $query) use ($filter, $model) {
                        $this->buildFilter($filter, $query, $filter['key']);
                    });

                }
                else {
                    $query_builder = $this->buildFilter($filter, $query_builder, $model->getTable().'.'.$filter['key']);
                }

            }
        }

        return $query_builder;
    }


    /**
     * @param array $filter
     * @param Builder $query
     * @param string $key
     * @return Builder
     */
    private function buildFilter(array $filter, $query, string $key)
    {
        switch ($filter['operator']) {

            case '=':
                $query->where(
                    $key,
                    '=',
                    $filter['operands'][0]
                );
                break;

            case '<>':
                $query->where(
                    $key,
                    '<>',
                    $filter['operands'][0]
                );
                break;

            case '>':
                $query->where(
                    $key,
                    '>',
                    $filter['operands'][0]
                );
                break;

            case '<':
                $query->where(
                    $key,
                    '<',
                    $filter['operands'][0]
                );
                break;

            case '::':
                $query->where(
                    $key,
                    'like',
                    '%' . $filter['operands'][0] . '%'
                );
                break;

            case '!::':
                $query->where(
                    $key,
                    'not like',
                    '%' . $filter['operands'][0] . '%'
                );
                break;

            case ':b:':
                $query->whereBetween(
                    $key,
                    $filter['operands']
                );
                break;

            case ':in:':

                $query->whereIn(
                    $key,
                    $filter['operands']
                );
                break;

            case ':nin:':
                $query->whereNotIn(
                    $key,
                    $filter['operands']
                );
                break;

        }

        return $query;
    }


    /**
     * @param Builder $query
     * @param ListCriteria $listCriteria
     * @return Builder
     */
    protected function buildSearchQuery(Builder $query, ListCriteria $listCriteria)
    {
        if(!empty($search = $listCriteria->getSearch())) {

            $query->where(function (Builder $query) use ($search) {

                foreach ($search['fields'] as $field) {

                    if(! str_contains($field, '.')) {
                        $query->orWhere($field, 'like', "%".$search['query']."%");
                    }

                    else {
                        $parts = explode('.', $field);
                        $count = count($parts);
                        $pos = strrpos($field, ".");
                        $relation = substr($field, 0, $pos);

                        $query->orWhereHas($relation, function (Builder $query) use ($parts, $search, $count) {
                            $query->where($parts[$count - 1], 'like', '%'.$search['query'].'%');
                        });

                    }
                }

            });
        }

        return $query;
    }


    /**
     *
     * ## todo : we dont support relation table columns based sort
     *
     * @param Builder $query
     * @param ListCriteria $listCriteria
     * @param Model $model
     * @return Builder
     */
    protected function buildSort(Builder $query, ListCriteria $listCriteria, Model $model)
    {
        if(!empty($sortElements = $listCriteria->getSort()) ) {

            foreach ($sortElements as $key => $dir ) {

                if (strpos($key, '.') == false) {
                    $query->orderBy($model->getTable().'.'.$key, $dir);
                }

            }
        }

        return $query;

    }


    /**
     * @param Builder $query
     * @param ListCriteria $listCriteria
     * @return Builder
     */
    protected function buildRelations(Builder $query, ListCriteria $listCriteria)
    {
        $relations = $listCriteria->getRelations();

        return $query->with($relations);
    }


    /**
     * @param Builder $query
     * @param ListCriteria $listCriteria
     * @param Model $model
     * @return Builder
     */
    protected function buildFields(Builder $query, ListCriteria $listCriteria, Model $model)
    {
        $fields = $listCriteria->getFields();

        if(!empty($fields)) {
            $r = [];

            foreach ($fields as $field) {
                $r[] = $model->getTable().".$field";
            }

            $query->select($r);
        }
        else {
            return $query;
        }


        return $query;
    }


    /**
     * @param ListCriteria $list_criteria
     * @param Model $model
     * @return PaginatedEntityList
     */
    protected function getListByCriteria(ListCriteria $list_criteria, Model $model)
    {
        return $this->makePaginatedList($this->buildQueryByListCriteria($list_criteria, $model), $list_criteria);
    }


    /**
     * @param ListCriteria $list_criteria
     * @param Builder $query
     * @param Model $model
     * @return PaginatedEntityList
     */
    protected function getListByCriteriaAndQuery(ListCriteria $list_criteria, Builder $query, Model $model)
    {
        return $this->makePaginatedList($this->buildQueryByListCriteria($list_criteria, $model, $query), $list_criteria);
    }

    /**
     * @param ListCriteria $list_criteria
     * @param Builder $query
     * @param Model $model
     * @return PaginatedEntityList
     */
    protected function getListByCriteriaAndQueryAndNotCheckSoftDelete(ListCriteria $list_criteria, Builder $query, Model $model)
    {
        return $this->makePaginatedList($this->buildQueryByListCriteria($list_criteria, $model, $query,true), $list_criteria);
    }


    /**
     * @param ListCriteria $list_criteria
     * @param Model $model
     * @param Builder $query
     * @param bool $not_check_soft_delete
     * @return Builder
     */
    private function buildQueryByListCriteria(ListCriteria $list_criteria, Model $model, Builder $query = null, bool $not_check_soft_delete = false)
    {
        if($query == null) {
            $query = $model->newQuery();
        }

        $query = $this->buildFiltersQuery($query, $list_criteria, $model);
        $query = $this->buildRelations($query, $list_criteria);
        $query = $this->buildSearchQuery($query, $list_criteria);
        $query = $this->buildSort($query, $list_criteria, $model);
        $query = $this->buildFields($query, $list_criteria, $model);

        ## check if model supports soft delete
        ## in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))

        if(!$not_check_soft_delete) {

            if (method_exists($model, 'trashed')) {
                $query->whereNull('deleted_at');
            }
        }

        return $query;
    }


    /**
     * @param Builder $query
     * @param ListCriteria $list_criteria
     * @return PaginatedEntityList
     */
    public function makePaginatedList(Builder $query, ListCriteria $list_criteria)
    {
        $total = $query->count();

        $page_count = ceil($total / $list_criteria->getPerPage());

        if ($total == 0) {
            return (new PaginatedEntityList())
                ->setData([])
                ->setPage($list_criteria->getPage())
                ->setTotal($total)
                ->setPerPage($list_criteria->getPerPage());

        } else {

            if ($list_criteria->getPage() > $page_count) {
                $list_criteria->setPage($page_count);
            }

            $skip = $list_criteria->getPerPage() * ($list_criteria->getPage() - 1);

            $dataArray = $query->skip($skip)->take($list_criteria->getPerPage())->get()->toArray();

            $current_page = $list_criteria->getPage();
            $prev_page = max(1, $current_page - 1);
            $next_page = min($current_page + 1, $page_count);
            $last_page = $page_count;
            $first_page = 1;

            $url_components = parse_url($list_criteria->getUrl());

            $url_path = $url_components['path'];

            $url_query_string = $url_components['query'] ?? '';
            parse_str($url_query_string, $get_array);

            $get_array['per_page'] = $list_criteria->getPerPage();
            $get_array['page'] = $current_page;
            $current_page_url = $url_path . '?' . http_build_query($get_array);

            $get_array['page'] = $prev_page;
            $prev_page_url = $url_path . '?' . http_build_query($get_array);

            $get_array['page'] = $next_page;
            $next_page_url = $url_path . '?' . http_build_query($get_array);

            $get_array['page'] = $first_page;
            $first_page_url = $url_path . '?' . http_build_query($get_array);

            $get_array['page'] = $last_page;
            $last_page_url = $url_path . '?' . http_build_query($get_array);

            return (new PaginatedEntityList())
                ->setData($dataArray)
                ->setPage($current_page)
                ->setTotal($total)
                ->setPerPage($list_criteria->getPerPage())
                ->setCurrentPage($current_page_url)
                ->setFirstPage($first_page_url)
                ->setNextPage($next_page_url)
                ->setPrevPage($prev_page_url)
                ->setLastPage($last_page_url);

        }
    }


}
