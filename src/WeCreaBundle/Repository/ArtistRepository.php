<?php

namespace WeCreaBundle\Repository;

/**
 * ArtistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArtistRepository extends \Doctrine\ORM\EntityRepository
{
    public function listArtists()
    {
        return $this->createQueryBuilder("a")
            ->where("a.publication = 1")
            ->getQuery()
            ->getResult();
    }

    /* If no suggestion received as search */
    public function myFindProfilByNameOrFirstname($exp)
    {
        $qb = $this->_em
            ->createQuery('SELECT a.id, a.name, a.firstname FROM WeCreaBundle:Artist a WHERE REGEXP(a.name, :regexp) = true OR REGEXP(a.firstname, :regexp) = true')
            ->setParameter('regexp', $exp);

        return $qb->getResult();
    }

    /* If suggestion received as search */
    public function myFindProfilByNameAndFirstName($exp)
    {
        $qb = $this->_em
            ->createQuery('SELECT a.id, a.name, a.firstname FROM WeCreaBundle:Artist a WHERE REGEXP(a.name, :regexp) = true OR REGEXP(a.firstname, :regexp) = true')
            ->setParameters(array('regexp' => $exp[0], 'regexp' => $exp[1]));

        return $qb->getResult();
    }

    public function myFindArtistsByIds(array $ids){
        $qb = $this->createQueryBuilder('a');

        $qb->add('where', $qb->expr()->in('a.id', '?1'))
            ->setParameter('1', $ids)
            ->orderBy('a.name');

        return $qb->getQuery()->getResult();
    }

}
