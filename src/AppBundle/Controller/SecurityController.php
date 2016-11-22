<?php
/**
 * Created by PhpStorm.
 * User: Splinter
 * Date: 12/11/2016
 * Time: 19:09
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/login",name="login")
     */
    public function loginAction(){

        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("@App/Security/login.html.twig", array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/logout",name="logout")
     */
    public function logoutAction(){

    }

    /**
     * @Route("/login_check",name="login_check")
     */
    public function loginCheckAction(){

    }
}