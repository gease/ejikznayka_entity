<?php

namespace Drupal\ejikznayka\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class OverviewLessons extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ejikznayka_overview_lessons';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // TODO: Implement buildForm() method.
    return [
      '#title' => 'placeholder',
      '#markup' => 'placeholder',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}