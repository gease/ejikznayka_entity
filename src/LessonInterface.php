<?php

namespace Drupal\ejikznayka;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Lesson entity.
 * @ingroup ejikznayka
 */
interface LessonInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface{

}