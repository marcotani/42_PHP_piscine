<?php
namespace App\ex09\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function findAllWithRelations(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.bankAccount', 'ba')
            ->leftJoin('p.addresses', 'a')
            ->addSelect('ba')
            ->addSelect('a')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findWithJoinAndFilter($name, $sort, $order): array
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.bankAccount', 'ba')
            ->leftJoin('p.addresses', 'a')
            ->addSelect('ba')
            ->addSelect('a');
        if ($name) {
            $qb->andWhere('p.name LIKE :name')
               ->setParameter('name', "%$name%");
        }
        // Map sort fields to entity fields
        $sortMap = [
            'id' => 'p.id',
            'name' => 'p.name',
            'birthdate' => 'p.birthdate',
            'city' => 'a.city',
            'account_number' => 'ba.accountNumber',
        ];
        $sortField = $sortMap[$sort] ?? 'p.name';
        $qb->orderBy($sortField, $order);
        return $qb->getQuery()->getResult();
    }
}
