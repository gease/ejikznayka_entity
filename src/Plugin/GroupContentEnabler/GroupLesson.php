<?php

namespace Drupal\ejikznayka\Plugin\GroupContentEnabler;

use Drupal\group\Plugin\GroupContentEnablerBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\group\Entity\GroupInterface;
use Drupal\Core\Url;
use Drupal\ejikznayka\Entity\LessonType;

/**
 * Provides a content enabler for lessons.
 *
 * @GroupContentEnabler(
 *   id = "group_lesson",
 *   label = @Translation("Group lesson"),
 *   description = @Translation("Adds lessons to groups."),
 *   entity_type_id = "ejikznayka_lesson",
 *   entity_access = TRUE,
 *   pretty_path_key = "lesson",
 *   reference_label = @Translation("Lesson"),
 *   reference_description = @Translation("The name of lesson you want to add to group."),
 *   deriver = "Drupal\ejikznayka\Plugin\GroupContentEnabler\GroupLessonDeriver",
 * )
 */
class GroupLesson extends GroupContentEnablerBase {

  /**
   * Retrieves the lesson type this plugin supports.
   *
   * @return \Drupal\ejikznayka\Entity\LessonType
   *   The lesson type this plugin supports.
   */
  protected function getLessonType() {
    return LessonType::load($this->getEntityBundle());
  }

  /**
   * {@inheritdoc}
   */
  public function getGroupOperations(GroupInterface $group) {
    $account = \Drupal::currentUser();
    $plugin_id = $this->getPluginId();
    $type = $this->getEntityBundle();
    $operations = [];

    if ($group->hasPermission("create $plugin_id entity", $account)) {
      $route_params = ['group' => $group->id(), 'plugin_id' => $plugin_id];
      $operations["lesson-create-$type"] = [
        'title' => $this->t('Create @type', ['@type' => $this->getLessonType()->label()]),
        'url' => new Url('entity.group_content.create_form', $route_params),
        'weight' => 30,
      ];
    }

    return $operations;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $config = parent::defaultConfiguration();
    $config['entity_cardinality'] = 1;
    return $config;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // Disable the entity cardinality field as the functionality of this module
    // relies on a cardinality of 1. We don't just hide it, though, to keep a UI
    // that's consistent with other content enabler plugins.
    $info = $this->t("This field has been disabled by the plugin to guarantee the functionality that's expected of it.");
    $form['entity_cardinality']['#disabled'] = TRUE;
    $form['entity_cardinality']['#description'] .= '<br /><em>' . $info . '</em>';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    $dependencies = parent::calculateDependencies();
    $dependencies['config'][] = 'ejikznayka.lesson.' . $this->getEntityBundle();
    return $dependencies;
  }

}