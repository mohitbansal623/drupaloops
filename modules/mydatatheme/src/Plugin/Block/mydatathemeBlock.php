<?php

namespace Drupal\mydatatheme\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'mydatathemeBlock' block.
 *
 * @Block(
 *  id = "mydatatheme_block",
 *  admin_label = @Translation("mydatatheme block"),
 * )
 */
class mydatathemeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    ////$build = [];
    //$build['mydatatheme_block']['#markup'] = 'Implement mydatathemeBlock.';

    $form = \Drupal::formBuilder()->getForm('Drupal\mydatatheme\Form\mydatathemeForm');

    return $form;
  }

}
