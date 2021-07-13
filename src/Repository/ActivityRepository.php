<?php

namespace App\Repository;


use App\Service\SearchData;
use App\Entity\Activity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;


/**
 * @method Activity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activity[]    findAll()
 * @method Activity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityRepository extends ServiceEntityRepository
{
    public $security;
    public function __construct(ManagerRegistry $registry, Security $s)
    {
        parent::__construct($registry, Activity::class);
        $this->security = $s;
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
        //:TODO faire en sorte de ne pas afficher les sorties dont l'état est : 'archieved'

        $query = $this
            ->createQueryBuilder('a')
            ->select('a,c,u,uO')
            ->join('a.campus', 'c')
            ->join('a.userOwner', 'uO')
            ->join('a.users', 'u');
        if (!empty($data->q)) {
            $query = $query
                ->andWhere('a.name LIKE :q')
                ->setParameter('q', "%{$data->q}%");

        }
        if (!empty($data->campuses)) {
            $query = $query
                ->andWhere('a.campus IN (:campus)')
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
                ->andWhere('uO.id IN (:userOwnActivities)')
                ->setParameter('userOwnActivities', $this->security->getUser()->getId());

        }

        if(!empty(($data->usersActivities))){
            $query = $query
                ->andWhere('u.id IN (:usersActivities)')
                ->setParameter('usersActivities', $this->security->getUser()->getId());
        }

        if(!empty(($data->userNotActivities))){
            $query = $query
                ->andWhere('u.id NOT IN (:userNotActivities)')
                ->setParameter('userNotActivities', $this->security->getUser()->getId());
        }

        if(!empty(($data->pastActivities))){
            $query = $query
                ->andWhere('a.startDateTime < CURRENT_DATE()');
        }


        return $query->getQuery()->getResult();
    }
}
