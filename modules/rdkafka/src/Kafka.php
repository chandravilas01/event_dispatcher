<?php

namespace Drupal\rdkafka;

use Drupal\Core\Config\ConfigFactoryInterface;
use RdKafka\Producer;

/**
 * Manages and produce rdkaka.
 */
class Kafka {

  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  private $config_factory;

  /**
   * @var Producer
   */
  private $rdkafka;

  /**
   * KafkaServices constructor.
   * @param ConfigFactoryInterface $config_factory
   * @param Producer $rdkafka
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config_factory = $config_factory->get('kafka_config.settings')->getRawData();
    $this->rdkafka = new Producer;
  }

  /**
   * @param $event_name
   * @param $event
   */
  public function send($event_name, $event) {
    // Check for kafka enable or not and early return
    if (!$this->config_factory['kafka_enable']) return;
    $payload = [
      'data' => (array)$event,
    ];
    // Adding broker
    $this->rdkafka->addBrokers($this->config_factory['kafka_broker_list']);
    // Kafka topic create/update
    $kafka_topic = $this->rdkafka->newTopic($this->config_factory['kafka_topic'] . '_' . $event_name);
    // Produce Kafka message
    $kafka_topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($payload), 0);

  }
}
