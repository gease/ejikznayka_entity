<?php

namespace Drupal\ejikznayka\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldFilteredMarkup;

/**
 * Plugin implementation of the 'ejikznayka_scenario_default_value' widget
 * for setting defaults on scenario field edit form.
 *
 * @FieldWidget(
 *   id = "ejikznayka_scenario_default_value",
 *   label = @Translation("Scenario Defaults"),
 *   field_types = {
 *     "ejikznayka_scenario"
 *   }
 * )
 */
class ScenarioDefaultValueWidget extends WidgetBase {

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
    $element['minus'] = [
      '#type' => 'checkbox',
      '#title' => $this->fieldDefinition->getFieldStorageDefinition()->getPropertyDefinition('minus')->getLabel(),
      '#default_value' => $items[$delta]->minus,
    ];
    $element['min'] = [
      '#type' => 'number',
      '#title' => $this->t('From'),
      '#min' => 0,
      //'#max' => 999999,
      '#default_value' => $items[$delta]->min ?: 1,
      '#attributes' => [
        'class' => ['ejikznayka-min'],
      ],
    ];
    $element['max'] = [
      '#type' => 'number',
      '#title' => $this->t('To', [], ['context' => 'Range']),
      '#min' => 0,
      //'#max' => 999999,
      '#default_value' => $items[$delta]->max ?: 1,
      '#attributes' => [
        'class' => ['ejikznayka-max'],
      ],
    ];
    return $element;
  }

  /**
   * Overrides \Drupal\Core\Field\WidgetBase::formMultipleElements().
   *
   * For defaults, we just have a single widget even for multiple cardinality fields.
   * Created after \Drupal\file\Plugin\Field\FieldWidget\FileWidget::formMultipleElements().
   *
   * @see \Drupal\file\Plugin\Field\FieldWidget\FileWidget::formMultipleElements()
   */
  protected function formMultipleElements(FieldItemListInterface $items, array &$form, FormStateInterface $form_state) {
    $field_name = $this->fieldDefinition->getName();
    $parents = $form['#parents'];
    $cardinality = $this->fieldDefinition->getFieldStorageDefinition()->getCardinality();
    $max = $cardinality > 1 ? $cardinality : 1;

    $title = $this->fieldDefinition->getLabel();
    $description = FieldFilteredMarkup::create(\Drupal::token()->replace($this->fieldDefinition->getDescription()));

    $elements = [];



    for ($delta = 0; $delta < $max; $delta++) {
      if (!isset($items[$delta])) {
        $items->appendItem();
      }
      // For multiple fields, title and description are handled by the wrapping
      // table.
      if ($max > 1) {
        $element = [
          '#title' => $this->t('@title (value @number)', ['@title' => $title, '@number' => $delta + 1]),
          '#title_display' => 'before',
          '#description' => '',
        ];
      }
      else {
        $element = [
          '#title' => $title,
          '#title_display' => 'before',
          '#description' => $description,
        ];
      }

      $element = $this->formSingleElement($items, $delta, $element, $form, $form_state);

      if ($element) {
        // Input field for the delta (drag-n-drop reordering).
        if ($max > 1) {
          // We name the element '_weight' to avoid clashing with elements
          // defined by widget.
          $element['_weight'] = [
            '#type' => 'weight',
            '#title' => $this->t('Weight for row @number', ['@number' => $delta + 1]),
            '#title_display' => 'invisible',
            // Note: this 'delta' is the FAPI #type 'weight' element's property.
            '#delta' => count($items),
            '#default_value' => $items[$delta]->_weight ?: $delta,
            '#weight' => 100,
          ];
        }

        $elements[$delta] = $element;
      }
    }

    $elements += [
      '#theme' => 'field_multiple_value_form',
      '#field_name' => $field_name,
      '#cardinality' => $cardinality,
      '#cardinality_multiple' => $this->fieldDefinition->getFieldStorageDefinition()
        ->isMultiple(),
      '#required' => $this->fieldDefinition->isRequired(),
      '#title' => $title,
      '#description' => $description,
      '#max_delta' => count($items) - 1,
    ];

    return $elements;
  }


  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $item_values) {
      $return_values[$delta]['minus'] = (bool) $item_values['minus'];
      $return_values[$delta]['max'] = $item_values['max'];
      $return_values[$delta]['min'] = $item_values['min'];
      $return_values[$delta]['count'] = $item_values['count'];
    }
    return $return_values;
  }

}
