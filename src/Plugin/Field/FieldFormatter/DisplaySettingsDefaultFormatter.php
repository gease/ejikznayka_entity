<?php
/**
 * Created by PhpStorm.
 * User: gease
 * Date: 01/03/18
 * Time: 18:35
 */

namespace Drupal\ejikznayka\Plugin\Field\FieldFormatter;


use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\TypedData\Plugin\DataType\ItemList;

/**
 * Plugin implementation of the 'ejikznayka_display_settings_default' formatter.
 *
 * @FieldFormatter(
 *   id = "ejikznayka_display_settings_default",
 *   label = @Translation("View display settings"),
 *   field_types = {
 *     "ejikznayka_display_settings"
 *   }
 * )
 */
class DisplaySettingsDefaultFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    /** @var ItemList $item */
    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'markup',
        '#title' => $this->t('Display settings'),
        '#markup' => $item->getString(),
      ];
    }

    return $elements;
  }

}