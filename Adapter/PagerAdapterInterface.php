<?php

namespace Anh\PagerBundle\Adapter;

interface PagerAdapterInterface
{
    public function getTotalRowsCount();
    public function getResult($offset, $limit);
}
