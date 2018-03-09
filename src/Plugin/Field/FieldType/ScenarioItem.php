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
 *   constraints = {"Scenario" = {}},
 *   list_class = "\Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItemList",
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

    if ($field_definition->getSetting('store')) {
      $properties['sequence'] = ListDataDefinition::create('integer')
        ->setLabel(t('Numbers'))
        ->setDescription(t("Sequence of numbers to be displayed during the task."))
        ->setRequired(TRUE);
      $properties['positions'] = ListDataDefinition::create('ejikznayka_position')
        ->setLabel(t('Positions'))
        ->setDescription(t("Positions of displayed numbers on the screen."));
    }

    $properties['max'] = DataDefinition::create('integer')
      ->setLabel(t('Maximal value'))
      ->addConstraint('Range', ['min' => 0, 'max' => 65535])
      ->setRequired(TRUE);

    $properties['min'] = DataDefinition::create('integer')
      ->setLabel(t('Minimal value'))
      ->addConstraint('Range', ['min' => 0, 'max' => 65535])
      ->setRequired(TRUE);

    $properties['minus'] = DataDefinition::create('boolean')
      ->setLabel(t('Subtraction allowed'))
      ->setRequired(TRUE);

    $properties['count'] = DataDefinition::create('integer')
      ->setLabel(t('Number of numbers'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'title' => [
          'description' => "Title.",
          'type' => 'varchar',
          'length' => 128,
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
      ],
      'indexes' => [
        'title' => ['title'],
        'count' => ['count'],
        'minmax' => ['min', 'max'],
      ],
    ];
    if ($field_definition->getSetting('store')) {
      $schema['columns']['sequence'] = [
        'description' => "Serialized sequence.",
        'type' => 'blob',
        'serialize' => TRUE,
      ];
      $schema['columns']['positions'] = [
        'description' => "Serialized position css values.",
        'type' => 'blob',
        'serialize' => TRUE,
      ];
    }
    return $schema;
  }

  /**
   * @inheritDoc
   */
  public function applyDefaultValue($notify = TRUE) {
    $default = $this->getFieldDefinition()->getDefaultValue($this->getEntity());
    $this->setValue($default[0]);
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return FALSE;
  }

  /**
   * @inheritDoc
   */
  public static function defaultStorageSettings() {
    return [
      'store' => TRUE,
    ];
  }


  /**
   * @inheritDoc
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = [];
    $element['store'] = [
      '#type' => 'checkbox',
      '#title' => t('Store scenario instead of random generation on-the-fly'),
      '#description' => t('If checked, scenario in this field will be stored in the database and each time replayed the same.
            If not checked, each time it will be generated according to settings just when played, and each time will be different '),
      '#default_value' => $this->getSetting('store'),
      '#disabled' => $has_data,
    ];
    return $element;
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