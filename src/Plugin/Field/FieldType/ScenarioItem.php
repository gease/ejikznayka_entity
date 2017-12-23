<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\ListDataDefinition;

/**
 * Plugin implementation of the 'scenario' field type.
 *
 * @FieldType(
 *   id = "ejikznayka_scenario",
 *   label = @Translation("Scenario"),
 *   description = @Translation("Create and save scenario for math learning."),
 *   default_widget = "ejikznayka_scenario_default",
 *   default_formatter = "ejikznayka_scenario_default",
 * )
 */
class ScenarioItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['sequence'] = ListDataDefinition::create('integer')
      ->setLabel(t('Numbers'))
      ->setDescription(t("Sequence of numbers to be displayed during the lesson."))
      ->setRequired(TRUE);

    /*$properties['positions'] = DataDefinition::create('list')
      ->setItemDefinition(DataDefinition::create('integer'))
      ->setLabel(t('Positions'))
      ->setDescription(t("Positions of displayed numbers on the screen."));
*/
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'sequence' => [
          'description' => "Serialized sequence.",
          'type' => 'blob',
          'serialize' => TRUE,
        ],
        /*'positions' => [
          'description' => "Serialized position css values.",
          'type' => 'blob',
          'serialize' => TRUE,
        ],*/
      ],
    ];
  }
}