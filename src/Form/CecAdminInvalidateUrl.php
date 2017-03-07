<?php

namespace Drupal\cloudfront_edge_caching\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\cloudfront_edge_caching;
use Aws;
use Aws\Exception\AwsException;

/**
 * Configure settings for Cloudfront credentials
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

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Invalidate'),
      '#button_type' => 'primary',
    );

    return parent::buildForm($form, $form_state);
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
   * Form submission handler.
   *
   * @param array $form
   * An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    // Get the URL
    $url_value = explode("\n", $form_state->getValue('url'));

    // Get the AWS Credentials
    $config = \Drupal::config('cec.settings');

    // Get the Paths
    $paths = array();
    foreach($url_value as $value) {
      if ($value) {
        $paths[] = trim($value);
      }
    }

    // Quantity
    $total_paths = count($paths);

    // Load AWS SDK
    $cloudFront = new  Aws\CloudFront\CloudFrontClient([
      'version'     => 'latest',
      'region'      => $config->get('cec_region'),
      'credentials' => [
        'key'    => $config->get('cec_key'),
        'secret' => $config->get('cec_secret')
      ]
    ]);

    // Invalidate URL
    try {
      $result = $cloudFront->createInvalidation([
        'DistributionId' => $config->get('cec_distribution_id'), // REQUIRED
        'InvalidationBatch' => [ // REQUIRED
          'CallerReference' => random_int(1, 999999999999999999),
          'Paths' => [
            'Items' => $paths, // items or paths to invalidate
            'Quantity' => $total_paths // REQUIRED (must be equal to the number of 'Items' in the previus line)
          ]
        ]
      ]);
    } catch (AwsException $e) {
      drupal_set_message(t($e->getMessage()), 'error');
    }

    parent::submitForm($form, $form_state);
  }
}