<?php

namespace Lhpalacio\EventDispatcher\Factory;

use Lhpalacio\EventDispatcher\EventDispatcher;
use Lhpalacio\EventDispatcher\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class EventDispatcherFactory
{
    /**
     * @param ContainerInterface $container
     * @return EventDispatcher
     */
    public function __invoke(ContainerInterface $container)
    {
        $listenersConfig = $this->getListenersConfig($container);
        $logger = $this->getlogger($container);

        $eventDispatcher = new EventDispatcher($logger);

        foreach ($listenersConfig as $eventName => $listenerNames) {
            $listenerNames = (array) $listenerNames;

            foreach ($listenerNames as $listenerName) {
                $listener = $container->get($listenerName);

                $eventDispatcher->addListener($eventName, $listener);
            }
        }

        return $eventDispatcher;
    }

    /**
     * @param ContainerInterface $container
     * @return LoggerInterface|null
     */
    private function getlogger(ContainerInterface $container)
    {
        $config = $container->get('config');

        if (!isset($config['lhpalacio']['event_dispatcher']['logger'])) {
            return null;
        }

        $logger = $container->get($config['lhpalacio']['event_dispatcher']['logger']);

        if (!$logger instanceof LoggerInterface) {
            throw new InvalidArgumentException(sprintf(
                'Invalid event dispatcher logger configuration class provided; received "%s", expected class implementing Psr\Log\LoggerInterface',
                $config['lhpalacio']['event_dispatcher']['logger']
            ));
        }

        return $logger;
    }

    /**
     * @param ContainerInterface $container
     * @return array
     */
    private function getListenersConfig(ContainerInterface $container)
    {
        $config = $container->get('config');

        if (!isset($config['lhpalacio']['event_dispatcher']['listeners'])) {
            throw new InvalidArgumentException(
                'Missing [\'lhpalacio\'][\'event_dispatcher\'][\'listeners\'] key in config'
            );
        }

        $listenersConfig = $config['lhpalacio']['event_dispatcher']['listeners'];

        if (!is_array($listenersConfig)) {
            throw new InvalidArgumentException(sprintf(
                'Listeners config must be array, %s given',
                is_object($listenersConfig) ? get_class($listenersConfig) : gettype($listenersConfig)
            ));
        }

        return $listenersConfig;
    }
}