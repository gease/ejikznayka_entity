<?php

namespace Drupal\ejikznayka\TypedData;


use Drupal\Core\TypedData\MapDataDefinition;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Typed data for css absolute positioning of an element.
 *
 * @see \Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem.
 */
class PositionDataDefinition extends MapDataDefinition {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    if (!isset($this->propertyDefinitions)) {
      foreach (['left', 'right', 'top', 'bottom'] as $property) {
        $this->propertyDefinitions[$property] = DataDefinition::create('string')
          ->setLabel($property)
          ->setRequired(TRUE);
          //->addConstraint('Range', ['min' => 0, 'max' => 100]);
      }
    }
    return $this->propertyDefinitions;
  }

}