<?php

namespace Drupal\ejikznayka\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for checking if sequence and settings of scenario match.
 *
 * @Constraint(
 *   id = "Scenario",
 *   label = @Translation("Scenario"),
 *   type = { "field:scenario" }
 * )
 */
class ScenarioConstraint extends Constraint {

}