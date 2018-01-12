<?php

namespace Drupal\ejikznayka;


use Drupal\Core\Entity\BundleEntityFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\EntityTypeInterface;

class LessonTypeForm extends BundleEntityFormBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $lesson_type = $this->entity;
    if ($lesson_type->isNew()) {
      $form['#title'] = $this->t('Add lesson type');
    }
    else {
      $form['#title'] = $this->t('Edit lesson type');
    }

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#default_value' => $lesson_type->label(),
      '#maxlength' => 255,
      '#required' => TRUE,
    ];
    $form['ejtid'] = [
      '#type' => 'machine_name',
      '#default_value' => $lesson_type->id(),
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#machine_name' => [
        'exists' => FALSE, //[$this, 'exists'],
        //'source' => ['name'],
      ],
    ];
    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#default_value' => $lesson_type->getDescription(),
    ];
    $form = parent::form($form, $form_state);
    return $this->protectBundleIdElement($form);
  }

  /**
   * Determines if the lesson type already exists.
   *
   * @param string $id
   *   The lesson type ID.
   *
   * @return bool
   *   TRUE if the vocabulary exists, FALSE otherwise.
   */
  public function exists($id) {
//    $action = $this->vocabularyStorage->load($id);
//    return !empty($action);
    return FALSE;
  }
}