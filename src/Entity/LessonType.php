<?php

namespace Drupal\ejikznayka\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the lesson type entity.
 *
 * @ConfigEntityType(
 *   id = "ejikznayka_lesson_type",
 *   label = @Translation("Lesson type"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Config\Entity\ConfigEntityStorage",
 *     "list_builder" = "Drupal\ejikznayka\LessonTypeListBuilder",
 *     "form" = {
 *       "default" = "Drupal\ejikznayka\LessonTypeForm",
 *       "delete" = "Drupal\ejikznayka\Form\LessonTypeDeleteForm"
 *     }
 *   },
 *   admin_permission = "administer ejikznayka",
 *   config_prefix = "lesson",
 *   bundle_of = "ejikznayka_lesson",
 *   translatable = FALSE,
 *   entity_keys = {
 *     "id" = "ejtid",
 *     "label" = "name",
 *   },
 *   links = {
 *     "delete-form" = "/admin/structure/ejikznayka/manage/{ejikznayka_lesson_type}/delete",
 *     "edit-form" = "/admin/structure/ejikznayka/manage/{ejikznayka_lesson_type}",
 *     "collection" = "/admin/structure/ejikznayka",
 *   },
 *   config_export = {
 *     "name",
 *     "ejtid",
 *     "description",
 *   }
 * )
 */
class LessonType extends ConfigEntityBundleBase {

  /**
   * The lesson type ID.
   *
   * @var string
   */
  protected $ejtid;

  /**
   * Name of the lesson type.
   *
   * @var string
   */
  protected $name;

  /**
   * Description of the lesson type.
   *
   * @var string
   */
  protected $description;

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    return $this->description;
  }

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->ejtid;
  }

}