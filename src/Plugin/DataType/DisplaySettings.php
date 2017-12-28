<?php

namespace Drupal\ejikznayka\Plugin\DataType;

use Drupal\Core\TypedData\Plugin\DataType\Map;

/**
 * @DataType(
 *  id = "ejikznayka_display_settings",
 *  label = @Translation("DisplaySettings"),
 *  constraints = {},
 *  definition_class = "\Drupal\ejikznayka\TypedData\DisplaySettingsDataDefinition"
 * )
 */
class DisplaySettings extends Map {

}