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
    /*$element['sequence'] = $element + [
      '#type' => 'fieldset',
      '#title' => $this->t('Sequence'),
        '#default_value' => isset($items[$delta]->sequence) ? $items[$delta]->sequence : NULL,
      //'#placeholder' => $this->getSetting('placeholder'),
      //'#size' => $this->getSetting('size'),
      //'#maxlength' => Email::EMAIL_MAX_LENGTH,
    ];
    for ($i = 0; $i < 3; $i++) {
      $element['sequence'][$i] = [
        '#type' => 'number',
        '#max' => 10,
        '#min' => 1,
        '#title' => $i,
        '#default_value' => $items[$delta]->sequence[$i],
      ];
    }*/
    $element['count'] = $element + [
      '#type' => 'number',
      '#title' => $this->t('Number of numbers'),
      '#size' => 3,
      '#min' => 1,
      '#max' => 50,
      '#default_value' => is_array($items[$delta]->sequence) ? count($items[$delta]->sequence) : 1,
      //  '#default_value' => $this->getSetting('count'),
      '#required' => TRUE,
    ];
    return $element;
  }

  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    $return_values = [];
    foreach ($values as $delta => $item_values) {
      $sequence = $positions = [];
      for ($i = 0; $i < $item_values['count']; $i++) {
        $sequence[$i] = mt_rand(1, 100);
        // Generate random position.
        $position = [];
        $top = mt_rand(0, 50);
        $left = mt_rand(0, 50);
        if (mt_rand(1, 2) == 1) {
          $position['top'] = $top;
        }
        else {
          $position['bottom'] = $top;
        }
        if (mt_rand(1, 2) == 1) {
          $position['left'] = $left;
        }
        else {
          $position['right'] = $left;
        }
        $positions[] = $position;
      }

      $return_values[$delta]['sequence'] = $sequence;
      $return_values[$delta]['positions'] = $positions;
      $return_values[$delta]['count'] = $item_values['count'];
    }
    return $return_values;
  }

}
