<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ejikznayka_scenario_default' widget.
 *
 * @FieldWidget(
 *   id = "ejikznayka_scenario_default",
 *   label = @Translation("Scenario"),
 *   field_types = {
 *     "ejikznayka_scenario"
 *   }
 * )
 */
class ScenarioDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['count'] = [
      '#type' => 'number',
      '#title' => $this->fieldDefinition->getFieldStorageDefinition()->getPropertyDefinition('count')->getLabel(),
      '#size' => 3,
      '#min' => 1,
      //'#max' => 50,
      '#default_value' => $items[$delta]->count ?: 1,
      //  '#default_value' => $this->getSetting('count'),
      '#required' => TRUE,
    ];
    $element['range'] = [
      '#type' => 'fieldgroup',
      '#title' => $this->t('Range of numbers'),
      'min' => [
        '#type' => 'number',
        '#title' => $this->t('From'),
        '#min' => 0,
        //'#max' => 999999,
        '#default_value' => $items[$delta]->min ?: 1,
      ],
      'max' => [
        '#type' => 'number',
        '#title' => $this->t('To', array(), array('context' => 'Range')),
        '#min' => 0,
        //'#max' => 999999,
        '#default_value' => $items[$delta]->max ?: 1,
      ],
    ];
    $element['minus'] = [
      '#type' => 'checkbox',
      '#title' => $this->fieldDefinition->getFieldStorageDefinition()->getPropertyDefinition('minus')->getLabel(),
      '#default_value' => $items[$delta]->minus,
    ];
    /** @var \Drupal\ejikznayka\TypedData\DisplaySettingsDataDefinition $display_definition */
    $display_definition = $this->fieldDefinition->getFieldStorageDefinition()->getPropertyDefinition('display_settings');
    $element['display_settings'] = [
      '#type' => 'fieldgroup',
      '#title' => $display_definition->getLabel(),
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
      ],
      'random_location' => [
        '#type' => 'checkbox',
        '#title' => $display_definition->getPropertyDefinition('random_location')->getLabel(),
        '#description' => $this->t("Doesn't have any effect if numbers are displayed in column"),
        '#default_value' => $items[$delta]->get('display_settings')->get('random_location')->getValue(),
      ],
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $item_values) {
      $sequence = $positions = [];
      $sum = 0;
      for ($i = 0; $i < $item_values['count']; $i++) {
        if ($item_values['minus'] && $sum > $item_values['range']['min'] && mt_rand(1, 2) == 1) {
          $sequence[$i] = -mt_rand($item_values['range']['min'], min($sum, $item_values['range']['max']));
        }
        else {
          $sequence[$i] = mt_rand($item_values['range']['min'], $item_values['range']['max']);
        }
        $sum += $sequence[$i];
        // Generate random position.
        $position = [];
        $top = mt_rand(0, 50);
        $left = mt_rand(0, 50);
        if (mt_rand(1, 2) == 1) {
          $position['top'] = $top . '%';
          $position['bottom'] = '';
        }
        else {
          $position['bottom'] = $top . '%';
          $position['top'] = '';
        }
        if (mt_rand(1, 2) == 1) {
          $position['left'] = $left . '%';
          $position['right'] = '';
        }
        else {
          $position['right'] = $left . '%';
          $position['left'] = '';
        }
        $positions[] = $position;
      }

      $return_values[$delta]['sequence'] = $sequence;
      $return_values[$delta]['positions'] = $positions;
      $return_values[$delta]['minus'] = (bool) $item_values['minus'];
      $return_values[$delta] += $item_values['range'];
      $return_values[$delta]['count'] = $item_values['count'];
      $return_values[$delta]['display_settings'] = $item_values['display_settings'];
    }
    return $return_values;
  }

}
