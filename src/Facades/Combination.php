<?php

namespace WsPackages\CombinationGenerate\Facades;

use Illuminate\Support\Facades\Facade;
use WsPackages\CombinationGenerate\Services\CombinationService;

/**
 * @method static array generateCombination(array $arrays, int $index = 0)
 * @method static array generate_combination(array $arrays, int $i = 0)
 * 
 * @see \WsPackages\CombinationGenerate\Services\CombinationService
 */
class Combination extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return CombinationService::class;
    }
}
