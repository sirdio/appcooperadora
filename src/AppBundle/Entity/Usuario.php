<?php

namespace AppBundle\Entity;
//use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * Usuario
 */
class Usuario implements UserInterface
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $dni;

    /**
     * @var string
     */
    private $apellido;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $telefono;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \DateTime
     */
    private $fechacreate;

    /**
     * @var boolean
     */
    private $isactive;

    /**
     * @var \AppBundle\Entity\Tipousuario
     */
    private $tipousuario;

    public function __construct()
    {
        $this->isactive = true;
        //$this->tipousuario = new ArrayCollection();
    }  

///////////////////////////////////////////////////

    public function getUsername()
    {
        return $this->username;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array($this->getTipousuario()->getRole());
    }

    public function eraseCredentials()
    {
        
    }

//////////////////////////////////////////////////
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
     * Set dni
     *
     * @param string $dni
     *
     * @return Usuario
     */
    public function setDni($dni)
    {
        $this->dni = $dni;

        return $this;
    }

    /**
     * Get dni
     *
     * @return string
     */
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Usuario
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
   /* public function getUsername()
    {
        return $this->username;
    }*/

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Usuario
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set fechacreate
     *
     * @param \DateTime $fechacreate
     *
     * @return Usuario
     */
    public function setFechacreate($fechacreate)
    {
        $this->fechacreate = $fechacreate;

        return $this;
    }

    /**
     * Get fechacreate
     *
     * @return \DateTime
     */
    public function getFechacreate()
    {
        return $this->fechacreate;
    }

    /**
     * Set isactive
     *
     * @param boolean $isactive
     *
     * @return Usuario
     */
    public function setIsactive($isactive)
    {
        $this->isactive = $isactive;

        return $this;
    }

    /**
     * Get isactive
     *
     * @return boolean
     */
    public function getIsactive()
    {
        return $this->isactive;
    }

    /**
     * Set tipousuario
     *
     * @param \AppBundle\Entity\Tipousuario $tipousuario
     *
     * @return Usuario
     */
    public function setTipousuario(\AppBundle\Entity\Tipousuario $tipousuario = null)
    {
        $this->tipousuario = $tipousuario;

        return $this;
    }

    /**
     * Get tipousuario
     *
     * @return \AppBundle\Entity\Tipousuario
     */
    public function getTipousuario()
    {
        return $this->tipousuario;
    }
}

