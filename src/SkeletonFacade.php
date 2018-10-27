<?php

namespace KonnectIT\PermissionUi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KonnectIT\PermissionUi\SkeletonClass
 */
class SkeletonFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'skeleton';
    }
}
