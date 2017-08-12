<?php

namespace AppBundle\Controller;

use AppBundle\fpdf\fpdf;
use AppBundle\Entity\Usuario;
use AppBundle\Form\UsuarioType;
use AppBundle\Form\UsuarioEditType;
use AppBundle\Form\PassType;
use Symfony\Component\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
class UsuarioController extends Controller
{
    private $sesion;

    public function __construct()
    {
        $this->sesion = new Session();
    }

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
        if ($this->get("security.authorization_checker")->isGranted("ROLE_ADMIN")) {
            return $this->render('AppBundle:Tesorero:principalt.html.twig');
        }elseif ($this->get("security.authorization_checker")->isGranted("ROLE_USER")) {
            return $this->render('AppBundle:User:principalu.html.twig');
        }/*elseif ($this->get("security.authorization_checker")->isGranted("ROLE_SOCIOP")) {
            return $this->render('AppBundle:Sociop:principalsp.html.twig');
        }elseif ($this->get("security.authorization_checker")->isGranted("ROLE_ADMIN")) {
            return $this->render('AppBundle:Administrador:principaladmin.html.twig');
        }*/
    }

    public function PrincipalAAction(Request $request)
    {
        return $this->render('AppBundle:Tesorero:principalt.html.twig');
    }

    public function PrincipalUAction(Request $request)
    {
        return $this->render('AppBundle:User:principalu.html.twig');
    }

    public function GestionUAction(Request $request)
    {
        return $this->render('AppBundle:Usuarios:gestionusuarios.html.twig');
    }

    public function ListarUAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository('AppBundle:Usuario');
        $usuario = $usuario_repo->findAll();
        if(!$usuario){
            $status = "No existen usuarios registrados.";
        }else{
            $status = "Lista de usuarios registrados.";
        }
        $this->sesion->getFlashBag()->add("status", $status);
        return $this->render('AppBundle:Usuarios:listarusuarios.html.twig',
            array('usuario'=>$usuario));
    }

    public function EditarDatosAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository('AppBundle:Usuario');
        $usuario = $usuario_repo->findOneBy(array('id'=>$id));
        if (count($usuario)!= 0) {
            $editform = $this->createForm(UsuarioEditType::class, $usuario);
            return $this->render('AppBundle:Usuarios:editardatos.html.twig',
            array('edit_form' => $editform->createView(), 'usuario'=>$usuario
                ));            
        } else {
            $status = "Se produjo un error al intentar recuperar los datos para su modificación.";
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('usuario_listar');            
        }
    }

    public function EditarPassAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository('AppBundle:Usuario');
        $usuario = $usuario_repo->findOneBy(array('id'=>$id));
        if (count($usuario)!= 0) {
            $editform = $this->createForm(PassType::class, $usuario);
            return $this->render('AppBundle:Usuarios:editarpassword.html.twig',
            array('edit_form' => $editform->createView(), 'usuario'=>$usuario
                ));            
        } else {
            $status = "Se produjo un error al intentar recuperar los datos para su modificación.";
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('usuario_listar');            
        }
    }

    public function FormularioAltaAction(Request $request)
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {   
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $consulta_repo = $em->getRepository('AppBundle:Usuario');
                $consultarDni = $consulta_repo->findOneBy(array("dni" => $form->get("dni")->getData()));
                $consultarCorreo = $consulta_repo->findOneBy(array("email" => $form->get("email")->getData())); 
                $consultarUsername = $consulta_repo->findOneBy(array("username" => $form->get("username")->getData())); 
                if (count($consultarDni) == 0 and count($consultarCorreo) == 0 and count($consultarUsername) == 0) {
                    $usuario = new Usuario();
                    $usuario->setDni($form->get("dni")->getData());
                    $usuario->setNombre($form->get("nombre")->getData());
                    $usuario->setApellido($form->get("apellido")->getData());
                    $usuario->setUserName($form->get("username")->getData());
                    $usuario->setEmail($form->get("email")->getData());
                    $usuario->setTelefono($form->get("telefono")->getData());
                    $factory = $this->get('security.encoder_factory');
                    $encader = $factory->getEncoder($usuario);
                    $password = $encader->encodePassword($form->get("password")->getData(), $usuario->getSalt());
                    $usuario->setPassword($password);
                    $fechac =  date('Y-m-d');
                    $objeto_DateTime = date_create_from_format('Y-m-d', $fechac);
                    $anio = date('Y');
                    $mes= date('m');
                    $dia= date('d');
                    $fechareg = $anio."-".$mes."-".$dia;
                    $usuario->setFechacreate($objeto_DateTime);
                    $usuario->setIsactive(1);
                    $em = $this->getDoctrine()->getManager();
                    $tu_repo = $em->getRepository("AppBundle:Tipousuario");
                    $tu = $tu_repo->findOneBy(array('id' => $form->get("tipousuario")->getData()->getId()));
                    $usuario->setTipousuario($tu);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($usuario);
                    $flush = $em->flush();
                    if ($flush == null) {
                        $status = "El usuario se ha creado correctamente.";
                        $usuario = new Usuario();
                        $form = $this->createForm(UsuarioType::class, $usuario);
                    } else {
                        $status = "El usuario no se ha creado correctamente.";
                    }  
                }else{
                    $status = "El usuario ya existe!!!.";
                }             
            }else{
                $status = "El usuario no se ha creado correctamente.";
            }
            $this->sesion->getFlashBag()->add("status", $status);
        } 
        return $this->render('AppBundle:Usuarios:formularioalta.html.twig',
            array('form' => $form->createView()
                ));
    }

    public function ConsultarUserNameAction($name)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository("AppBundle:Usuario");
        $usuario = $usuario_repo->findOneBy(array('username' => $name));
        if (count($usuario) == 1) {
            $estado = 1;
            $alert1 = "El Nombre de Usuario ya existe.";
        } else {
            $estado = 0;
            $alert1 = "nombre de Usuario valido.";
        }
        return $this->render('AppBundle:Usuarios:mensaje1.html.twig',
            array('alert1' => $alert1, 'estado'=>$estado
                ));
    }

    public function ConsultarEmailAction($correo)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository("AppBundle:Usuario");
        $usuario = $usuario_repo->findOneBy(array('email' => $correo));
        if (count($usuario) == 1) {
            $estado = 1;
            $alert1 = "El Email ingresado ya existe.";
        } else {
            $estado = 0;
            $alert1 = "Email valido.";
        }
        return $this->render('AppBundle:Usuarios:mensaje1.html.twig',
            array('alert1' => $alert1, 'estado'=>$estado
                ));
    }

    public function ConsultarDniAction($dni)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository("AppBundle:Usuario");
        $usuario = $usuario_repo->findOneBy(array('dni' => $dni));
        if (count($usuario) == 1) {
            $estado = 1;
            $alert1 = "El D.N.I. ingresado ya existe.";
        } else {
            $estado = 0;
            $alert1 = "D.N.I. valido.";
        }
        return $this->render('AppBundle:Usuarios:mensaje1.html.twig',
            array('alert1' => $alert1, 'estado'=>$estado
                ));
    }

    public function ConsultarEditEmailAction($correo, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository("AppBundle:Usuario");
        $usuario = $usuario_repo->findOneBy(array('email' => $correo));
        $userEdit = $usuario_repo->findOneBy(array('id' => $id));
        if (count($usuario) == 1) {
            if ($usuario->getEmail() == $userEdit->getEmail()) {
                $estado = 0;
                $alert1 = "Email valido.";                
            }else{
                $estado = 1;
                $alert1 = "El Email ingresado ya existe.'";
            }
        } else {
            $estado = 0;
            $alert1 = "Email valido.";
        }
        return $this->render('AppBundle:Usuarios:mensajeedit.html.twig',
            array('alert1' => $alert1, 'estado'=>$estado
                ));
    }

    public function ConsultarEditDniAction($dni, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository("AppBundle:Usuario");
        $usuario = $usuario_repo->findOneBy(array('dni' => $dni));
        $userEdit = $usuario_repo->findOneBy(array('id' => $id));
        if (count($usuario) == 1) {
            if ($usuario->getEmail() == $userEdit->getEmail()) {
                $estado = 0;
                $alert1 = "D.N.I. valido.";               
            }else{
            $estado = 1;
            $alert1 = "El D.N.I. ingresado ya existe.";
            }            
        } else {
            $estado = 0;
            $alert1 = "D.N.I. valido.";
        }
        return $this->render('AppBundle:Usuarios:mensajeedit.html.twig',
            array('alert1' => $alert1, 'estado'=>$estado
                ));
    }

    public function ActualizarDatosAction(Request $request, $id)
    { 
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository("AppBundle:Usuario");
        $usuario = $usuario_repo->findOneBy(array('id'=>$id));
        if (count($usuario)!= 0) {
            $editform = $this->createForm(UsuarioEditType::class, $usuario);
            $editform->handleRequest($request);
            if ($editform->isValid()) {
                $usuario->setDni($editform->get("dni")->getData());
                $usuario->setNombre($editform->get("nombre")->getData());
                $usuario->setApellido($editform->get("apellido")->getData());
                $usuario->setEmail($editform->get("email")->getData());
                $usuario->setTelefono($editform->get("telefono")->getData());
                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                $flush = $em->flush();
                if ($flush == null) {
                    $status = "Los datos del usuario se modificaron correctamente.";                    
                } else {
                    $status = "Los datos del usuario no se ha modificado con exito.";
                }
                $this->sesion->getFlashBag()->add("status", $status);
                return $this->redirectToRoute('usuario_listar');                
            } else {
                $status = "Se produjo un error al intentar Actualizar los datos.";
                $this->sesion->getFlashBag()->add("status", $status);                
                return $this->render('AppBundle:Usuarios:editardatos.html.twig',
                array('edit_form' => $editform->createView(), 'usuario'=>$usuario
                ));                 
            }
        } else {
            $status = "Se produjo un error al intentar Actualizar los datos.";
            $this->sesion->getFlashBag()->add("status", $status);
            return $this->redirectToRoute('usuario_listar');
        }        
    }

    public function ModificarPaswordAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository("AppBundle:Usuario");
        $usuario = $usuario_repo->findOneBy(array('id'=>$id));
        $editform = $this->createForm(PassType::class, $usuario);
        $editform->handleRequest($request);
        if ($editform->isSubmitted()) { 
            if ($editform->isValid()) {
                $factory = $this->get('security.encoder_factory');
                $encader = $factory->getEncoder($usuario);
                $password = $encader->encodePassword($editform->get("password")->getData(), $usuario->getSalt());
                $usuario->setPassword($password);
                $em = $this->getDoctrine()->getManager();
                $em->persist($usuario);
                $flush = $em->flush();
                if ($flush == null) {
                    $status = "La contraseña se ha modificado con exito.";
                    $this->sesion->getFlashBag()->add("status", $status);
                    return $this->redirectToRoute('usuario_listar');           
                } else {
                    $status = "La contraseña no se ha modificado.";
                }                
            } else {
                $status = "La contraseña no se ha modificado.";
            }
            $this->sesion->getFlashBag()->add("status", $status);
        } 
        return $this->render('AppBundle:Usuarios:editarpassword.html.twig',
            array('edit_form' => $editform->createView(), 'usuario'=>$usuario
                ));        
    } 

}