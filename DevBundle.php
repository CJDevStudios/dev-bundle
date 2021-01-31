<?php
/*
 * This file is part of Developer Bundle.
 *
 * (c) CJ Development Studios <contact@cjdevstudios.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CJDevStudios\DevBundle;

use CJDevStudios\DevBundle\DependencyInjection\DevExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DevBundle extends Bundle {

   public function getContainerExtension()
   {
      return new DevExtension();
   }
}
