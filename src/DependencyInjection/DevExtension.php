<?php
/*
 * This file is part of Developer Bundle.
 *
 * (c) CJ Development Studios <contact@cjdevstudios.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CJDevStudios\DevBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DevExtension extends Extension {

   public function load(array $configs, ContainerBuilder $container)
   {
      $loader = new YamlFileLoader(
         $container,
         new FileLocator(__DIR__.'/../../config')
      );
      $loader->load('services.yaml');
   }
}
