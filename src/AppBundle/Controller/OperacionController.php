<?php

namespace AppBundle\Controller;

use Symfony\Component\ClassLoader\ClassMapGenerator;
//use AppBundle\fpdf\fpdf;
use AppBundle\Entity\Usuario;
use AppBundle\Form\UsuarioType;
use AppBundle\Entity\Operacion;
use AppBundle\Form\OperacionType;
use Symfony\Component\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
class OperacionController extends Controller
{
    private $sesion;

    public function __construct()
    {
        $this->sesion = new Session();
    }

    public function FormularioOperacionAction(Request $request, $usn)
    {
        $operacion = new Operacion();
        $form = $this->createForm(OperacionType::class, $operacion);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {   
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $usuario_repo = $em->getRepository('AppBundle:Usuario');
                $usuario = $usuario_repo->findOneBy(array("username" => $usn));                
               
                $estadodesc = $this->getVerificarLongitud($form->get("descripcion")->getData(), 500);
                $estadosolic = $this->getVerificarLongitud($form->get("solicitante")->getData(), 500);
                $estadoprove = $this->getVerificarLongitud($form->get("proveedor")->getData(), 200);
                $estadodoc = $this->getVerificarLongitud($form->get("documentonro")->getData(), 500);
                $estadoseleco = $this->getVerificarSelect($form->get("otorgado")->getData());
                $estadoselecto = $this->getVerificarSelect($form->get("tipooperacion")->getData());
                if ($estadodesc == false and $estadosolic == false and $estadoprove == false and $estadodoc == false and $estadoseleco == false and $estadoselecto == false) {
                    $operacion = new Operacion();
                    $fechac =  $form->get("fecha")->getData();
                    //var_dump($fechac);
                    //die();
                    //$objeto_DateTime = date_create_from_format('Y-m-d', $fechac);
                    $operacion->setFecha($fechac);
                    $operacion->setDescripcion($form->get("descripcion")->getData());
                    $operacion->setSolicitante($form->get("solicitante")->getData());
                    $operacion->setProveedor($form->get("proveedor")->getData());
                    $operacion->setDocumentonro($form->get("documentonro")->getData());
                    $operacion->setOtorgado($form->get("otorgado")->getData());
                    $operacion->setTipooperacion($form->get("tipooperacion")->getData());
                    $operacion->setImporte($form->get("importe")->getData());
                    $operacion->setEstadoasiento(1);
                    $operacion->setUsuario($usuario);     
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($operacion);
                    $flush = $em->flush();
                    if ($flush == null) {
                        $status = "La Operación se registro con exito.";
                        $operacion = new Operacion();
                        $form = $this->createForm(OperacionType::class, $operacion);
                    } else {
                        $status = "La Operación no se ha registrado.";
                    }  
                }else{
                    $status = "No se pudo registrar la operación, verifique los datos ingresados.";
                }             
            }else{
                $status = "La Operación no se ha registrado.";
            }
            $this->sesion->getFlashBag()->add("status", $status);
        }
        return $this->render('AppBundle:Movimientos:movimientos.html.twig',
            array('form' => $form->createView()
                ));
    }
    public function getVerificarLongitud($valor, $longitud)
    {
        if (strlen($valor) > $longitud) {
            $estado = true;
        } else {
            $estado = false;
        }
        return $estado; 
    }
    public function getVerificarSelect($valor)
    {
        if ($valor == 'opcion') {
            $estado = true;
        } else {
            $estado = false;
        }
        return $estado; 
    }
    public function ReportePDFAction(Request $request)
    {
        var_dump(ClassMapGenerator::createMap(__DIR__.'/../fpdf/fpdf'));


        //$pdf = new FPDF();
        die();
    }
}