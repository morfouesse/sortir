<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\SearchData;
use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Activity::class);
    }

    // /**
    //  * @return Activity[] Returns an array of Activity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Activity
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /* public function findSearch(SearchData $data)
     {
         $query = $this->getSearchQuery(data)->getQuery();
     }*/

    /**
     * request of dql for the filter
     * @param SearchData $data
     * @return array
     */
    public function findSearch(SearchData $data): array
    {
        $query = $this
            ->createQueryBuilder('a')
            ->select('a,c')
            ->join('a.campus', 'c');
        if (!empty($data->q)) {
            $query = $query
                ->andWhere('a.name LIKE :q')
                ->setParameter('q', "%{$data->q}%");

        }
        if (!empty($data->campuses)) {
            $query = $query
                //probleme prend la premiere val par default
                ->andWhere('c.id IN (:campus)')
                ->setParameter('campus', $data->campuses);
        }
        if (!empty($data->startDate)) {
            $query = $query
                //TODO:probleme prend la premiere val par default
                ->andWhere('c.id IN (:campus)')
                ->setParameter('campus', $data->campuses);
        }

        if (!empty($data->startDate)) {
            $query = $query
                ->andWhere('a.startDateTime >=:startDate')
                ->setParameter('startDate', $data->startDate);
        }
        if (!empty($data->lastDate)) {
            $query = $query
                ->andWhere('a.startDateTime <=:lastDate')
                ->setParameter('lastDate', $data->lastDate);
        }


        if(!empty(($data->userOwnActivities))){
            $query = $query
                ->andWhere('a.userOwner.id =: userOwner')
                ->setParameter('userOwner', $this->getUser()->getId());
        }

        if(!empty(($data->usersActivities))){
            $query = $query
                ->andWhere('a.users.id =:usersActivities')
                ->setParameter('usersActivities', $data->usersActivities);
        }

        if(!empty(($data->userNotActivities))){
            $query = $query
                ->andWhere('a.users.id !=:lastDate')
                ->setParameter('userNotActivities', $data->userNotActivities);
        }

        if(!empty(($data->pastActivities))){
            $query = $query
                ->andWhere('a. =:pastActivities')
                ->setParameter('pastActivities', $data->pastActivities);
        }

        return $query->getQuery()->getResult();
    }
}
