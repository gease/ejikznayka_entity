<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\ListDataDefinition;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\ejikznayka\TypedData\DisplaySettingsDataDefinition;

/**
 * Plugin implementation of the 'scenario' field type.
 *
 * @FieldType(
 *   id = "ejikznayka_scenario",
 *   label = @Translation("Scenario"),
 *   description = @Translation("Create and save scenario for math learning."),
 *   default_widget = "ejikznayka_scenario_default",
 *   default_formatter = "ejikznayka_scenario_play",
 * )
 */
class ScenarioItem extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['title'] = DataDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t("Title for the task."))
      ->setRequired(TRUE);

    $properties['sequence'] = ListDataDefinition::create('integer')
      ->setLabel(t('Numbers'))
      ->setDescription(t("Sequence of numbers to be displayed during the task."))
      ->setRequired(TRUE);

    $properties['positions'] = ListDataDefinition::create('ejikznayka_position')
      ->setLabel(t('Positions'))
      ->setDescription(t("Positions of displayed numbers on the screen."));

    $properties['max'] = DataDefinition::create('integer')
      ->setLabel(t('Maximal value'))
      ->addConstraint('Range', ['min' => 0, 'max' => 65535])
      ->setRequired(TRUE);

    $properties['min'] = DataDefinition::create('integer')
      ->setLabel(t('Minimal value'))
      ->addConstraint('Range', ['min' => 0, 'max' => 65535])
      ->setRequired(TRUE);

    $properties['minus'] = DataDefinition::create('boolean')
      ->setLabel(t('Subtraction allowed'));

    $properties['count'] = DataDefinition::create('integer')
      ->setLabel(t('Number of numbers'))
      ->setRequired(TRUE);

    $properties['display_settings'] = DisplaySettingsDataDefinition::create('ejikznayka_display_settings')
      ->setLabel(t('Display settings'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'title' => [
          'description' => "Title.",
          'type' => 'varchar',
          'length' => 128,
        ],
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
        'max' => [
          'description' => "Maximal value",
          'type' => 'int',
          'size' => 'small',
          'unsigned' => TRUE,
        ],
        'min' => [
          'description' => "Minimal value",
          'type' => 'int',
          'size' => 'small',
          'unsigned' => TRUE,
        ],
        'minus' => [
          'description' => "Is subtraction allowed",
          'type' => 'int',
          'size' => 'tiny',
        ],
        'count' => [
          'description' => "Number of numbers",
          'type' => 'int',
          'size' => 'tiny',
          'unsigned' => TRUE,
        ],
        'display_settings' => [
          'description' => "Serialized display settings",
          'type' => 'blob',
          'serialize' => TRUE,
        ],
      ],
      'indexes' => [
        'title' => ['title'],
        'count' => ['count'],
        'minmax' => ['min', 'max'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  /**public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::fieldSettingsForm($form, $form_state);
    $form['minus'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Subtraction allowed'),
      '#default_value' => $this->getSetting('minus'),
    ];
    return $form;
  }*/

}