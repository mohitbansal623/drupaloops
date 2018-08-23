<?php

namespace Drupal\first_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ArticleBlock' block.
 *
 * @Block(
 *  id = "ArticleBlock",
 *  admin_label = @Translation("ArticleBlock"),
 * )
 */
class ArticleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['ArticleBlock']['#markup'] = 'Implement Form.';

    $build = \Drupal::formBuilder()->getForm('Drupal\first_module\Form\MymoduleExampleForm');

    return $build;
  }

}
