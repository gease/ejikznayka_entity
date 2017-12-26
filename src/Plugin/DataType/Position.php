<?php

namespace Drupal\ejikznayka\Plugin\DataType;


use Drupal\Core\TypedData\Plugin\DataType\Map;

/**
 * @DataType(
 *  id = "ejikznayka_position",
 *  label = @Translation("Position"),
 *  constraints = {},
 *  definition_class = "\Drupal\ejikznayka\TypedData\PositionDataDefinition"
 * )
 */
class Position extends Map {

}