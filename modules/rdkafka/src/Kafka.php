<?php

namespace Drupal\rdkafka;

use Drupal\Core\Config\ConfigFactoryInterface;
use RdKafka\Producer;

class Kafka {
  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  private $config_factory;

  /**
   * @var bool
   */
  private $enable;

  /**
   * @var Producer
   */
  private $rk;

  /**
   * KafkaServices constructor.
   * @param ConfigFactoryInterface $config_factory
   */

  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config_factory = $config_factory->get('kafka_config.settings')->getRawData();
    if ($this->config_factory['kafka_enable']) {
      $this->enable = TRUE;
      $this->rk = new Producer();
      $this->rk->addBrokers($this->config_factory['kafka_broker_list']);
    }
  }

  /**
   * @param $event_name
   * @param $event
   */
  public function send($event_name, $event) {
    if (!$this->enable) false;
    $payload = [
      'data' => (array)$event,
    ];
    $kafka_topic = $this->rk->newTopic($this->config_factory['kafka_topic'] . '_' . $event_name);
    $kafka_topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($payload), 0);
  }
}
