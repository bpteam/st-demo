<?php

namespace App\Controller\Login;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\PostAuthenticator;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class Registration extends AbstractController
{
    private UserRepository $userRepository;
    private EncoderFactoryInterface $encoderFactory;
    private GuardAuthenticatorHandler $guardAuthenticator;
    private PostAuthenticator $authenticator;

    public function __construct(
        UserRepository $userRepository,
        GuardAuthenticatorHandler $guardAuthenticator,
        PostAuthenticator $authenticator,
        EncoderFactoryInterface $encoderFactory
    )
    {
        $this->userRepository = $userRepository;
        $this->encoderFactory = $encoderFactory;
        $this->guardAuthenticator = $guardAuthenticator;
        $this->authenticator = $authenticator;
    }

    /**
     * @Route(
     *     path="/registration",
     *     name="app-registration",
     *     methods={"POST"}
     *     )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $message = 'Success';
        $code = 200;
        if ($this->getUser()) {
            $message = 'Already logged in';
        } elseif (
            empty($request->get('firstname'))
            || empty($request->get('lastname'))
            || empty($request->get('nickname'))
            || empty($request->get('password'))
            || empty($request->get('age'))
        ) {
            $message = 'Invalid credentials';
            $code = 400;
        } elseif ($this->userRepository->findByNickname($request->get('nickname'))) {
            $message = 'User already exists';
            $code = 401;
        } else {
            $user = new User(
                Uuid::uuid4(),
                $request->get('firstname'),
                $request->get('lastname'),
                $request->get('nickname'),
                $request->get('password'),
                (int) $request->get('age')
            );
            $user->setPassword($this->encoderFactory->getEncoder($user)
                ->encodePassword($request->get('password'), $user->getSalt()));
            $this->userRepository->save($user);
            $this->guardAuthenticator->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $this->authenticator,
                'main'
            );
        }

        return new JsonResponse([
            'message' => $message,
        ], $code);
    }
}
