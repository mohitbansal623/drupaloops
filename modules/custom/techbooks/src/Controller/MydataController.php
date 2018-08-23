<?php

namespace Drupal\techbooks\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

/**
 * Class MydataController.
 *
 * @package Drupal\mydata\Controller
 */
class MydataController extends ControllerBase {

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
     // Created a custom service.
     $book_list = \Drupal::service('techbooks.listbooks')->getBooks();


     // Used a Database Service which is already there in Drupal 8.
     $db_object = \Drupal::service("database");
     $query = $db_object->query("Select * from node");

     $node = Node::load(13);

     // dpm($node->field_image);

     dpm($node->field_tags->referencedEntities());




     // Fetching records without using any service.
     // $result = \Drupal::database()->select('node', 'n')
     //        ->fields('n', array('nid'))
     //        ->execute()->fetchAllAssoc('nid');

     //  dpm($result);

     return [
      '#type' => 'markup',
      '#markup' => $this->t($book_list)
    ];
  }

}
