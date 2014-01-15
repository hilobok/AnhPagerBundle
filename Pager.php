<?php

namespace Anh\PagerBundle;

use Anh\PagerBundle\Adapter\PagerAdapterInterface;
use Anh\PagerBundle\Adapter\DoctrineOrmAdapter;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

class Pager
{
    /**
     * Number of pages which are visible at once
     */
    protected $pagerLength;

    /**
     * Pagination url
     */
    protected $url;

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

    public function getOptions()
    {
        return array(
            'pagesCount' => $this->getPagesCount(),
            'pagerLength' => $this->getPagerLength(),
            'currentPage' => $this->getCurrentPage(),
            'url' => $this->getUrl()
        );
    }

    public function getPagerLength()
    {
        return $this->pagerLength;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl($page = null)
    {
        if (!is_null($page)) {
            if (strpos($this->url, '{page}') !== false) {
                return str_replace('{page}', $page, $this->url);
            } else {
                return sprintf('%s%s', $this->url, $page);
            }
        }

        return $this->url;
    }

    public function paginate($data, $currentPage, $rowsPerPage)
    {
        $this->adapter = $this->guessAdapter($data);

        if (!$this->adapter instanceof PagerAdapterInterface) {
            throw new \InvalidArgumentException(
                sprintf("Unable to guess adapter for '%s'.", is_object($data) ? get_class($data) : gettype($data))
            );
        }

        $this->currentPage = $currentPage;
        $this->rowsPerPage = $rowsPerPage;

        $this->pagesCount = null;
        $this->totalRowsCount = null;
        $this->result = null;

        return clone $this;
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
            $this->result = $this->adapter->getResult(
                ($this->currentPage - 1) * $this->rowsPerPage,
                $this->rowsPerPage
            );
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

    protected function guessAdapter($data)
    {
        if ($data instanceof PagerAdapterInterface) {
            return $data;
        }

        if ($data instanceof QueryBuilder or $data instanceof Query) {
            return new DoctrineOrmAdapter($data);
        }

        return null;
    }
}
