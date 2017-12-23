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
    $element['sequence'] = $element + [
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
    }
    return $element;
  }

}
