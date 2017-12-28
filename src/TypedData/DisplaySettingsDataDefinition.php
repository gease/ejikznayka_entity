<?php

namespace Drupal\ejikznayka\TypedData;

use Drupal\Core\TypedData\MapDataDefinition;
use Drupal\Core\TypedData\DataDefinition;


/**
 * Typed data for display settings.
 *
 * @see \Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem.
 */
class DisplaySettingsDataDefinition extends MapDataDefinition {

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    if (!isset($this->propertyDefinitions)) {
      $this->propertyDefinitions['font_size'] = DataDefinition::create('integer')
        ->setLabel(t('Font size'));
      $this->propertyDefinitions['interval'] = DataDefinition::create('float')
        ->setLabel(t('Interval (seconds)'));
      $this->propertyDefinitions['column'] = DataDefinition::create('string')
        ->setLabel(t('How to show the numbers?'))
        ->addConstraint('AllowedValues', ['single', 'column', 'line']);
      $this->propertyDefinitions['keep'] = DataDefinition::create('boolean')
        ->setLabel(t('Keep or hide line or column after last number?'));
      $this->propertyDefinitions['random_location'] = DataDefinition::create('boolean')
        ->setLabel(t('Random location'));
    }
    return $this->propertyDefinitions;
  }
}