<?php

namespace Lhpalacio\EventDispatcher;

interface RecordsEventsInterface
{
    /**
     * @return object[]
     */
    public function getRecordedEvents();

    /**
     * Clears recorded events
     */
    public function clearRecordedEvents();
}
