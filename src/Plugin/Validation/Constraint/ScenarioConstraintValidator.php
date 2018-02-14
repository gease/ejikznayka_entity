<?php

namespace Drupal\ejikznayka\Plugin\Validation\Constraint;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem;

class ScenarioConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    $violation = $this->validateRange($value, $constraint);
    if ($violation instanceof ConstraintViolationBuilderInterface) {
      $violation->atPath('sequence')->addViolation();
      return;
    }
  }

  /**
   * Check if all sequence values are within given range.
   *
   * @param \Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem $value
   * @param \Symfony\Component\Validator\Constraint $constraint
   */
  private function validateRange($value, Constraint $constraint) {
    $violations = [];
    foreach ($value->sequence as $sequence_item) {
      $abs = abs($sequence_item);
      if ($abs <= $value->min || $abs >= $value->max) {
        $violations[] = $sequence_item;
      }
    }
    if (empty($violations)) {
      return;
    }
    else {
      return $this->context->buildViolation('Values <em>%value</em> is out of limits',
        ['%value' => implode(',', $violations)]);
    }
  }

}