<?php
declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;

class CacheKernel extends HttpCache
{
    protected function getOptions()
    {
        // TODO cache options
        return [
//            'debug' => false // Uncomment on env === prod
        ];
    }
}
