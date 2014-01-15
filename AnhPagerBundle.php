<?php

namespace Anh\PagerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AnhPagerBundle extends Bundle
{
    public static function getRequiredBundles()
    {
        return array(
            'Sp\BowerBundle\SpBowerBundle',
        );
    }
}
