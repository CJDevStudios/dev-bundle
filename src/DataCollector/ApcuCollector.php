<?php
/**
 * ---------------------------------------------------------------------
 * Developer Bundle
 * Copyright (C) 2021 CJ Development Studios and contributors.
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of Developer Bundle.
 *
 * Developer Bundle is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Developer Bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Developer Bundle. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
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

        $this->data = [
            'count'         => (int) $info['num_entries'],
            'hits'          => (int) $info['num_hits'],
            'misses'        => (int) $info['num_misses'],
            'hitrate'       => (float) ($info['num_hits'] /  ($info['num_hits'] + $info['num_misses'])) * 100,
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
