<?php

namespace Drupal\event_dispatcher\Services;

use Drupal\Core\Config\ConfigFactory;
use RdKafka\Producer;

/**
 * Class KafkaServices.
 *
 * @package Drupal\event_dispatcher\Services
 */
class KafkaServices {

  /**
   * Configuration Factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * Gets my setting.
   */
  public function getKafkaConfigStatus() {
    $config = $this->configFactory->get('kafka_config.settings');
    return $config->get('kafka_enable');
  }

  public function getKafkaConfig() {
    $config = $this->configFactory->get('kafka_config.settings');
    $result = [];
    $result['sasl_username'] = $config->get('kafka_sasl_username');
    $result['sasl_password'] = $config->get('kafka_sasl_password');
    $result['topic'] = $config->get('kafka_topic');
    $result['broker_list'] = $config->get('kafka_broker_list');

    return $result;
  }

  public function produce($message) {
    $config = $this->configFactory->get('kafka_config.settings');
    if (!empty($config->get('kafka_topic')) && !empty($config->get('kafka_broker_list'))) {
      $rk = new Producer();
      $rk->setLogLevel(LOG_DEBUG);
      $rk->addBrokers($config->get('kafka_broker_list'));
      $topic = $rk->newTopic($config->get('kafka_topic'));
      $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
    }
  }
}

