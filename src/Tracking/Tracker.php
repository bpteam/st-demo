<?php

namespace App\Tracking;

use App\Entity\TrackEvent;

interface Tracker
{
    public function track(TrackEvent $track): void;
}