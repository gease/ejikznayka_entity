<?php

namespace Drupal\ejikznayka\Routing;

use Drupal\ejikznayka\Entity\LessonType;
use Symfony\Component\Routing\Route;

/**
 * Provides routes for group_lesson group content.
 */
class GroupLessonRouteProvider {

  /**
   * Provides the shared collection route for group node plugins.
   */
  public function getRoutes() {
    $routes = $plugin_ids = $permissions_add = $permissions_create = [];

    foreach (LessonType::loadMultiple() as $name => $lesson_type) {
      $plugin_id = "group_lesson:$name";

      $plugin_ids[] = $plugin_id;
      $permissions_add[] = "create $plugin_id content";
      $permissions_create[] = "create $plugin_id entity";
    }

    // If there are no lesson types yet, we can't have any plugin IDs and should
    // therefore exit early because we cannot have any routes for them either.
    if (empty($plugin_ids)) {
      return $routes;
    }

    $routes['entity.group_content.group_lesson_relate_page'] = new Route('group/{group}/lesson/add');
    $routes['entity.group_content.group_lesson_relate_page']
      ->setDefaults([
        '_title' => 'Relate lesson',
        '_controller' => '\Drupal\ejikznayka\Controller\GroupLessonController::addPage',
      ])
      ->setRequirement('_group_permission', implode('+', $permissions_add))
      ->setRequirement('_group_installed_content', implode('+', $plugin_ids))
      ->setOption('_group_operation_route', TRUE);

    $routes['entity.group_content.group_lesson_add_page'] = new Route('group/{group}/lesson/create');
    $routes['entity.group_content.group_lesson_add_page']
      ->setDefaults([
        '_title' => 'Create lesson',
        '_controller' => '\Drupal\ejikznayka\Controller\GroupLessonController::addPage',
        'create_mode' => TRUE,
      ])
      ->setRequirement('_group_permission', implode('+', $permissions_create))
      ->setRequirement('_group_installed_content', implode('+', $plugin_ids))
      ->setOption('_group_operation_route', TRUE);

    return $routes;
  }
}