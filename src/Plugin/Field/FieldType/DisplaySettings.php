<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\ejikznayka\TypedData\DisplaySettingsDataDefinition;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines display settings for the lesson entity.
 *
 * @FieldType(
 *   id = "ejikznayka_display_settings",
 *   label = @Translation("Display Settings"),
 *   description = @Translation("An entity field defining lesson display settings."),
 *   default_widget = "ejikznayka_display_settings_default",
 *   default_formatter = "ejikznayka_scenario_default",
 *   no_ui = true,
 * )
 */
class DisplaySettings extends FieldItemBase {

  /**
   * @inheritDoc
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    /*$properties['display_settings'] = DisplaySettingsDataDefinition::create('ejikznayka_display_settings')
      ->setLabel(t('Display settings'))
      ->setRequired(TRUE);*/
    $properties['font_size'] = DataDefinition::create('integer')
      ->setLabel(t('Font size'))
      ->setRequired(TRUE);
    $properties['interval'] = DataDefinition::create('float')
      ->setLabel(t('Interval (seconds)'))
      ->setRequired(TRUE);
    $properties['column'] = DataDefinition::create('string')
      ->setLabel(t('How to show the numbers?'))
      ->addConstraint('AllowedValues', ['single', 'column', 'line'])
      ->setRequired(TRUE);
    $properties['keep'] = DataDefinition::create('boolean')
      ->setLabel(t('Keep or hide line or column after last number?'));
    $properties['random_location'] = DataDefinition::create('boolean')
      ->setLabel(t('Random location'));
    return $properties;
  }

  /**
   * @inheritDoc
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        /*'display_settings' => [
          'description' => "Serialized display settings",
          'type' => 'blob',
          'serialize' => TRUE,
        ],*/
        'font_size' => [
          'description' => 'Font size',
          'type' => 'int',
          'unsigned' => TRUE,
          'not null' => TRUE,
          'default' => 20,
        ],
        'interval' => [
          'description' => 'Time interval in seconds',
          'type' => 'numeric',
          'precision' => 3,
          'scale' => 1,
          'unsigned' => TRUE,
          'not null' => TRUE,
          'default' => 1,
        ],
        'column' => [
          'description' => 'How to show the numbers (column, line, single)',
          'type' => 'varchar',
          'length' => 6,
          'not null' => TRUE,
          'default' => 'single',
         ],
        'keep' => [
          'description' => 'Whether to keep display after last item was shown',
          'type' => 'int',
          'size' => 'tiny',
          'unsigned' => TRUE,
        ],
        'random_location' => [
          'description' => 'Random vs fixed location',
          'type' => 'int',
          'size' => 'tiny',
          'unsigned' => TRUE,
        ],
      ],
      'indexes' => [
        'font_size' => ['font_size'],
        'interval' => ['interval'],
        'other' => ['keep', 'random_location'],
      ],
    ];
    return $schema;
  }

}