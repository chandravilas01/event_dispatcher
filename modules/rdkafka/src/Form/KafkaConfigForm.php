<?php

/**
 * @file
 * Contains Drupal\event_dispatcher\Form\KafkaConfigForm.
 */
namespace Drupal\rdkafka\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class KafkaConfigForm.
 *
 * @package Drupal\event_dispatcher\Form
 */
class KafkaConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'kafka_config.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'kafka_config_setting_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('kafka_config.settings');

    $form['kafka'] = array(
      '#type' => 'fieldset',
      '#title' => t('Configure Kafka'),
      '#collapsible' => FALSE,
      '#collapsed' => FALSE,
      '#prefix' => t('Apache Kafka is an open-source stream-processing software platform which is used to handle
       the real-time data storage. It works as a broker between two parties, i.e., a sender and a receiver. It can handle
       about trillions of data events in a day.'),
    );

    $form['kafka']['kafka_enable'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable Kafka'),
      '#description' => t('click on checkbox to enable kafka configuration.'),
      '#default_value' => $config->get('kafka_enable'),
    );

    $form['kafka']['kafka_topic'] = array(
      '#type' => 'textfield',
      '#title' => t('Topic'),
      '#description' => t('A Topic is a category/feed name to which records are stored and published.'),
      '#placeholder' => t('topic name'),
      '#default_value' => $config->get('kafka_topic'),
      '#required' => TRUE,
    );

    $form['kafka']['kafka_broker_list'] = array(
      '#type' => 'textarea',
      '#title' => t('Servers/Broker List'),
      '#attributes' => array('placeholder' => t('10.0.0.1:9092, 10.0.0.2:9092'),),
      '#description' => t('Enter kafka brokers by comma(,) separate list (10.0.0.1:9092, 10.0.0.2:9092), A broker
      is a Kafka server and Kafka cluster typically consists of multiple brokers to maintain load balance.'),
      '#default_value' => $config->get('kafka_broker_list'),
      '#required' => TRUE,
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('kafka_config.settings')
      ->set('kafka_enable', $form_state->getValue('kafka_enable'))
      ->set('kafka_topic', $form_state->getValue('kafka_topic'))
      ->set('kafka_broker_list', $form_state->getValue('kafka_broker_list'))
      ->save();
  }
}
