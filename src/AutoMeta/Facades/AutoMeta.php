<?php namespace Orzcc\AutoMeta\Facades;

use Illuminate\Support\Facades\Facade;

class AutoMeta extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'autometa';
    }
}