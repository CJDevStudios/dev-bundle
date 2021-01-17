<?php
/*
 * This file is part of Developer Bundle.
 *
 * (c) CJ Development Studios <contact@cjdevstudios.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
