<?php

namespace Drupal\kafka_services\Controller;
use Drupal\Core\Controller\ControllerBase;
use RdKafka\Producer;

class KafkaController extends ControllerBase {

  /**
   * @var string
   */
  private  $kafka_topic;

  /**
   * @var bool
   */
  private $enable;

  /**
   * @var array|null
   */
  private $topic;

  /**
   * KafkaController constructor.
   */
  public function __construct() {
    $config = \Drupal::config('kafka_config.settings');
    if ($config->get('kafka_enable')) {
      $rk = new Producer();
      $rk->addBrokers($config->get('kafka_broker_list'));
      $this->topic = $config->get('kafka_topic');
      $this->kafka_topic = $rk->newTopic($this->topic);
      $this->enable = TRUE;
    }
  }

  /**
   * @param $payload
   */
  public function produce($payload) {
    if (!$this->enable) return;
    $data = [
      'topic' => $this->topic,
      'data' => (array)$payload,
      'timestamp' => time()
    ];

    $this->kafka_topic->produce(RD_KAFKA_PARTITION_UA, 0, json_encode($data), 0);
  }
}
