<?php

namespace Drupal\ajax_load_entity\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'ajax_load_entity_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "ajax_load_entity_formatter",
 *   label = @Translation("Ajax load entity formatter"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class AjaxLoadEntityFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // Implement settings summary.

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = ['#markup' => $this->viewValue($item)];
    }

    return $elements;
  }

  /**
   * Generate the output appropriate for one field item.
   *
   * @param \Drupal\Core\Field\FieldItemInterface $item
   *   One field item.
   *
   * @return string
   *   The textual output generated.
   */
  protected function viewValue(FieldItemInterface $item) {
    // The text value has no text format assigned to it, so the user input
    // should equal the output, including newlines.
    return nl2br(Html::escape($item->value));
  }

}

/**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    // Get the view mode picked on the manage display page.
    $view_mode = $this->getSetting('view_mode');

    // Now we need to loop over each entity to be rendered and create a link element
    // for each one.
    foreach ($this->getEntitiesToView($items, $langcode) as $delta => $entity) {
      if (!$entity->isNew()) {
        // Set up the options for our route, we default the method to 'nojs' since
        // the drupal ajax library will replace that for us.
        $options = [
          'method' => 'nojs',
          'entity_type' => $entity->getEntityTypeId(),
          'entity' => $entity->id(),
          'view_mode' => $view_mode
        ];

        // Now we create the path from our route, passing the options it needs.
        $uri = Url::fromRoute('ajax_load_entity.load_entity', $options);

        // And create a link element. We need to add the 'use-ajax' class so that
        // Drupal's core AJAX library will detect this link and ajaxify it.
        $elements[$delta] = [
          '#type' => 'link',
          '#title' => $entity->uuid(),
          '#url' => $uri,
          '#options' => $uri->getOptions() + [
            'attributes' => [
              'class' => [
                'use-ajax'
              ],
            ]
          ]
        ];

        if (!empty($items[$delta]->_attributes)) {
          $elements[$delta]['#options'] += ['attributes' => []];
          $elements[$delta]['#options']['attributes'] += $items[$delta]->_attributes;
          // Unset field item attributes since they have been included in the
          // formatter output and shouldn't be rendered in the field template.
          unset($items[$delta]->_attributes);
        }
      } else {
        continue;
      }
      $elements[$delta]['#cache']['tags'] = $entity->getCacheTags();
    }

    // Now we add the container to render after the links. This is where the AJAX
    // loaded content will be injected in to.
    $elements[] = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'ajax-load-entity',
      ],
    ];

    // Make sure the AJAX library is attached.
    $elements['#attached']['library'][] = 'core/drupal.ajax';
    return $elements;
  }
