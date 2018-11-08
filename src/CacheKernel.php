<?php
declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

/**
 * Class CacheKernel
 * @package App
 */
class CacheKernel extends HttpCache
{
    /**
     * @return array
     */
    protected function getOptions(): array
    {
        // TODO cache options
        return [
//            'debug' => false // Uncomment on env === prod
        ];
    }
}
