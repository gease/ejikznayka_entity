<?php

namespace Drupal\ejikznayka\Plugin\GroupContentEnabler;


use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\ejikznayka\Entity\LessonType;

class GroupLessonDeriver extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    /** @var LessonType $lesson_type */
    foreach (LessonType::loadMultiple() as $name => $lesson_type) {
      $label = $lesson_type->label();

      $this->derivatives[$name] = [
        'entity_bundle' => $name,
        'label' => t('Group lesson (@type)', ['@type' => $label]),
        'description' => t('Adds %type lesson to groups both publicly and privately.', ['%type' => $label]),
      ] + $base_plugin_definition;
    }

    return $this->derivatives;
  }

}