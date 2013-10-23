<?php

namespace Anh\Bundle\PagerBundle;

use Anh\Bundle\PagerBundle\Adapter\PagerAdapterInterface;
use Anh\Bundle\PagerBundle\Adapter\DoctrineOrmAdapter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

class Pager
{
    /**
     * Number of pages which are visible at once
     */
    protected $pagerLength;

    protected $adapter;
    protected $rowsPerPage;
    protected $currentPage;
    protected $pagesCount;
    protected $totalRowsCount;
    protected $result;

    public function __construct($pagerLength)
    {
        $this->pagerLength = $pagerLength;
    }

    public function getPagerLength()
    {
        return $this->pagerLength;
    }

    public function paginate($adapter, $currentPage, $rowsPerPage)
    {
        $this->adapter = $this->guessAdapter($adapter);

        if (!$this->adapter instanceof PagerAdapterInterface) {
            throw new \InvalidArgumentException('Parameter $adapter should be instance of PagerAdapterInterface.');
        }

        $this->currentPage = $currentPage;
        $this->rowsPerPage = $rowsPerPage;

        $this->pagesCount = null;
        $this->totalRowsCount = null;
        $this->result = null;

        return $this;
    }

    public function getPagesCount()
    {
        if ($this->pagesCount === null) {
            $this->pagesCount = (integer) ceil($this->getTotalRowsCount() / $this->getRowsPerPage());
        }

        return $this->pagesCount;
    }

    public function getTotalRowsCount()
    {
        if ($this->totalRowsCount === null) {
            $this->totalRowsCount = $this->adapter->getTotalRowsCount();
        }

        return $this->totalRowsCount;
    }

    public function getResult()
    {
        if ($this->result === null) {
            $this->result = $this->adapter->getResult(($this->currentPage - 1) * $this->rowsPerPage, $this->rowsPerPage);
        }

        return $this->result;
    }

    public function isEmpty()
    {
        return $this->getTotalRowsCount() === 0;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    public function getRowsPerPage()
    {
        return $this->rowsPerPage;
    }

    protected function guessAdapter($adapter)
    {
        if ($adapter instanceof PagerAdapterInterface) {
            return $adapter;
        }

        if ($adapter instanceof QueryBuilder or $adapter instanceof Query) {
            return new DoctrineOrmAdapter($adapter);
        }

        return null;
    }
}
