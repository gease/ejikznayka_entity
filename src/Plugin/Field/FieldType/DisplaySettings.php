<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\ejikznayka\TypedData\DisplaySettingsDataDefinition;

/**
 * Defines display settings for the lesson entity.
 *
 * @FieldType(
 *   id = "ejikznayka_display_settings",
 *   label = @Translation("Display Settings"),
 *   description = @Translation("An entity field defining lesson display settings."),
 *   no_ui = FALSE,
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
    $properties['display_settings'] = DisplaySettingsDataDefinition::create('ejikznayka_display_settings')
      ->setLabel(t('Display settings'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * @inheritDoc
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'display_settings' => [
        'description' => "Serialized display settings",
        'type' => 'blob',
        'serialize' => TRUE,
      ],
    ];
    return $schema;
  }

}