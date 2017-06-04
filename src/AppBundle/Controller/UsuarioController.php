<?php

namespace AppBundle\Controller;

use Symfony\Component\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UsuarioController extends Controller
{

    public function LoginAction(Request $request)
    {
        $session = $request->getSession();
        $authenticationUtils = $this->get("security.authentication_utils");
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('AppBundle:Default:login.html.twig', array(
            "error" => $error,
            "last_username"=> $lastUsername
        ));
    }

    public function PrincipalAction(Request $request)
    {
        if ($this->get("security.authorization_checker")->isGranted("ROLE_TESORERO")) {
            return $this->render('AppBundle:Tesorero:principalt.html.twig');
        }/*elseif ($this->get("security.authorization_checker")->isGranted("ROLE_SOCIOB")) {
            return $this->render('AppBundle:Sociob:principalsb.html.twig');
        }elseif ($this->get("security.authorization_checker")->isGranted("ROLE_SOCIOP")) {
            return $this->render('AppBundle:Sociop:principalsp.html.twig');
        }elseif ($this->get("security.authorization_checker")->isGranted("ROLE_ADMIN")) {
            return $this->render('AppBundle:Administrador:principaladmin.html.twig');
        }*/
    }

    public function GestionUAction(Request $request)
    {
        return $this->render('AppBundle:Usuarios:gestionusuarios.html.twig');
    }

 /*   public function PrincipalSocioBAction(Request $request)
    {
        print_r("expression");
    }

    public function PrincipalSocioPAction(Request $request)
    {
        print_r("expression");
    }

    public function PrincipalAdministradorAction(Request $request)
    {
        print_r("expression");
    }*/
}