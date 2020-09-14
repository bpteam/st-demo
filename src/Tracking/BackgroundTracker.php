<?php

namespace App\Tracking;

use App\Entity\TrackEvent;
use App\Message\Track;
use Symfony\Component\Messenger\MessageBusInterface;

class BackgroundTracker implements Tracker
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function track(TrackEvent $track): void
    {
        $this->messageBus->dispatch(new Track($track));
    }
}