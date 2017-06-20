<?php

namespace AppBundle\Repository;

//use AppBundle\Entity\Operacion;
use Doctrine\ORM\EntityRepository;
///**
// *
// * @ORM\Entity
// * @ORM\Table(name="usuario")
// * @ORM\Entity(repositoryClass="Sistema\UsuariosBundle\Entity\UsuarioRepository")
// */
/**
 * Operacion
 */
class OperacionRepository extends EntityRepository
{
	public function getOperacionesxFecha($desdefecha, $hastafecha)
	{
		$em = $this->getEntityManager();
		$query = $this->createQueryBuilder("o")
				->where("o.fecha BETWEEN :desdef and :hastaf")
				->setParameters("desdef", $desdefecha)
				->setParameters("hastaf", $hastafecha)
				->getQuery();

		try {
		$operaciones = $query->getResult();
		return $operaciones;			
		} catch (\Doctrine\ORM\NoResultException $e) {
			return null;		
		}		
		
	}

// src/AppBundle/Entity/ProductRepository.php
/*public function findOneByIdJoinedToCategory($productId)
{
    $query = $this->getEntityManager()
        ->createQuery(
            'SELECT p, c FROM AppBundle:Product p
            JOIN p.category c
            WHERE p.id = :id'
        )->setParameter('id', $productId);

    try {
        return $query->getSingleResult();
    } catch (\Doctrine\ORM\NoResultException $e) {
        return null;
    }
}*/

// src/Acme/StoreBundle/Entity/ProductRepository.php
/*public function findOneByIdJoinedToCategory($id)
{
    $query = $this->getEntityManager()
        ->createQuery('
            SELECT p, c FROM AcmeStoreBundle:Product p
            JOIN p.category c
            WHERE p.id = :id'
        )->setParameter('id', $id);

    try {
        return $query->getSingleResult();
    } catch (\Doctrine\ORM\NoResultException $e) {
        return null;
    }
}*/
}