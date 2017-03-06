<?php

namespace Drupal\cloudfront_edge_caching\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Implements an example form.
 */
class CecAdminSettingsForm extends ConfigFormBase {

    /**
     * Constructor for ComproCustomForm.
     *
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     * The factory for configuration objects.
     */
    public function __construct(ConfigFactoryInterface $config_factory) {
        parent::__construct($config_factory);
    }

    /**
     * Returns a unique string identifying the form.
     *
     * @return string
     * The unique string identifying the form.
     */
    public function getFormId() {
        return 'cec.adminsettingsform';
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

        // Region.
        $form['cec_region'] = [
            '#type' => 'textfield',
            '#title' => t('Region'),
            '#size' => 10,
            '#maxlength' => 128,
            '#description' => $this->t('Ej: us-east-1'),
        ];

        // Key.
        $form['cec_key'] = [
            '#type' => 'textfield',
            '#title' => t('Key'),
            '#size' => 50,
            '#maxlength' => 128,
            '#description' => $this->t('Ej: EOjWGh6Keft9czeNkmHsa1aMcrhYukxdlIXRayDt'),
        ];

        // Secret.
        $form['cec_secret'] = [
            '#type' => 'textfield',
            '#title' => t('Secret'),
            '#size' => 20,
            '#maxlength' => 128,
            '#description' => $this->t('Ej: AHIAJF6JNSRJRVNSDOKA'),
        ];

        // Distribution ID.
        $form['cec_distribution_id'] = [
            '#type' => 'textfield',
            '#title' => t('Distribution ID'),
            '#size' => 20,
            '#maxlength' => 128,
            '#description' => $this->t('Ej: E206SWIPUZ2Z48'),
        ];

        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary',
        );

        return $form;
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

    }
}