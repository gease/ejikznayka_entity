<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\Plugin\DataType\ItemList;

/**
 * Plugin implementation of the 'ejikznayka_scenario_default' formatter.
 *
 * @FieldFormatter(
 *   id = "ejikznayka_scenario_default",
 *   label = @Translation("Scenario"),
 *   field_types = {
 *     "ejikznayka_scenario"
 *   }
 * )
 */
class ScenarioDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    /** @var ItemList $item */
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'markup',
        '#title' => $this->t('Sequence'),
        '#markup' => $item->getString(),
      ];
    }

    return $elements;
  }
}