<?php
namespace Dspurl\Taobaosdk\Facades;
use Illuminate\Support\Facades\Facade;
class Taobaosdk extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'taobaosdk';
    }
}