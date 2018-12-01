<?php
/**
 * This file is part of the PositibeLabs Projects.
 *
 * (c) Pedro Carlos Abreu <pcabreus@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Positibe\Bundle\ClassificationBundle\Model;

use Pcabreus\Utils\Entity\SluggableByNameInterface;
use Pcabreus\Utils\Entity\TimestampableInterface;
use Pcabreus\Utils\Entity\ToggleableInterface;

interface CollectionInterface extends SluggableByNameInterface, ToggleableInterface, TimestampableInterface
{
}
