<?php

namespace Lhpalacio\EventDispatcher;

trait RecordsEventsTrait
{
    /**
     * @var object[]
     */
    private $recordedEvents = [];

    /**
     * @return object[]
     */
    public function getRecordedEvents()
    {
        return $this->recordedEvents;
    }

    /**
     * Clears recorded events
     */
    public function clearRecordedEvents()
    {
        $this->recordedEvents = [];
    }

    /**
     * @param object $event
     */
    private function recordThat($event)
    {
        $this->recordedEvents[] = $event;
    }
}
