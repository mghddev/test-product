<?php
namespace App\Lib\Pagination;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

/**
 * Class ListCriteria
 * @package App\Lib\Pagination
 */
class ListCriteria
{

    const DESC = 'desc';
    const ASC  = 'asc';

    const OPERATOR_EQUAL = '=';
    const OPERATOR_NOT_EQUAL = '<>';
    const OPERATOR_LIKE = '::';
    const OPERATOR_NOT_LIKE = '!::';
    const OPERATOR_BIGGER = '>';
    const OPERATOR_BIGGER_EQUAL = '>=';
    const OPERATOR_SMALLER = '<';
    const OPERATOR_SMALLER_EQUAL = '<=';
    const OPERATOR_BETWEEN = ':b:';
    const OPERATOR_NOT_BETWEEN = ':nb:';
    const OPERATOR_IN = ':in:';
    const OPERATOR_NOT_IN = ':nin:';


    const operators = [
        self::OPERATOR_EQUAL,
        self::OPERATOR_NOT_EQUAL,
        self::OPERATOR_BETWEEN,
        self::OPERATOR_BIGGER,
        self::OPERATOR_BIGGER_EQUAL,
        self::OPERATOR_SMALLER,
        self::OPERATOR_SMALLER_EQUAL,
        self::OPERATOR_LIKE,
        self::OPERATOR_NOT_LIKE,
        self::OPERATOR_IN,
        self::OPERATOR_NOT_IN
    ];



    /**
     * @var int
     */
    protected int $page = 1;

    /**
     * @var int
     */
    protected int $per_page = 10;

    /**
     * ['created_at' => 'desc', 'name'=>'asc']
     * @var array | null
     */
    protected ?array $sort = null;

    /**
     * @var array|null
     */
    protected ?array $fields = null;


    /**
     * [ 'operator' => [ 'key' => 'value(s)'] ]
     *
     * [ '=' => [ 'name' => 'Majid'] ]
     * [ '::' => [ 'name' => 'Ma'] ]
     * [ '>' => [ 'age' => '22'] ]
     * [ '<=' => [ 'age' => '30'] ]
     * [ ':b:' => ['age' => [12, 25] ] ]
     * [ ':nb:' => ['age' => [12, 25] ] ]
     * [ ':in:' => ['name' => [ 'Majid', 'Javad' ] ] ]
     * [ ':nin:' => ['age' => [20,21,22,23,24,25] ] ]
     *
     * @var array|null
     */
    protected ?array $filters = null;

    /**
     * @var array
     */
    protected array $relations = [];

    /**
     * @var array|null
     */
    protected ?array $search = null;

    /**
     * @var string|null
     */
    protected ?string $url = null;


    /**
     * @param Request $request
     * @return ListCriteria
     */
    function fromRequest(Request $request)
    {
        return self::fromRequestStatic($request);
    }

    /**
     * @param Request $request
     * @return ListCriteria
     */
    static function fromRequestStatic(Request $request)
    {
        $criteria = new static();

        $criteria->setPage($request->get('page', 1));
        $criteria->setFilters(self::parseFilters($request->get('filters', [])));
        $criteria->setSearch(self::parseSearch($request->get('search', [])));
        $criteria->setFields($request->get('fields', []));
        $criteria->setPerPage($request->get('per_page', 10));
        $criteria->setSort(self::parseSort($request->get('sort', '' ) ) );
        $criteria->setUrl($request->getRequestUri());
        $criteria->setRelations(empty($request->get('relations')) ? [] : $request->get('relations'));

        return $criteria;
    }


    /**
     * @param string $sort
     * @param bool $mysql
     * @return array
     */
    static function parseSort(string $sort, $mysql = true)
    {
        $result = [];
        if($mysql) {
            $valueDesc = self::DESC;
            $valueAsc = self::ASC;
        }

        else {
            $valueAsc = 1;
            $valueDesc  = -1;
        }

        if(!empty($sort)) {
            $sort_elements = explode(',', $sort);


            foreach ($sort_elements as $sort_element){
                if(starts_with($sort_element, '-')) {
                    $result[substr($sort_element, 1, strlen($sort_element))] = $valueDesc;
                }
                else {
                    $result[$sort_element] = $valueAsc;
                }
            }
        }
        else {
            $result = [];
        }

        return $result;
    }

    /**
     * @param array $search
     * @return array
     */
    static function parseSearch(array $search)
    {
        $fields = !empty($search['fields']) ? explode(',', $search['fields']) : [] ;

        $query  = !empty($search['q']) ? $search['q'] : null;

        return ['fields' => $fields, 'query' => $query];
    }

    /**
     * @param array $filters
     * @return array
     */
    static function parseFilters(array $filters)
    {
        $result = [];

        foreach ($filters as $fKey => $fValue) {

            if(! starts_with($fValue, self::operators )) {

                if($fValue == 'true') {
                    $fValue = true;
                }

                elseif ($fValue == 'false' ) {
                    $fValue = false;
                }

                elseif (self::is_int_val($fValue)) {
                    $fValue = (int) $fValue;
                }

                $result[] = [
                    'key'      => $fKey,
                    'operator' => self::OPERATOR_EQUAL,
                    'operands' => [ $fValue]
                ];

            }

            else {
                $operator = self::detectOperator($fValue);

                if($operator == ':b:') {

                    $operandString = substr($fValue, length($operator) , length($fValue));

                    $result[] = [
                        'key' => $fKey,
                        'operator' => $operator,
                        'operands' => explode(',', $operandString)
                    ];
                }

                else if($operator == ':in:') {

                    $operandString = substr($fValue, length($operator) , length($fValue));

                    $result[] = [
                        'key' => $fKey,
                        'operator' => $operator,
                        'operands' => explode(',', $operandString)
                    ];

                }

                else if($operator == ':nin:') {

                    $operandString = substr($fValue, length($operator) , length($fValue));

                    $result[] = [
                        'key' => $fKey,
                        'operator' => $operator,
                        'operands' => explode(',', $operandString)
                    ];
                }


                else {

                    $result[] = [
                        'key' => $fKey,
                        'operator' => $operator,
                        'operands' => [
                            mb_substr($fValue, mb_strlen($operator) , mb_strlen($fValue))
                        ]
                    ];
                }



            }

        }

        return $result;
    }


