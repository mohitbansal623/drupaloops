<?php

namespace Drupal\site_api_key\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Class site_api_key.
 *
 * @package Drupal\mydatatheme\Form
 */
class site_api_key extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_api_key_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['siteapikey'] = array (
      '#type' => 'textfield',
      '#title' => t('Site API Key'),
      '#required' => TRUE,
    );


    $form['nodeid'] = array (
      '#type' => 'textfield',
      '#title' => t('Node ID'),
      '#required' => TRUE,
    );

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Save',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field = $form_state->getValues();
    $key = $field['siteapikey'];
    $nid = $field['nodeid'];

    $config = \Drupal::service('config.factory')->getEditable('siteapikey.settings');
    $site_apikey = $config->get('siteapikey');

    if (is_numeric($nid)) {
      $node = \Drupal\node\Entity\Node::load($nid);
      if ($node->type != 'page') {
        drupal_set_message(t('Access denied'), 'status');
      }

      elseif($site_apikey == $key && $node->type == 'page') {
        drupal_set_message(t('Title is ' . $node->title), 'status');
      }
    }
  }
}
