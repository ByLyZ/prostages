<?php

namespace App\Repository;

use App\Entity\Stages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stages[]    findAll()
 * @method Stages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stages::class);
    }

    // /**
    //  * @return Stages[] Returns an array of Stages objects
    //  */

    public function findByStageParNomEntreprise($nomEntreprise)
    {
        return $this->createQueryBuilder('s')
            ->select('s,e,f')
            ->join('s.entreprise','e')
            ->join('s.formations','f')
            ->where('e.nom = :nomEntreprise')
            ->setParameter('nomEntreprise', $nomEntreprise)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByStageParFormation($nomFormation)
    {
        $gestionnaireEntite=$this->getEntityManager();

        $requete = $gestionnaireEntite->createQuery(
            'SELECT stage,formation,entreprise
            FROM App\Entity\Stages stage
            JOIN stage.formations formation
            JOIN stage.entreprise entreprise
            WHERE formation.nom = :nomFormation'
        );
        $requete->setParameter('nomFormation', $nomFormation);

        return $requete->execute();
    }
    
    public function AfficherToutStage()
    {
        return $this->createQueryBuilder('s')
            ->select('s,e,f')
            ->join('s.entreprise','e')
            ->join('s.formations','f')
            ->orderBy('s.id','DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Stages
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
