<?php
/**
 * @file
 * Contains \Drupal\first_module\Controller\FirstController.
 */

namespace Drupal\test_twig\Controller;

use Drupal\Core\Controller\ControllerBase;

class TestTwig extends ControllerBase {
  public function content() {
    return [
      '#theme' => 'my_template',
      '#test_var' => $this->t('Test Value'),
    ];

  }
}
