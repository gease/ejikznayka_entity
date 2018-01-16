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

  /**
   * Displays add content links for available lesson types.
   *
   * Redirects to ejikznayka_lesson/add/[type] if only one content type is available.
   *
   * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
   *   A render array for a list of the node types that can be added; however,
   *   if there is only one lesson type defined for the site, the function
   *   will return a RedirectResponse to the lesson add page for that one lesson
   *   type.
   *
   * @see \Drupal\node\Controller\NodeController::addPage()
   */
  public function addPage() {
    $build = [
      '#theme' => 'ejikznayka_lesson_add_list',
    ];

    $content = [];

    // Only use node types the user has access to.
    foreach ($this->entityManager()->getStorage('ejikznayka_lesson_type')->loadMultiple() as $type) {
      $content[$type->id()] = $type;
    }

    // Bypass the node/add listing if only one content type is available.
    if (count($content) == 1) {
      $type = array_shift($content);
      return $this->redirect('entity.ejikznayka_lesson.add_form', ['ejikznayka_lesson_type' => $type->id()]);
    }

    $build['#content'] = $content;

    return $build;
  }

}
