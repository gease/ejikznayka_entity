<?php
/**
 * Created by PhpStorm.
 * User: gease
 * Date: 01/03/18
 * Time: 16:55
 */

namespace Drupal\ejikznayka\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ejikznayka_display_setings_default' widget.
 *
 * @FieldWidget(
 *   id = "ejikznayka_display_settings_default",
 *   label = @Translation("Display settings"),
 *   field_types = {
 *     "ejikznayka_display_settings"
 *   }
 * )
 */
class DisplaySettingsDefaultWidget extends WidgetBase{

  /**
   * @inheritDoc
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\ejikznayka\TypedData\DisplaySettingsDataDefinition $display_definition */
    $display_definition = $this->fieldDefinition->getFieldStorageDefinition()->getPropertyDefinition('display_settings');
    $element = [
      'interval' => [
        '#type' => 'number',
        '#title' => $display_definition->getPropertyDefinition('interval')->getLabel(),
        '#size' => 3,
        '#step' => 0.1,
        '#min' => 0.1,
        '#max' => 5,
        '#default_value' => $items[$delta]->get('display_settings')->get('interval')->getValue(),
        '#required' => TRUE,
      ],
      'font_size' => [
        '#type' => 'number',
        '#title' => $display_definition->getPropertyDefinition('font_size')->getLabel(),
        '#step' => 4,
        '#min' => 20,
        '#max' => 100,
        '#default_value' => $items[$delta]->get('display_settings')->get('font_size')->getValue(),
        '#required' => TRUE,
      ],
      'column' => [
        '#type' => 'radios',
        '#title' => $display_definition->getPropertyDefinition('column')->getLabel(),
        '#options' => [
          'single' => $this->t('By one'),
          'column' => $this->t('In column'),
          'line' => $this->t('In line'),
        ],
        '#default_value' => $items[$delta]->get('display_settings')->get('column')->getValue(),
        '#required' => TRUE,
      ],
      'keep' => [
        '#type' => 'checkbox',
        '#title' => $display_definition->getPropertyDefinition('keep')->getLabel(),
        '#description' => $this->t("Doesn't have any effect if numbers are displayed by one"),
        '#default_value' => $items[$delta]->get('display_settings')->get('keep')->getValue(),
        '#states' => [
          'invisible' => [
            ':input[name="' . $items->getName() . '[' . $delta . '][column]"]' => ['value' => 'single'],
          ],
        ],
      ],
      'random_location' => [
        '#type' => 'checkbox',
        '#title' => $display_definition->getPropertyDefinition('random_location')->getLabel(),
        '#description' => $this->t("Doesn't have any effect if numbers are displayed in column"),
        '#default_value' => $items[$delta]->get('display_settings')->get('random_location')->getValue(),
        '#states' => [
          'visible' => [
            ':input[name="' . $items->getName() . '[' . $delta . '][column]"]' => ['value' => 'single'],
          ],
        ],
      ],
    ];
    return $element;
  }

}