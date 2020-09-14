<?php

namespace App\MessageHandler;

use App\Message\Track;
use App\Repository\TrackingRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TrackHandler implements MessageHandlerInterface
{
    private TrackingRepository $repository;

    public function __construct(
        TrackingRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function __invoke(Track $message)
    {
        $this->repository->save($message->track);
    }
}
