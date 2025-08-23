<?php

namespace DangerWayne\Specification\Facades;

use Illuminate\Support\Facades\Facade;

class Specification extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'specification';
    }
}
