<?php

namespace Drupal\event_dispatcher\EventDispatcher;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\Event;
use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\kafka_services\Controller\KafkaController;
use Drupal\kafka_services\Services\KafkaServices;

class EventDispatcher extends ContainerAwareEventDispatcher {

  /**
   * EventDispatcher constructor.
   * @param ContainerInterface $container
   * @param array $listeners
   */
  public function __construct(ContainerInterface $container, array $listeners = []) {
    parent::__construct($container, $listeners);
  }

  /**
   * @param string $event_name
   * @param $kafka
   * @param Event|NULL $event
   * @return Event|void|null
   */
  public function dispatch($event_name, Event $event = NULL) {
    parent::dispatch($event_name, $event);
    $kafka = new KafkaController;
    $kafka->produce($event);
  }
}
