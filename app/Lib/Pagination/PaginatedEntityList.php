<?php
namespace App\Lib\Pagination;

/**
 * Class PaginatedEntityList
 * @package App\Lib\Pagination
 */
class PaginatedEntityList
{

    /**
     * @var array
     */
    protected $data;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $per_page;

    /**
     * @var string
     */
    protected $next_page;

    /**
     * @var string
     */
    protected $prev_page;

    /**
     * @var string
     */
    protected $first_page;

    /**
     * @var string
     */
    protected $last_page;

    /**
     * @var string
     */
    protected $current_page;


    /**
     * @var int
     */
    protected $total;

    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data) : PaginatedEntityList
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param int $page
     * @return PaginatedEntityList
     */
    public function setPage(int $page): PaginatedEntityList
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $per_page
     * @return PaginatedEntityList
     */
    public function setPerPage(int $per_page): PaginatedEntityList
    {
        $this->per_page = $per_page;
        return $this;
    }

    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->per_page;
    }

    /**
     * @param string $next_page
     * @return PaginatedEntityList
     */
    public function setNextPage(string $next_page): PaginatedEntityList
    {
        $this->next_page = $next_page;
        return $this;
    }

    /**
     * @return string
     */
    public function getNextPage(): string
    {
        return $this->next_page;
    }

    /**
     * @param string $prev_page
     * @return PaginatedEntityList
     */
    public function setPrevPage(string $prev_page): PaginatedEntityList
    {
        $this->prev_page = $prev_page;
        return $this;
    }

    /**
     * @param string $first_page
     * @return PaginatedEntityList
     */
    public function setFirstPage(string $first_page): PaginatedEntityList
    {
        $this->first_page = $first_page;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrevPage(): string
    {
        return $this->prev_page;
    }

    /**
     * @param string $last_page
     * @return PaginatedEntityList
     */
    public function setLastPage(string $last_page): PaginatedEntityList
    {
        $this->last_page = $last_page;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastPage(): string
    {
        return $this->last_page;
    }

    /**
     * @return string
     */
    public function getFirstPage(): string
    {
        return $this->first_page;
    }


    /**
     * @param string $current_page
     * @return PaginatedEntityList
     */
    public function setCurrentPage(string $current_page): PaginatedEntityList
    {
        $this->current_page = $current_page;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentPage(): string
    {
        return $this->current_page;
    }

    /**
     * @param int $total
     * @return PaginatedEntityList
     */
    public function setTotal(int $total): PaginatedEntityList
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }


    /**
     * @return bool
     */
    public function isEmpty()
    {
        return ($this->total == 0);
    }


    /**
     * @param bool $no_meta
     * @return array
     */
    function toArray($no_meta = false)
    {
        if($no_meta) {
            return $this->data;
        }
        else {
            return [
                'data' => $this->data,
                'meta' => [
                    'page' => $this->page,
                    'per_page' => $this->per_page,
                    'total' => $this->total,
                    'first_page' => $this->first_page,
                    'prev_page' => $this->prev_page,
                    'current_page' => $this->current_page,
                    'next_page' => $this->next_page,
                    'last_page' => $this->last_page,
                ]
            ];
        }

    }


}
