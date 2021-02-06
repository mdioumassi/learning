<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Users|null find($id, $lockMode = null, $lockVersion = null)
 * @method Users|null findOneBy(array $criteria, array $orderBy = null)
 * @method Users[]    findAll()
 * @method Users[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UsersRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Users) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return Users[] Returns an array of Users objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Users
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getUserTrainingPlanning(?UserInterface $user)
    {
        return $this->createQueryBuilder('u')
            ->select(
//                'u.civility,
//                u.firstname,
//                u.lastname,
//                u.email,
//                u.phone_mobile,
                'l.label as level,
                t.label as training,
                c.start,
                c.end')
            ->innerJoin('u.levels', 'l')
            ->innerJoin('u.calendars', 'c')
            ->innerJoin('l.trainings', 't')
            ->andWhere('u.id = :val')
           ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getLevelsByUsers(?UserInterface $user)
    {
        return $this->createQueryBuilder('u')
            ->select('l.label as level')
            ->innerJoin('u.levels', 'l')
            ->andWhere('u.id = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getTrainingByLevelsByUsers(?UserInterface $user)
    {
        return $this->createQueryBuilder('u')
            ->select('t.label as training, l.label as level')
            ->innerJoin('u.levels', 'l')
            ->innerJoin('l.trainings', 't')
            ->andWhere('u.id = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getPlanningByUser(?UserInterface $user)
    {
        return $this->createQueryBuilder('u')
            ->select('distinct(l.label) as level, c.start, c.end')
            ->innerJoin('u.levels', 'l')
            ->innerJoin('u.calendars', 'c')
            ->innerJoin('l.calendars', 'lc')
            ->andWhere('u.id = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
            ;
    }
}
