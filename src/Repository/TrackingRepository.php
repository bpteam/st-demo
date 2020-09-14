<?php

namespace App\Repository;

use App\Entity\TrackEvent;

interface TrackingRepository
{
    public function save(TrackEvent $trackEvent): void;
}