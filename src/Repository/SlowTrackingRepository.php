<?php

namespace App\Repository;

use App\Entity\TrackEvent;
use RuntimeException;
use SocialTech\StorageInterface;

class SlowTrackingRepository implements TrackingRepository
{
    private StorageInterface $storage;
    private string $storagePath;

    public function __construct(StorageInterface $storage, string $storagePath = '/app/var/analytics-storage')
    {
        $this->storage = $storage;
        $this->storagePath = $storagePath;
        if(false === (file_exists($this->storagePath) || mkdir($this->storagePath, 0777, true))) {
            throw new RuntimeException('Can not create storage path ' . $this->storagePath);
        }
    }

    public function save(TrackEvent $trackEvent): void
    {
        $filename = $this->storagePath . DIRECTORY_SEPARATOR . $trackEvent->getDateCreated()->format('YmdHisu') . $trackEvent->getId();
        $this->storage->store(
            $filename,
            json_encode($trackEvent, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR)
        );
    }
}