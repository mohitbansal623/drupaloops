<?php
/**
 * @file
 * Contains Drupal\ajax_example\AjaxExampleForm
 */

namespace Drupal\ajax_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class AjaxExampleForm extends FormBase {

  public function getFormId() {
    return 'ajax_example_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
     $form['user_email'] = array(
        '#type' => 'textfield',
        '#title' => 'User or Email',
        '#description' => 'Please enter in a user or email',
        '#prefix' => '<div id="user-email-result"></div>',
        '#ajax' => array(
           'callback' => '::checkUserEmailValidation',
           'effect' => 'fade',
           'event' => 'change',
            'progress' => array(
               'type' => 'throbber',
               'message' => NULL,
            ),
          ),
       );
    );
  }
  public function checkUserEmailValidation(array $form, FormStateInterface $form_state) {
     $ajax_response = new AjaxResponse();

    // Check if User or email exists or not
    $field = $form_state->getValues();
    $email = $field['user_email'];
     if (user_load_by_name($email) || user_load_by_mail($email)) {
       $text = 'User or Email is exists';
     } else {
       $text = 'User or Email does not exists';
     }
     $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
     return $ajax_response;
     }
  }
?>
