<?php

namespace Drupal\mydataajax\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Class mydataajaxForm.
 *
 * @package Drupal\mydataajax\Form
 */
class mydataajaxForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mydataajax_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('mydataajax', 'm')
            ->condition('id', $_GET['num'])
            ->fields('m');
        $record = $query->execute()->fetchAssoc();

    }

    $form['candidate_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Candidate Name:'),
      '#required' => TRUE,
       //'#default_values' => array(array('id')),
      '#default_value' => (isset($record['name']) && $_GET['num']) ? $record['name']:'',
      );
    //print_r($form);die();

    $form['mobile_number'] = array(
      '#type' => 'textfield',
      '#title' => t('Mobile Number:'),
      '#default_value' => (isset($record['mobilenumber']) && $_GET['num']) ? $record['mobilenumber']:'',
      );

    $form['candidate_mail'] = array(
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['email']) && $_GET['num']) ? $record['email']:'',
      '#prefix' => '<div id="user-email-result">',
      '#ajax' => array(
           'callback' => '::checkUserEmailValidation',
           'effect' => 'fade',
           'event' => 'change',
            'progress' => array(
               'type' => 'throbber',
               'message' => NULL,
            ),
          ),
      '#suffix' => '</div>',
      );

    $form['candidate_age'] = array (
      '#type' => 'textfield',
      '#title' => t('AGE'),
      '#required' => TRUE,
      '#default_value' => (isset($record['age']) && $_GET['num']) ? $record['age']:'',
       );

    $form['candidate_gender'] = array (
      '#type' => 'select',
      '#title' => ('Gender'),
      '#options' => array(
        'Female' => t('Female'),
        'male' => t('Male'),
        '#default_value' => (isset($record['gender']) && $_GET['num']) ? $record['gender']:'',
        ),
      );

    $form['web_site'] = array (
      '#type' => 'textfield',
      '#title' => t('web site'),
      '#default_value' => (isset($record['website']) && $_GET['num']) ? $record['website']:'',
       );

    $form['header_info'] = array(
      '#type' => 'markup',
      '#markup' => '<div id="header_info"><p>Please Select Identityfication Type *</p></div>',
      '#default_value' => '<div id="header_info"><p>Please Select Identityfication Type *
</p></div>',
    );

    $active = array("Email" => "Email Address", "Domain" => "Domain Address");
    $form['select_radio'] = array (
      '#type' => 'radios',
      '#options' => $active,
      '#ajax' => array(
         'callback' => '::checkRadioSelected',
         'effect' => 'fade',
         'event' => 'change',
          'progress' => array(
             'type' => 'throbber',
             'message' => NULL,
          ),
        ),
     );

     $form['display'] = array(
      '#type' => 'markup',
      '#markup' => '<div id="display"></div>',
    );

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
        //'#value' => t('Submit'),
    ];

    return $form;
  }

  public function checkRadioSelected(array $form, FormStateInterface $form_state) {
     $ajax_response = new AjaxResponse();
     \Drupal::logger('mydataajax')->notice('message');

     // $values = $form_state['values']['select_radio'];
     // \Drupal::logger('mydataajax')->notice($values);


    // Check if User or email exists or not
    $field = $form_state->getValues();
    $email = $field['select_radio'];

    // \Drupal::logger('mydataajax')->notice($email);

     if ($email == 'Email') {
       $text = 'Email Address<br><input type ="text"><br>When you verify an entire domain, you are verifying all email addresses from that domain, so you dont need to verify email addresses from that domain individually.
          Domain names are case-insensitive.';
     }
     else if ($email == 'Domain'){
       $text = 'Fully Qualified Domain Name<br><input type ="text"><br>You must verify each email address that will be used as a From or Return-Path address for your messages.
          The entire email address is case-sensitive.';
     }
     $ajax_response->addCommand(new HtmlCommand('#display', $text));
     return $ajax_response;
  }



  public function checkUserEmailValidation(array $form, FormStateInterface $form_state) {
     $ajax_response = new AjaxResponse();
     \Drupal::logger('mydataajax')->notice('message');

    // Check if User or email exists or not
    $field = $form_state->getValues();
    $email = $field['candidate_mail'];
     if (user_load_by_name($email) || user_load_by_mail($email)) {
       $text = 'User or Email is exists';
     } else {
       $text = 'User or Email does not exists';
     }
     $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
     return $ajax_response;
  }

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {

         $name = $form_state->getValue('candidate_name');
          if(preg_match('/[^A-Za-z]/', $name)) {
             $form_state->setErrorByName('candidate_name', $this->t('your name must in characters without space'));
          }

          // Confirm that age is numeric.
        if (!intval($form_state->getValue('candidate_age'))) {
             $form_state->setErrorByName('candidate_age', $this->t('Age needs to be a number'));
            }

         /* $number = $form_state->getValue('candidate_age');
          if(!preg_match('/[^A-Za-z]/', $number)) {
             $form_state->setErrorByName('candidate_age', $this->t('your age must in numbers'));
          }*/

          if (strlen($form_state->getValue('mobile_number')) < 10 ) {
            $form_state->setErrorByName('mobile_number', $this->t('your mobile number must in 10 digits'));
           }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $name=$field['candidate_name'];
    //echo "$name";
    $number=$field['mobile_number'];
    $email=$field['candidate_mail'];
    $age=$field['candidate_age'];
    $gender=$field['candidate_gender'];
    $website=$field['web_site'];

    /*$insert = array('name' => $name, 'mobilenumber' => $number, 'email' => $email, 'age' => $age, 'gender' => $gender, 'website' => $website);
    db_insert('mydataajax')
    ->fields($insert)
    ->execute();

    if($insert == TRUE)
    {
      drupal_set_message("your application subimitted successfully");
    }
    else
    {
      drupal_set_message("your application not subimitted ");
    }*/

    if (isset($_GET['num'])) {
          $field  = array(
              'name'   => $name,
              'mobilenumber' =>  $number,
              'email' =>  $email,
              'age' => $age,
              'gender' => $gender,
              'website' => $website,
          );
          $query = \Drupal::database();
          $query->update('mydataajax')
              ->fields($field)
              ->condition('id', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
          $form_state->setRedirect('mydataajax.display_table_controller_display');

      }

       else
       {
           $field  = array(
              'name'   =>  $name,
              'mobilenumber' =>  $number,
              'email' =>  $email,
              'age' => $age,
              'gender' => $gender,
              'website' => $website,
          );
           $query = \Drupal::database();
           $query ->insert('mydataajax')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");

           $response = new RedirectResponse("/mydataajax/hello/table");
           $response->send();
       }
     }

}
