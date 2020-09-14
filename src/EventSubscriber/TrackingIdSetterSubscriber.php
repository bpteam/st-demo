<?php

namespace App\EventSubscriber;

use App\Controller\IgnoreTracking;
use App\Entity\TrackEvent;
use App\Entity\User;
use App\Tracking\Tracker;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TrackingIdSetterSubscriber implements EventSubscriberInterface
{
    public const TRACKING_ID_NAME = 'tracking_id';

    private string $cookieName;
    private ?string $trackingId;
    private TokenStorageInterface $tokenStorage;
    private Tracker $tracker;

    public function __construct(
        string $cookieName,
        TokenStorageInterface $tokenStorage,
        Tracker $tracker
    ) {
        $this->cookieName = $cookieName;
        $this->tokenStorage = $tokenStorage;
        $this->tracker = $tracker;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $trackingId = $event->getRequest()->cookies->get($this->cookieName);
        if ($trackingId === null) {
            $trackingId = Uuid::uuid4()->toString();
        }
        $event->getRequest()->attributes->set(self::TRACKING_ID_NAME, $trackingId);
        $this->trackingId = $trackingId;
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            /** @var AbstractController $controller */
            $controller = $controller[0];
        }

        if (false === ($controller instanceof IgnoreTracking)) {
            $userId = null;
            $token = $this->tokenStorage->getToken();
            if (isset($token)) {
                $userId = $token->getUser() instanceof User
                    ? $token->getUser()->getId()
                    : null;
            }
            $this->tracker->track(
                new TrackEvent(
                    Uuid::fromString($this->trackingId),
                    $userId,
                    (string) $event->getRequest()->get('_route'),
                    new DateTimeImmutable()
                )
            );
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if ($event->getRequest()->cookies->get($this->cookieName) === null) {
            $event
                ->getResponse()
                ->headers
                ->setCookie(
                    new Cookie(
                        $this->cookieName,
                        $this->trackingId,
                        new DateTimeImmutable('+30 day'),
                        '/',
                        null,
                        null,
                        true
                    ));
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
