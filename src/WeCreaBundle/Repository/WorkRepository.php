<?php

namespace WeCreaBundle\Repository;
use Doctrine\ORM\Query;

/**
 * WorkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkRepository extends \Doctrine\ORM\EntityRepository
{
	/**
	 * Get work title by workId
	 * @return mixed
	 */
	public function getWorkTitle($workId)
	{
		return $this
			->createQueryBuilder('w')
			->select('w.title')
			->where('w.id = :workId')
			->setParameter('workId', $workId)
			->getQuery()
			->getSingleScalarResult();
	}

	/**
	 * Get all works and order by artist name
	 */
    public function getAllWorks()
    {
		$qb = $this->createQueryBuilder('w');
		$qb->select('w')
			->join('w.artist', 'a')
			->orderBy('a.name', 'asc')
		;

		return $qb->getQuery()->getResult();
    }

	/**
	 *
	 * @param $nature String
	 * @return array
	 */
    public function getWorkByNature(String $nature)
    {
    	$qb = $this->createQueryBuilder('w');
    	$qb->select('w')
		    ->join('w.nature', 'n')
		    ->where('n.name = :name')
		    ->setParameter('name', $nature);

    	return $qb->getQuery()->getResult();
    }


    public function myFindByRegExpWorks($exp){
        $qb = $this->createQueryBuilder('w')
            ->select('w.id')
            ->join('w.nature', 'n')
            ->where('REGEXP(w.title, :regexp) = true')
            ->orWhere('REGEXP(w.description, :regexp) = true')
            ->orWhere('REGEXP(w.technic, :regexp) = true')
            ->orWhere('REGEXP(n.name, :regexp) = true')
            ->setParameter('regexp', $exp);

        return $qb->getQuery()->getResult();
    }

    public function myFindByRegExpArtists($exp){
        $qb = $this->createQueryBuilder('w')
            ->select('w.id')
            ->join('w.artist', 'a')
            ->where('REGEXP(a.name, :regexp) = true')
            ->orWhere('REGEXP(a.firstname, :regexp) = true')
            ->setParameter('regexp', $exp);

        return $qb->getQuery()->getResult();
    }

    public function myFindWorksByIds(array $ids){
        $qb = $this->createQueryBuilder('w')
        ->select('w.title');
        $qb->add('where', $qb->expr()->in('w.id', '?1'))
            ->setParameter('1', $ids)
            ->orderBy('w.title');

        return $qb->getQuery()->getResult();
    }

    public function myFindWorksAllFieldsByIds(array $ids){
        $qb = $this->createQueryBuilder('w');

        $qb->add('where', $qb->expr()->in('w.id', '?1'))
            ->setParameter('1', $ids)
            ->orderBy('w.title');

        return $qb->getQuery()->getResult();
    }

    public function myFindWork($artist, $title) {
        $qb = $this->createQueryBuilder('w');
        $qb
            ->where('w.title = :title')
            ->setParameter('title', $title)

            ->join('w.artist','a')
            ->andWhere('a.name = :artist')
            ->setParameter('artist', $artist);

        return $qb->getQuery()->getSingleResult();
    }

    public function myFindWorkQuant($artist, $title, $caract) {
        $qb = $this->createQueryBuilder('w');
        $qb
            ->where('w.title = :title')
            ->setParameter('title', $title)

            ->join('w.artist','a')
            ->andWhere('a.name = :artist')
            ->setParameter('artist', $artist);

            $qb->join('w.caracts', 'c')
            ->select('c.quantity')
            ->andWhere('c.dimension = :caract')
            ->setParameter('caract', $caract);

        $result = $qb->getQuery()->getSingleScalarResult();
        return $result;
    }

    public function myFindCaract($artist, $title, $caract) {
        $qb = $this->createQueryBuilder('w');
        $qb
            ->where('w.title = :title')
            ->setParameter('title', $title)

            ->join('w.artist','a')
            ->andWhere('a.name = :artist')
            ->setParameter('artist', $artist);

        $qb->join('w.caracts', 'c')
            ->select('c.id')
            ->andWhere('c.dimension = :caract')
            ->setParameter('caract', $caract);
        return $qb->getQuery()->getSingleResult();
    }
}
