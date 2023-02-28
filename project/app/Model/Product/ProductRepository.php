<?php

namespace App\Model\Product;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ProductRepository
{

    private EntityRepository $entityRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityRepository = $entityManager->getRepository($this->getEntityName());
    }

    protected function getEntityName(): string
    {
        return Product::class;
    }

    public function findOne(array $criteria, ?array $orderBy = null): ?object
    {
        return $this->entityRepository->findOneBy($criteria, $orderBy);
    }


    public function findBySku(string $sku): object
    {
        return $this->findOne(['sku' => $sku]);
    }
}






