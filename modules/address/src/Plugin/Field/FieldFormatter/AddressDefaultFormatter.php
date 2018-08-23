<?php

namespace Drupal\address\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'AddressDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "AddressDefaultFormatter",
 *   label = @Translation("Address"),
 *   field_types = {
 *     "Address"
 *   }
 * )
 */
class AddressDefaultFormatter extends FormatterBase {

  /**
   * Define how the field type is showed.
   *
   * Inside this method we can customize how the field is displayed inside
   * pages.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {

    $elements = [];

    $options = array(
      'attributes' => ['class' => ['link']],
    );

    foreach ($items as $delta => $item) {
       $title = $item->street;
       $title_link = $item->city;
       $link = Link::fromTextAndUrl($title , Url::fromUri($title_link , $options))->toString();

      $elements[$delta] =  [
        '#type' => 'markup',
        // '#markup' => $item->street . ', ' . $item->city
        '#markup' => $link
      ];
    }

    return $elements;
  }

} // class
