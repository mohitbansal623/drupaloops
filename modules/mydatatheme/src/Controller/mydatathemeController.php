<?php

namespace Drupal\mydatatheme\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class mydatathemeController.
 *
 * @package Drupal\mydatatheme\Controller
 */
class mydatathemeController extends ControllerBase {

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('This page contain all inforamtion about my data ')
    ];
  }

}
