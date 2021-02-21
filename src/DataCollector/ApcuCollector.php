<?php
/*
 * This file is part of Developer Bundle.
 *
 * (c) CJ Development Studios <contact@cjdevstudios.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CJDevStudios\DevBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * APCu Data Collector
 * @since 1.0.0
 */
class ApcuCollector extends DataCollector {

    /**
     * @inheritDoc
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $info = apcu_cache_info(true);
        $memory = (int) $info['mem_size'];

        $units = array('B', 'KiB', 'MiB', 'GiB', 'TiB');
        $i = floor(log($memory) / log(1024));
        $memory_value = sprintf('%.02F', $memory / (1024 ** $i)) * 1 . ' ' . $units[(int) $i];

        $total_calls = $info['num_hits'] + $info['num_misses'];
        $hit_rate = $total_calls > 0 ? (float) ($info['num_hits'] / $total_calls) * 100 : 0;

        $this->data = [
            'count'         => (int) $info['num_entries'],
            'hits'          => (int) $info['num_hits'],
            'misses'        => (int) $info['num_misses'],
            'hitrate'       => $hit_rate,
            'memory_usage'  => $memory_value,
            'memory_raw'    => $memory
        ];
    }

    public function getName(): string
    {
        return 'cjds.apcu_collector';
    }

    public function reset(): void
    {
        $this->data = [];
    }

    /**
     * @since 1.0.0
     * @return int
     */
    public function getCount(): int
    {
        return $this->data['count'];
    }

    /**
     * @since 1.0.0
     * @return int
     */
    public function getHits(): int
    {
        return $this->data['hits'];
    }

    /**
     * @since 1.0.0
     * @return int
     */
    public function getMisses(): int
    {
        return $this->data['misses'];
    }

    /**
     * @since 1.0.0
     * @return int
     */
    public function getMemoryUsage(): int
    {
        return $this->data['memory_usage'];
    }

    /**
     * @since 1.0.0
     * @return int
     */
    public function getMemoryRaw(): int
    {
        return $this->data['memory_raw'];
    }

    /**
     * @since 1.0.0
     * @return float
     */
    public function getHitRate(): float
    {
        return (int) $this->data['hitrate'];
    }
}
