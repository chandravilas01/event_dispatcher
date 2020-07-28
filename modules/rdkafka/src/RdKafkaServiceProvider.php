<?php
namespace Drupal\rdkafka;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\Core\DependencyInjection\ServiceProviderInterface;

class RdKafkaServiceProvider extends ServiceProviderBase implements ServiceProviderInterface {
  public function alter(ContainerBuilder $container) {
    $container = $container->getDefinition('event_dispatcher.event_service');
    $container->setClass('Drupal\rdkafka\Kafka');
  }
}
