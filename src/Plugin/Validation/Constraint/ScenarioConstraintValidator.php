<?php

namespace Drupal\ejikznayka\Plugin\Validation\Constraint;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;
use Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem;

class ScenarioConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    if (!($constraint instanceof ScenarioConstraint) || !($value instanceof ScenarioItem)) {
      throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\ScenarioConstraint');
    }
    if (!($value instanceof ScenarioItem)) {
      throw new UnexpectedTypeException($value, '\Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem');
    }

    $violation = $this->validateMinMax($value, $constraint);
    if ($violation instanceof ConstraintViolationBuilderInterface) {
      $violation->atPath('sequence')->addViolation();
      return;
    }

    /* @var ScenarioItem $value */
    if ($value->getFieldDefinition()->getSetting('store')) {
      $violation = $this->validateRange($value, $constraint);
      if ($violation instanceof ConstraintViolationBuilderInterface) {
        $violation->atPath('sequence')->addViolation();
        return;
      }
      $violation = $this->validateLength($value, $constraint);
      if ($violation instanceof ConstraintViolationBuilderInterface) {
        $violation->atPath('count')->addViolation();
        return;
      }
      if ($value->minus) {
        $violation = $this->checkAlwaysPositive($value, $constraint);
      }
      else {
        $violation = $this->checkAllPositive($value, $constraint);
      }
      if ($violation instanceof ConstraintViolationBuilderInterface) {
        $violation->atPath('sequence')->addViolation();
        return;
      }
    }
  }

  /**
   * Check if limits are correct.
   *
   * @param \Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem $value
   * @param \Symfony\Component\Validator\Constraint $constraint*
   */
  private function validateMinMax($value, Constraint $constraint) {
    if ($value->min > $value->max) {
      return $this->context->buildViolation('Upper limit of range is lower than lower limit.');
    }
    if ($value->min < 0) {
      return $this->context->buildViolation('Limits of range should be positive');
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
      if ($abs < $value->min || $abs > $value->max) {
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

  /**
   * Check if length of the sequence corresponds to the count parameter.
   *
   * @param \Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem $value
   * @param \Symfony\Component\Validator\Constraint $constraint
   */
  private function validateLength($value, Constraint $constraint) {
    if (count($value->sequence) != $value->count) {
      return $this->context->buildViolation('Number of numbers is different than count value');
    }
  }

  /**
   * Check if sum is always positive through scenario.
   *
   * @param \Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem $values
   * @param \Symfony\Component\Validator\Constraint $constraint
   */
  private function checkAlwaysPositive($values, Constraint $constraint) {
    $res = 0;
    foreach ($values->sequence as $sequence_item) {
      $res += $sequence_item;
      if ($res < 0) {
        return $this->context->buildViolation('Sum should be always positive');
      }
    }
  }

  /**
   * Check if all numbers are positive.
   *
   * @param \Drupal\ejikznayka\Plugin\Field\FieldType\ScenarioItem $values
   * @param \Symfony\Component\Validator\Constraint $constraint
   */
  private function checkAllPositive($values, Constraint $constraint) {
    foreach ($values->sequence as $sequence_item) {
      if ($sequence_item < 0) {
        return $this->context->buildViolation('All numbers must be positive');
      }
    }
  }

}