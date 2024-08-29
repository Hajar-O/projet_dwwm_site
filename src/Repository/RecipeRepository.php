<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipe>
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

//    /**
//     * @return Recipe[] Returns an array of Recipe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipe
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function RecipesByCategory(Category $category)
    {
        return $this->createQueryBuilder('r')
            ->join('r.idCategory', 'c')
            ->where('c = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }
    public function getlastRecipe(){
        return $this->createQueryBuilder('r')
            ->orderBy('r.publishedAt', 'DESC')  // Tri par date de création décroissante
            ->setMaxResults(1)  // Limite le résultat à une seule recette
            ->getQuery()
            ->getOneOrNullResult();  // Retourne la dernière recette ou null s'il n'y a pas de résultats

    }

}
