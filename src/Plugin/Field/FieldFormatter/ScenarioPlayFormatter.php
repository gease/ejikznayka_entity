<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'ejikznayka_scenario_play' formatter.
 *
 * @FieldFormatter(
 *   id = "ejikznayka_scenario_play",
 *   label = @Translation("Play scenario"),
 *   field_types = {
 *     "ejikznayka_scenario"
 *   }
 * )
 */
class ScenarioPlayFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $config = \Drupal::config('ejikznayka.arithmetics')->get();
    $js_config = [
      'count' => $config['count'],
      'interval' => $config['interval'],
      'minus' => $config['minus'],
      'keep' => ($config['column'] == 'single' ? FALSE : $config['keep']),
      'random_location' => $config['random_location'],
      'mark' => $config['mark'],
      'column' => $config['column'],
      'font_size' => $config['font_size'],
    ];
    if (!empty($config['digits'])) {
      $js_config['digits'] = $config['digits'];
    }
    else {
      $js_config['range'] = $config['range'];
      $js_config['sequence'] = $config['sequence'];
    }

    /** @var \Drupal\Core\TypedData\Plugin\DataType\ItemList $items */
    foreach ($items as $delta => $item) {
      $js_config['data']['sequence'] = $item->sequence;
      $js_config['data']['positions'] = $item->positions;
      $elements[$delta] = [
        '#theme' => 'ejikznayka_arithmetics',
        '#attached' => [
          'drupalSettings' => [
            'ejikznayka' => [
              'arithmetics' => $js_config,
            ],
          ],
        ],
      ];
    }

    return $elements;
  }

}