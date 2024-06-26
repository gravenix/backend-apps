<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserFriend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<UserFriend>
 *
 * @method UserFriend|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFriend|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFriend[]    findAll()
 * @method UserFriend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFriendRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFriend::class);
    }

    public function findUserInvitation(Uuid $userId, Uuid $friendId): ?UserFriend
    {
        return $this->createQueryBuilder('uf')
            ->innerJoin(User::class, 'u', Join::WITH, 'u = uf.user')
            ->innerJoin(User::class, 'f', Join::WITH, 'f = uf.friend')
            ->where('uf.user_id = :invitedId')
            ->andWhere('uf.friend_id = :userId')
            ->andWhere('uf.accepted = 0')
            ->setParameter('userId', $userId->toBinary())
            ->setParameter('invitedId', $friendId->toBinary())
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return UserFriend[]
     */
    public function getFriendRequestsFor(Uuid $userId): array
    {
        return $this->createQueryBuilder('uf')
            ->select('uf, u')
            ->leftJoin(User::class, 'u', Join::WITH, 'u = uf.user')
            ->where('uf.friend_id = :userId')
            ->andWhere('uf.accepted = 0')
            ->setParameter('userId', $userId->toBinary())
            ->getQuery()
            ->getResult();
    }

    /**
     * @return UserFriend[]
     */
    public function getUserFriendsFor(Uuid $userId): array
    {
        return $this->createQueryBuilder('uf')
            ->select('uf, u, f')
            ->leftJoin(User::class, 'u', Join::WITH, 'u = uf.friend')
            ->leftJoin(User::class, 'f', Join::WITH, 'f = uf.user')
            ->where('uf.user_id = :userId')
            ->orWhere('uf.friend_id = :userId')
            ->andWhere('uf.accepted = 1')
            ->setParameter('userId', $userId->toBinary())
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return UserFriend[] Returns an array of UserFriend objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?UserFriend
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
