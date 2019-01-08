<?php

namespace Lhpalacio\EventDispatcher;

use Lhpalacio\EventDispatcher\Exception\InvalidArgumentException;
use Psr\Log\LoggerInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @var callable[]
     */
    private $listeners = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch($event)
    {
        if (!is_object($event)) {
            throw new InvalidArgumentException(sprintf(
                '$event expected to be an object, %s given',
                gettype($event)
            ));
        }

        $eventName = get_class($event);

        if (!isset($this->listeners[$eventName])) {
            if ($this->logger) {
                $this->logger->debug(sprintf('Listeners not found for event %s.', $eventName));
            }

            return;
        }

        foreach ($this->listeners[$eventName] as $listener) {
            $listener($event);

            if ($this->logger) {
                $this->logger->debug(sprintf('Event %s has been called.', $eventName));
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function addListener($eventName, callable $listener)
    {
        if (!isset($this->listeners[(string) $eventName])) {
            $this->listeners[$eventName] = [];
        }

        $this->listeners[(string) $eventName][] = $listener;

        if ($this->logger) {
            $this->logger->debug(sprintf('Listener %s has been registered for event %s.', get_class($listener), $eventName));
        }
    }
}
