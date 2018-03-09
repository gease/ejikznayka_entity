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

  protected $ajaxWrapperId;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->ajaxWrapperId = Html::getUniqueId($field_definition->getName() . '-add-more-wrapper');
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title for the task'),
      '#description' => $this->t('Title that will appear as a header for the task.'),
      '#required' => TRUE,
      '#default_value' => $items[$delta]->title,
    ];
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
    if ($this->fieldDefinition->getSetting('store')) {
      $element['sequence'] = [
        '#type' => 'textfield',
        '#title' => t('Sequence'),
        '#default_value' => $items[$delta]->sequence ? implode(',', $items[$delta]->sequence) : '',
        '#required' => TRUE,
      ];
      $element['generate'] = [
        '#type' => 'html_tag',
        '#tag' => 'input',
        '#attributes' => [
          'type' => 'button',
          'value' => t('Generate sequence'),
          'class' => 'button',
          'name' => 'generate',
          'data-ejikznayka-target' => Html::getClass(implode('-', [
            'form-item',
            $items->getName(),
            $delta,
          ])),
        ],
        '#attached' => [
          'library' => [
            'ejikznayka/ejikznayka',
          ],
        ],
      ];
    }
    if ($this->fieldDefinition->getFieldStorageDefinition()->getCardinality() == FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED) {
      $element['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#name' => $items->getName() . '_' . $delta . '_remove_button',
        '#ajax' => [
          // We cannot set callbacks with '::removeAjax' notation, because they
          // are resolved in context of entity form class.
          'callback' => [get_class($this), 'removeAjax'],
          'wrapper' => $this->getAjaxWrapperId($form),
        ],
        // We don't perform any validation when removing an element.
        '#limit_validation_errors' => [],
        '#submit' => [[get_class($this), 'removeSubmit']],
      ];
    }
    return $element;
  }

  /**
   * Overrides \Drupal\Core\Field\WidgetBase::formMultipleElements().
   *
   * We need to handle differently adding and removing single widgets.
   * Created after \Drupal\file\Plugin\Field\FieldWidget\FileWidget::formMultipleElements().
   *
   * @see \Drupal\file\Plugin\Field\FieldWidget\FileWidget::formMultipleElements()
   */
  protected function formMultipleElements(FieldItemListInterface $items, array &$form, FormStateInterface $form_state) {
    $field_name = $this->fieldDefinition->getName();
    $parents = $form['#parents'];
    $cardinality = $this->fieldDefinition->getFieldStorageDefinition()->getCardinality();
    // If form was submitted with ajax, we store updated $items array in WidgetState.
    $field_state = static::getWidgetState($parents, $field_name, $form_state);
    if (isset($field_state['items'])) {
      $items->setValue($field_state['items']);
    }
    // Here, we should have correct number of items both for unlimited
    // and limited cardinality fields.
    // We don't add any empty fields beforehand.
    // We do this only with ajax "add more" button.
    $title = $this->fieldDefinition->getLabel();
    $description = FieldFilteredMarkup::create(\Drupal::token()->replace($this->fieldDefinition->getDescription()));
    $is_multiple = ($cardinality == FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED) || (count($items) > 1);
    // We add or remove items via $field_state.
    $max = $field_state['items_count'];
    $elements = [];

    for ($delta = 0; $delta < $max; $delta++) {

      if (!isset($items[$delta])) {
        $items->appendItem();
        $items[$delta]->applyDefaultValue(TRUE);
      }

      // For multiple fields, title and description are handled by the wrapping
      // table.
      if ($is_multiple) {
        $element = [
          '#title' => $this->t('@title (value @number)', ['@title' => $title, '@number' => $delta + 1]),
          '#title_display' => 'invisible',
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
        if ($is_multiple) {
          // We name the element '_weight' to avoid clashing with elements
          // defined by widget.
          $element['_weight'] = [
            '#type' => 'weight',
            '#title' => $this->t('Weight for row @number', ['@number' => $delta + 1]),
            '#title_display' => 'invisible',
            // Note: this 'delta' is the FAPI #type 'weight' element's property.
            '#delta' => $max,
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
      '#max_delta' => $max - 1,
    ];

    // Add 'add more' button, if not working with a programmed form.
    if ($cardinality == FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED && !$form_state->isProgrammed()) {
      $id_prefix = implode('-', array_merge($parents, [$field_name]));
      $elements['#prefix'] = '<div id="' . $this->getAjaxWrapperId($form) . '">';
      $elements['#suffix'] = '</div>';

      $elements['add_more'] = [
        '#type' => 'submit',
        '#name' => strtr($id_prefix, '-', '_') . '_add_more',
        '#value' => t('Add another item'),
        '#attributes' => ['class' => ['field-add-more-submit']],
        '#limit_validation_errors' => [array_merge($parents, [$field_name])],
        '#submit' => [[get_class($this), 'addMoreSubmit']],
        '#ajax' => [
          'callback' => [get_class($this), 'addMoreAjax'],
          'wrapper' => $this->getAjaxWrapperId($form),
          'effect' => 'fade',
        ],
      ];
    }
    return $elements;
  }

  /**
   * Ajax callback for remove button on individual form item.
   */
  public static function removeAjax(array $form, FormStateInterface $form_state) {
    $parents = $form_state->getTriggeringElement()['#array_parents'];
    $parents = array_slice($parents, 0, -3);
    $element = NestedArray::getValue($form, $parents);
    return $element;
  }

  /**
   * Submit callback for ajax remove button on individual form item.
   *
   * Created after FileWidget submit handler.
   *
   * @see \Drupal\file\Plugin\Field\FieldWidget\FileWidget::submit()
   */
  public static function removeSubmit($form, FormStateInterface $form_state) {
    $button = $form_state->getTriggeringElement();
    $parents = array_slice($button['#parents'], 0, -1);
    // See FileWidget::submit() for explanation.
//    NestedArray::setValue($form_state->getUserInput(), $parents, NULL);
//    $submitted_values = NestedArray::getValue($form_state->getValues(), $parents);
    $delta = $button['#parents'][count($button['#parents']) - 2];
    NestedArray::unsetValue($form_state->getUserInput(), $parents);
    $parents = array_slice($button['#parents'], 0, -2);
    // Get field_name and field_parents to use further to set WidgetState.
    $element = NestedArray::getValue($form, array_slice($button['#array_parents'], 0, -2));
    $field_name = $element['#field_name'];
    $field_parents = $element['#field_parents'];
    $field_state = static::getWidgetState($field_parents, $field_name, $form_state);
    $field_state['items'] = NestedArray::getValue($form_state->getUserInput(), $parents);
    $field_state['items_count'] = count($field_state['items']);
    static::setWidgetState($field_parents, $field_name, $form_state, $field_state);

    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $item_values) {
      $return_values[$delta]['title'] = $item_values['title'];
      $return_values[$delta]['minus'] = (bool) $item_values['minus'];
      $return_values[$delta]['max'] = $item_values['max'];
      $return_values[$delta]['min'] = $item_values['min'];
      $return_values[$delta]['count'] = $item_values['count'];
      if ($this->fieldDefinition->getSetting('store')) {
        $positions = [];
        for ($i = 0; $i < $item_values['count']; $i++) {
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
        $return_values[$delta]['sequence'] = explode(',', $item_values['sequence']);
        $return_values[$delta]['positions'] = $positions;
      }
    }
    return $return_values;
  }

  /**
   * Helper function for ajax add/remove item buttons.
   */
  protected function getAjaxWrapperId($form) {
    $parents = $form['#parents'];
    return $parents ? implode('-', $parents) . '-' . $this->ajaxWrapperId : $this->ajaxWrapperId;
  }

}
