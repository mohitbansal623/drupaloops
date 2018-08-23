<?php

namespace Drupal\first_module\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class MymoduleExampleForm.
 */
class MymoduleExampleForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'MymoduleExampleForm';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['user_mail'] = [
      '#type' => 'email',
      '#title' => t('Email ID:'),
      '#required' => TRUE,
    ];

    $form['company_link'] = [
      '#type' => 'textfield',
      '#title' => t('Company Link'),
      '#required' => TRUE,
    ];

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
     '#type' => 'submit',
     '#value' => $this->t('Subscribe'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    //parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      // drupal_set_message($key . ': ' . $value);
    }

    $options = array(
      'attributes' => ['class' => ['link']],
    );

    $email_id = $form_state->getValue('user_mail');

    $company_link = $form_state->getValue('company_link');

    // $link = Link::fromTextAndUrl(t('Link to google'), Url::fromUri('https://www.google.com', $options))->toString();

    $link = Link::fromTextAndUrl($email_id, Url::fromUri($company_link, $options))->toString();

    drupal_set_message($link, 'status');

    drupal_set_message($this->t('@user_email ,Your email-id has been sent !', ['@user_email' => $form_state->getValue('user_mail')]));

   }
}
