<?php

namespace Anh\PagerBundle\Adapter;

use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrineOrmAdapter implements PagerAdapterInterface
{
    protected $paginator;

    public function __construct($query, $fetchJoinCollection = true, $useOutputWalkers = false)
    {
        $this->paginator = new Paginator($query, $fetchJoinCollection);
        $this->paginator->setUseOutputWalkers($useOutputWalkers);
    }

    public function getTotalRowsCount()
    {
        return $this->paginator->count();
    }

    public function getResult($offset, $limit)
    {
        $this->paginator->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;

        return $this->paginator->getIterator();
    }
}
