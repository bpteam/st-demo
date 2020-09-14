<?php

namespace App\Controller;

use App\Entity\TrackEvent;
use App\Entity\User;
use App\EventSubscriber\TrackingIdSetterSubscriber;
use App\Tracking\Tracker;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Tracking extends AbstractController implements IgnoreTracking
{
    private Tracker $tracker;

    public function __construct(Tracker $tracker)
    {
        $this->tracker = $tracker;
    }

    /**
     * @Route(
     *     path="/t/{_source_label}",
     *     name="app-track",
     *     requirements={
     *       "_source_label"="\w+"
     *     }
     *     )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $this->tracker->track(new TrackEvent(
            Uuid::fromString($request->get(TrackingIdSetterSubscriber::TRACKING_ID_NAME)),
            $this->getUser() instanceof User ? $this->getUser()->getId() : null,
            (string) $request->get('_source_label'),
            new DateTimeImmutable()
        ));

        return new Response('', 204);
    }
}