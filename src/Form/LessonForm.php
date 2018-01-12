<?php

namespace Drupal\ejikznayka\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Language\Language;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the ejikznayka_lesson entity edit forms.
 *
 * @ingroup ejikznayka
 */

class LessonForm extends ContentEntityForm{

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\content_entity_example\Entity\Lesson */
    $form = parent::buildForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);

    $entity = $this->entity;
    if ($status == SAVED_UPDATED) {
      drupal_set_message($this->t('The lesson %feed has been updated.', ['%feed' => $entity->toLink()->toString()]));
    }
    else {
      drupal_set_message($this->t('The lesson %feed has been added.', ['%feed' => $entity->toLink()->toString()]));
    }

    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $status;
  }

}