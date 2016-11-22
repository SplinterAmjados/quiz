<?php
/**
 * Created by PhpStorm.
 * User: anouira
 * Date: 22/11/2016
 * Time: 15:20
 */

namespace AppBundle\Listeners;


use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var Router
     */
    private $router;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker,Router $router){
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
    }

    /**
     * onAuthenticationSuccess
     *
     * @author    Joe Sexton <joe@webtipblog.com>
     * @param    Request $request
     * @param    TokenInterface $token
     * @return    Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token){

        if ($this->authorizationChecker->isGranted('ROLE_RESPONSIBLE')) {
            return new RedirectResponse($this->router->generate('homepage'));
        }

        if ($this->authorizationChecker->isGranted('ROLE_CANDIDATE')) {
            return new RedirectResponse($this->router->generate('candidate_index'));
        }

    }
}