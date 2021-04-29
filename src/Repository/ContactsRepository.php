<?php

namespace App\Repository;

use App\Entity\Contacts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contacts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contacts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contacts[]    findAll()
 * @method Contacts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contacts::class);
    }

        public function filtre ($filtre , $nom , $user){
            if($filtre!=null && $nom == null){

                if($filtre['Nom']== null){
                    $Nom= '%';
                }else{
                    $Nom = $filtre['Nom'];
                }

                if($filtre['Prenom']== null){
                    $Prenom= '%';
                }else{
                    $Prenom = $filtre['Prenom'];
                }

                if($filtre['Mail']== null){
                    $Mail= '%';
                }else{
                    $Mail = $filtre['Mail'];
                }

                if($filtre['Telephone']== null){
                    $Telephone= '%';
                }else{
                    $Telephone = $filtre['Telephone'];
                }

                if($filtre['Metier']== null){
                    $Metier= '%';
                }else{
                    $Metier = $filtre['Metier'];
                }

                if($filtre['ville']== null){
                    $ville= '%';
                }else{
                    $ville = $filtre['ville'];
                }

                if($filtre['News']== null){
                    $News= '%';
                }else{
                    $News = $filtre['News'];
                }

                if($filtre['Date']== null){
                    $Date= '%';
                }else{
                    $Date = $filtre['Date'];
                }

                if($filtre['Meteo']== null){
                    $Meteo= '%';
                }else{
                    $Meteo = $filtre['Meteo'];
                }

                if($filtre['Tags']== null){
                    $Tags= '%';
                }else{
                    $Tags = $filtre['Tags'];
                }

                return $this->createQueryBuilder('c')
                    ->andWhere('c.user = :User')
                    ->andWhere('c.Nom LIKE :Nom')
                    ->andWhere('c.Prenom LIKE :Prenom')
                    ->andWhere('c.Mail LIKE :Mail')
                    ->andWhere('c.Telephone LIKE :Telephone')
                    ->andWhere('c.Metier LIKE :Metier')
                    ->andWhere('c.ville LIKE :ville')
                    ->andWhere('c.News LIKE :News')
                    ->andWhere('c.Date LIKE :Date')
                    ->andWhere('c.Meteo LIKE :Meteo')
                    ->andWhere('c.Tags LIKE :Tags')
                    ->setParameters([
                            'User'=>$user,
                            'Nom'=> '%'.$Nom.'%',
                            'Prenom'=> '%'.$Prenom.'%',
                            'Mail'=> '%'.$Mail.'%',
                            'Telephone'=> '%'.$Telephone.'%',
                            'Metier'=> '%'.$Metier.'%',
                            'ville'=> '%'.$ville.'%',
                            'News'=> '%'.$News.'%',
                            'Date'=> '%'.$Date.'%',
                            'Meteo'=> '%'.$Meteo.'%',
                            'Tags'=> '%'.$Tags.'%',

                    ])
                    ->getQuery()
                ->getResult();
            }
            if($filtre==null && $nom != null){
                return $this->createQueryBuilder('c')
                    ->andWhere('c.user = :User')
                    ->andWhere('c.Nom LIKE :Nom')
                    ->setParameters([
                            'User'=>$user,
                            'Nom'=> $nom['Nom'],
                    ])
                    ->getQuery()
                ->getResult();
            }

        }
    // /**
    //  * @return Contacts[] Returns an array of Contacts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contacts
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    private function setval($vals){
        foreach($vals as $val){
            
        }
    }
}
