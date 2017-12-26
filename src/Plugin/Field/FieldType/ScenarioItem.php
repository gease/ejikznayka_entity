<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\ListDataDefinition;
use Drupal\Core\TypedData\Plugin\DataType\IntegerData;

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

    $properties['count'] = DataDefinition::create('integer')
      ->setLabel(t('Number of numbers'));

    $properties['positions'] = ListDataDefinition::create('ejikznayka_position')
      ->setLabel(t('Positions'))
      ->setDescription(t("Positions of displayed numbers on the screen."));

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
        'positions' => [
          'description' => "Serialized position css values.",
          'type' => 'blob',
          'serialize' => TRUE,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   * Generate random sequence based on field settings.
   */
  public function preSave() {
    /** @var \Drupal\Core\TypedData\TypedDataManager $data_manager */
    //$data_manager = \Drupal::service('typed_data_manager');
    //$position_definition = $data_manager->createDataDefinition('ejikznayka_position');
    $this->count;
    $sequence = $positions = [];
    for ($i = 0; $i < $this->count; $i++) {
      $sequence[$i] = mt_rand(1, 100);
      // Generate random position.
      $position = [];
      $top = mt_rand(0, 50);
      $left = mt_rand(0, 50);
      if (mt_rand(1, 2) == 1) {
        $position['top'] = $top;
      }
      else {
        $position['bottom'] = $top;
      }
      if (mt_rand(1, 2) == 1) {
        $position['left'] = $left;
      }
      else {
        $position['right'] = $left;
      }
      //$positions[] = $data_manager->create($position_definition, $position);
      $positions[] = $position;
    }

    $this->set('sequence', $sequence);
    $this->set('positions', $positions);
  }

}