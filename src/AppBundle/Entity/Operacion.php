<?php

namespace AppBundle\Entity;

/**
 * Operacion
 */
class Operacion
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var string
     */
    private $solicitante;

    /**
     * @var string
     */
    private $proveedor;

    /**
     * @var string
     */
    private $documentonro;

    /**
     * @var string
     */
    private $otorgado;

    /**
     * @var string
     */
    private $tipooperacion;

    /**
     * @var float
     */
    private $importe;

    /**
     * @var boolean
     */
    private $estadoasiento;

    /**
     * @var \AppBundle\Entity\Usuarios
     */
    private $usuario;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Operacion
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Operacion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set solicitante
     *
     * @param string $solicitante
     *
     * @return Operacion
     */
    public function setSolicitante($solicitante)
    {
        $this->solicitante = $solicitante;

        return $this;
    }

    /**
     * Get solicitante
     *
     * @return string
     */
    public function getSolicitante()
    {
        return $this->solicitante;
    }

    /**
     * Set proveedor
     *
     * @param string $proveedor
     *
     * @return Operacion
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;

        return $this;
    }

    /**
     * Get proveedor
     *
     * @return string
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }

    /**
     * Set documentonro
     *
     * @param string $documentonro
     *
     * @return Operacion
     */
    public function setDocumentonro($documentonro)
    {
        $this->documentonro = $documentonro;

        return $this;
    }

    /**
     * Get documentonro
     *
     * @return string
     */
    public function getDocumentonro()
    {
        return $this->documentonro;
    }

    /**
     * Set otorgado
     *
     * @param string $otorgado
     *
     * @return Operacion
     */
    public function setOtorgado($otorgado)
    {
        $this->otorgado = $otorgado;

        return $this;
    }

    /**
     * Get otorgado
     *
     * @return string
     */
    public function getOtorgado()
    {
        return $this->otorgado;
    }

    /**
     * Set tipooperacion
     *
     * @param string $tipooperacion
     *
     * @return Operacion
     */
    public function setTipooperacion($tipooperacion)
    {
        $this->tipooperacion = $tipooperacion;

        return $this;
    }

    /**
     * Get tipooperacion
     *
     * @return string
     */
    public function getTipooperacion()
    {
        return $this->tipooperacion;
    }

    /**
     * Set importe
     *
     * @param float $importe
     *
     * @return Operacion
     */
    public function setImporte($importe)
    {
        $this->importe = $importe;

        return $this;
    }

    /**
     * Get importe
     *
     * @return float
     */
    public function getImporte()
    {
        return $this->importe;
    }

    /**
     * Set estadoasiento
     *
     * @param boolean $estadoasiento
     *
     * @return Operacion
     */
    public function setEstadoasiento($estadoasiento)
    {
        $this->estadoasiento = $estadoasiento;

        return $this;
    }

    /**
     * Get estadoasiento
     *
     * @return boolean
     */
    public function getEstadoasiento()
    {
        return $this->estadoasiento;
    }

    /**
     * Set usuario
     *
     * @param \AppBundle\Entity\Usuarios $usuario
     *
     * @return Operacion
     */
    public function setUsuario(\AppBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \AppBundle\Entity\Usuarios
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}

