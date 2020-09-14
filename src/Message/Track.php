<?php

namespace App\Message;

use App\Entity\TrackEvent;

class Track
{
    public TrackEvent $track;
    public function __construct(TrackEvent $track)
    {
        $this->track = $track;
    }
}