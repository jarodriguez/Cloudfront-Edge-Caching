<?php

namespace Drupal\cloudfront_edge_caching\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\cloudfront_edge_caching;
use Aws;
use Aws\Exception\AwsException;

/**
 * Invalidate manual URL
 */
class CecAdminInvalidateUrl extends ConfigFormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * @return string
   * The unique string identifying the form.
   */
  public function getFormId() {
    return 'cec_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'cec.settings',
    ];
  }

  /**
   * Form constructor.
   *
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   *
   * @return array
   * The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('cec.settings');

    // Textarea.
    $form['url'] = [
      '#type' => 'textarea',
      '#title' => $this->t('URL to invalidate'),
      '#description' => $this->t('Specify the existing path you wish to invalidate. For example: /node/28, /forum/1. Enter one value per line'),
      '#required' => TRUE
    ];

    $form['invalidate'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Invalidate URL'),
      '#submit' => array('::invalidateSubmission'),
    );

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Get the URL
    $url_value = explode("\n", $form_state->getValue('url'));

    if (!empty($url_value) && is_array($url_value) && count($url_value) > 0) {
      foreach($url_value as $value) {
        if (substr($value, 0, 1) != '/' && !empty($value)) {
          $form_state->setErrorByName('url', $this->t('The URL introduced is not valid.'));
        }
      }
    }

    else {
      $form_state->setErrorByName('url', $this->t('The URL introduced is not valid.'));
    }
  }

  /**
   * Custom submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function invalidateSubmission(array &$form, FormStateInterface $form_state) {
    // Get the URL
    $url_value = explode("\n", $form_state->getValue('url'));

    // Get the AWS Credentials
    $config = \Drupal::config('cec.settings');

    // Check if the credentials are configured
    if (!$config->get('cec_region' && !$config->get('cec_key') && !$config->get('cec_secret'))) {
      drupal_set_message(t('You must configure the Global Settings correctly before execute an invalidation.'), 'error');
    }

    else {
      // Get the Paths
      $paths = array();
      foreach ($url_value as $value) {
        if ($value) {
          $paths[] = trim($value);
        }
      }

      // Invalidate URL
      list($status, $message) = cloudfront_edge_caching_invalidate_url($paths);

      if ($status == TRUE) {
        drupal_set_message(t('You invalidation is in progress.'), 'status');
      }
      else {
        drupal_set_message(t($message), 'error');
      }
    }
  }
}