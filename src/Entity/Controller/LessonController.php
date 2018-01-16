<?php

namespace Drupal\ejikznayka\Entity\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\ejikznayka\Entity\LessonType;

/**
 * Provides route responses for Lesson entity.
 */
class LessonController extends ControllerBase {

  /**
   * Returns a form to add a new lesson.
   *
   * @param \Drupal\ejikznayka\Entity\LessonType $ejikznayka_lesson_type
   *   The lesson type (bundle) this lesson will be added to.
   *
   * @return array
   *   The lesson add form.
   */
  public function addForm(LessonType $ejikznayka_lesson_type) {
    $term = $this->entityManager()->getStorage('ejikznayka_lesson')->create(['ejtid' => $ejikznayka_lesson_type->id()]);
    return $this->entityFormBuilder()->getForm($term);
  }

}