<?php

namespace AppBundle\Controller;


use Symfony\Component\ClassLoader\ClassMapGenerator;
//use AppBundle\fpdf\fpdf;
use AppBundle\Entity\Usuario;
use AppBundle\Form\UsuarioType;
use AppBundle\Entity\Operacion;
use AppBundle\Form\OperacionType;
use AppBundle\Repository\OperacionRepository;
use Symfony\Component\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Ps\PdfBundle\Annotation\Pdf;
use Symfony\Component\HttpFoundation\Response;
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
                    //$objeto_DateTime = date_format($fechac, 'Y-m-d');
                    //print_r($fechac);
                    //var_dump($objeto_DateTime);
                    //die();
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

    /**
    * @Pdf()
    */
    //,$name
   /* public function ReporteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $operacion_repo = $em->getRepository('AppBundle:Operacion');
        $operaciones = $operacion_repo->findAll();
        $facade = $this->get('ps_pdf.facade');
        $response = new Response();
        $this->render('PsPdfBundle:Example:reporteUsuarios.pdf.twig', array('operaciones'=> $operaciones), $response);
        
        $xml = $response->getContent();
        
        $content = $facade->render($xml);
        
        return new Response($content, 200, array('content-type' => 'application/pdf'));
    }*/
    public function RepoInicioAction()
    {
        return $this->render('AppBundle:Reportes:reportexfecha.html.twig');
    }

    public function ConsultarOperacionesAction(Request $request)
    {
        $desdefecha = explode("-",$request->get("fechadesde"));
        $hastafecha = explode("-",$request->get("fechahasta"));
        $desdef = $desdefecha[2]."-".$desdefecha[1]."-".$desdefecha[0];
        $hastaf = $hastafecha[2]."-".$hastafecha[1]."-".$hastafecha[0];
        $em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
        $query = "SELECT * FROM operaciones WHERE fecha <"."'".$desdef."'";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $reportepre = $stmt->fetchAll();
        $saldoanterior = 0;
        if (count($reportepre) != 0) {
            foreach ($reportepre as $reportepre) {
                $saldoanterior = $saldoanterior + $reportepre['importe'];
            }
        } 
        $query = "SELECT * FROM operaciones WHERE fecha BETWEEN "."'".$desdef."'"." AND "."'".$hastaf."'";
        $stmt = $db->prepare($query);
        $params = array();
        $stmt->execute($params);
        $reportexf = $stmt->fetchAll();
        if (count($reportexf) < 11) {
            $facade = $this->get('ps_pdf.facade');
            $response = new Response();
            $this->render('PsPdfBundle:Example:reporteoperaciones1.pdf.twig', 
                array('reportexf'=> $reportexf, 'saldoanterior'=>$saldoanterior,
                    'desdef'=>$desdef, 'hastaf'=>$hastaf
                    ), $response);
            $xml = $response->getContent();
            $content = $facade->render($xml);
            return new Response($content, 200, array('content-type' => 'application/pdf'));            
        } else {
            $contreg = count($reportexf);
            $partent = intval(count($reportexf)/10);
            $partent = $partent + 1;
            $facade = $this->get('ps_pdf.facade');
            $response = new Response();
            $this->render('PsPdfBundle:Example:reporteoperaciones2.pdf.twig', 
                array('reportexf'=> $reportexf, 'saldoanterior'=>$saldoanterior,
                    'desdef'=>$desdef, 'hastaf'=>$hastaf, 'contreg' =>$contreg, 'partent'=>$partent
                    ), $response);
            $xml = $response->getContent();
            $content = $facade->render($xml);
            return new Response($content, 200, array('content-type' => 'application/pdf'));                        
        }            
    }

    public function VerXmlAction()
    {
        if (file_exists('C:\xampp\htdocs\appcooperadora\web\public\xml\resultado4xml.xml')) {
            if(!$xml = simplexml_load_file('C:\xampp\htdocs\appcooperadora\web\public\xml\resultado4xml.xml')){
                $status = "No se ha podido cargar el archivo";
                $estado = 0;
            } else {
                $status = "Datos Procesados";
                $estado = 1;
                unlink('C:\xampp\htdocs\appcooperadora\web\public\xml\resultado4xml.xml');
                foreach ($xml as $registro){
                    $estCarga = $this->getCargarMovimientoXml($registro->userid, $registro->fecha, $registro->descripcion, $registro->solicitante, $registro->proveedor, $registro->documentonro, $registro->otorgado, $registro->tipooperacion, $registro->importe);
                }
            }            
        }else{
            $status = "El archivo XML no existe";
            $estado = 0;
            $xml = 0;
        }

        $this->sesion->getFlashBag()->add("status", $status);
        return $this->render('AppBundle:archxml:procesoxml.html.twig',
            array('xml' => $xml, 'estado'=>$estado));
    }

    public function getCargarMovimientoXml($userid, $fecha, $descripcion, $solicitante, $proveedor, $documentonro, $otorgado, $tipooperacion, $importe)
    {
        $em = $this->getDoctrine()->getManager();
        $usuario_repo = $em->getRepository('AppBundle:Usuario');
        $usuario = $usuario_repo->findOneBy(array("id" => $userid));
        $operacion = new Operacion();
        $objeto_DateTime = date_create($fecha);
        $operacion->setFecha($objeto_DateTime);
        $operacion->setDescripcion($descripcion);
        $operacion->setSolicitante($solicitante);
        $operacion->setProveedor($proveedor);
        $operacion->setDocumentonro($documentonro);
        $operacion->setOtorgado($otorgado);
        $operacion->setTipooperacion($tipooperacion);
        $operacion->setImporte($importe);
        $operacion->setEstadoasiento(1);
        $operacion->setUsuario($usuario);     
        $em = $this->getDoctrine()->getManager();
        $em->persist($operacion);
        $flush = $em->flush();
        if ($flush == null) {
            $estCarga = true;  
        } else {
            $estCarga = false;
        }  
        return $estCarga;
    }




}