    /**
     * This function takes a string as argument
     * and check if this string starts with one of our operators if yes, it returns the operator
     * else it returns null
     *
     * @param $str
     * @return string | null
     */
    static function detectOperator(string $str)
    {
        foreach (self::operators as $operator) {
            if(starts_with($str, $operator)) {
                return $operator;
            }
        }

        return null;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->per_page;
    }


    /**
     * @param int $page
     * @return ListCriteria
     */
    public function setPage(int $page): ListCriteria
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @param int $per_page
     * @return ListCriteria
     */
    public function setPerPage(int $per_page): ListCriteria
    {
        $this->per_page = $per_page;

        return $this;
    }

    /**
     * @param array|null $sort
     * @return ListCriteria
     */
    public function setSort(?array $sort): ListCriteria
    {
        $this->sort = $sort;

        return $this;
    }


    /**
     * @return array|null
     */
    public function getSort(): ?array
    {
        return $this->sort;
    }

    /**
     * @param array|null $fields
     * @return ListCriteria
     */
    public function setFields(?array $fields): ListCriteria
    {
        $this->fields = $fields;

        return $this;
    }


    /**
     * @return int
     */
    function genOffset()
    {
        return ($this->page - 1) * $this->per_page + 1;
    }


    /**
     * @return int
     */
    function genLimit()
    {
        return $this->per_page;
    }

    /**
     * @return array
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @param array $relations
     * @return ListCriteria
     */
    public function setRelations(array $relations): ListCriteria
    {
        $this->relations = $relations;

        return $this;
    }


    /**
     * @param string $relation
     * @return bool
     */
    public function hasRelation(string $relation) :bool
    {
        return in_array($relation, $this->relations);
    }

    /**
     * @param string $relation
     * @return ListCriteria
     */
    public function addRelation(string $relation): ListCriteria
    {
        $this->relations[] = $relation;

        return $this;
    }

    /**
     * @param string $relation
     * @return ListCriteria
     */
    public function removeRelation(string $relation): ListCriteria
    {
        $relations = $this->relations;
        if (in_array($relation, $relations)) {
            $relations = array_diff($relations, [$relation]);
        }

        $this->relations = $relations;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getFilters(): ?array
    {
        return $this->filters;
    }

    /**
     * @param array|null $filters
     * @return ListCriteria
     */
    public function setFilters(?array $filters): ListCriteria
    {
        $this->filters = $filters;
        return $this;
    }


    /**
     *
     * [
     *       'key'      => $fKey,
     *       'operator' => self::OPERATOR_EQUAL,
     *       'operands' => [ $fValue]
     * ];
     *
     * @param array $filter
     * @return ListCriteria
     */
    public function addFilter(array $filter): ListCriteria
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getSearch(): ?array
    {
        return $this->search;
    }

    /**
     * @param array|null $search
     * @return ListCriteria
     */
    public function setSearch(?array $search): ListCriteria
    {
        $this->search = $search;

        return $this;
    }


    /**
     * @param string|null $url
     * @return ListCriteria
     */
    public function setUrl(?string $url): ListCriteria
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }



    /**
     * @param Builder $query
     * @return PaginatedEntityList
     */
    public function makePaginatedList(Builder $query)
    {
        $total = $query->count();

        $page_count = ceil($total / $this->getPerPage());

        if ($total == 0) {
            $this->setPage(0);
        }

        if ($this->getPage() > $page_count) {
            $this->setPage($page_count);
        }

        $data = $query->get()->toArray();

        $dataArray = [];
        foreach ($data as $stdObject) {
            $dataArray[] = json_decode(json_encode($stdObject), true);
        }

        $current_page = $this->getPage();
        $prev_page = max(1, $current_page - 1);
        $next_page = min($current_page + 1, $page_count);
        $last_page = $page_count;
        $first_page = 1;

        $url_components = parse_url($this->getUrl());

        $url_path = $url_components['path'];

        $url_query_string = $url_components['query'] ?? '';
        parse_str($url_query_string, $get_array);

        $get_array['per_page'] = $this->getPerPage();
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
            ->setPerPage($this->getPerPage())
            ->setCurrentPage($current_page_url)
            ->setFirstPage($first_page_url)
            ->setNextPage($next_page_url)
            ->setPrevPage($prev_page_url)
            ->setLastPage($last_page_url);


    }


    /**
     * @param $data
     * @return bool
     */
    static function is_int_val($data)
    {
        if (is_int($data) === true) return true;
        if (is_string($data) === true && is_numeric($data) === true) {
            return (strpos($data, '.') === false);
        }
    }

    /**
     * @return array|null
     */
    public function getFields(): ?array
    {
        return $this->fields;
    }


}
