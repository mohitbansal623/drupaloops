<?php

namespace Drupal\mydataajax\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'mydataajaxBlock' block.
 *
 * @Block(
 *  id = "mydataajax_block",
 *  admin_label = @Translation("mydataajax block"),
 * )
 */
class mydataajaxBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    ////$build = [];
    //$build['mydataajax_block']['#markup'] = 'Implement mydataajaxBlock.';

    $form = \Drupal::formBuilder()->getForm('Drupal\mydataajax\Form\mydataajaxForm');

    return $form;
  }

}
