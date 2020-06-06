<?php

namespace Seongbae\Discuss;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Seongbae\Discuss\Skeleton\SkeletonClass
 */
class DiscussFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'discuss';
    }
}
