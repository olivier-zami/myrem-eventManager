<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class Event extends EntityRepository
{
    public function getAll()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT evt FROM AppBundle:Event evt'
            )
            ->getResult();
    }
}