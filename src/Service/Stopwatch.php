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

namespace CJDevStudios\DevBundle\Service;

use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Stopwatch\Stopwatch as SymfonyStopwatch;

/**
 * @since 1.0.0
 */
final class Stopwatch {

   /**
    * @since 1.0.0
    * @var SymfonyStopwatch
    */
   private static SymfonyStopwatch $stopwatch;

   /**
    * @since 1.0.0
    * @var KernelInterface
    */
   private KernelInterface $kernel;

   public function __construct(KernelInterface $kernel)
   {
      $this->kernel = $kernel;
   }

   private function init(): void
   {
      try {
         $debug_stopwatch = $this->kernel->getContainer()->get('debug.stopwatch');
      } catch (ServiceNotFoundException $e) {
         $debug_stopwatch = null;
      }
      /** @var SymfonyStopwatch $debug_stopwatch */
      if ($debug_stopwatch !== null && $this->kernel->getEnvironment() === 'dev') {
         self::$stopwatch = $debug_stopwatch;
      } else {
         self::$stopwatch = new SymfonyStopwatch();
      }
   }

   public function start(string $name, string $category = 'app')
   {
      if (self::$stopwatch === null) {
         $this->init();
      }
      self::$stopwatch->start($name, $category);
   }

   public function stop(string $name)
   {
      if (self::$stopwatch === null) {
         return;
      }
      self::$stopwatch->stop($name);
   }
}
