<?php

namespace Drupal\cloudfront_edge_caching\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure settings for Cloudfront credentials
 */
class CecAdminSettingsForm extends ConfigFormBase {

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

    // Region.
    $form['cec_region'] = [
      '#type' => 'textfield',
      '#title' => t('Region'),
      '#default_value' => $config->get('cec_region'),
      '#size' => 10,
      '#maxlength' => 128,
      '#description' => $this->t('Ej: us-east-1'),
    ];

    // Key.
    $form['cec_key'] = [
      '#type' => 'textfield',
      '#title' => t('Key'),
      '#default_value' => $config->get('cec_key'),
      '#size' => 50,
      '#maxlength' => 128,
      '#description' => $this->t('Ej: EOjWGh6Keft9czeNkmHsa1aMcrhYukxdlIXRayDt'),
    ];

    // Secret.
    $form['cec_secret'] = [
      '#type' => 'textfield',
      '#title' => t('Secret'),
      '#default_value' => $config->get('cec_secret'),
      '#size' => 20,
      '#maxlength' => 128,
      '#description' => $this->t('Ej: AHIAJF6JNSRJRVNSDOKA'),
    ];

    // Distribution ID.
    $form['cec_distribution_id'] = [
      '#type' => 'textfield',
      '#title' => t('Distribution ID'),
      '#default_value' => $config->get('cec_distribution_id'),
      '#size' => 20,
      '#maxlength' => 128,
      '#description' => $this->t('Ej: E206SWIPUZ2Z48'),
    ];

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Save'),
      '#button_type' => 'primary',
    );

    return parent::buildForm($form, $form_state);
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
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
    // Retrieve the configuration
    $this->config('cec.settings')
      // Set the submitted configuration setting
      ->set('cec_region', $form_state->getValue('cec_region'))
      ->set('cec_key', $form_state->getValue('cec_key'))
      ->set('cec_secret', $form_state->getValue('cec_secret'))
      ->set('cec_distribution_id', $form_state->getValue('cec_distribution_id'))
      ->save();
    parent::submitForm($form, $form_state);
  }
}