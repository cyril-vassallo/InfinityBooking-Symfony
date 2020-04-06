<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

Class StatsService {

    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function getStats(){
        $users      = $this->getUsersCount();
        $ads        = $this->getAdsCount();
        $bookings   = $this->getBookingsCount();
        $comments   = $this->getCommentsCount();

        return compact('users','ads','bookings','comments');
    }

    public function getAdsStats($direction){
        $query = $this->manager->createQuery(
            'SELECT AVG(c.rating) as note , a.title , a.id , u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note '. $direction
        );

        return $query->setMaxResults(5)->getResult();
        
    }

    public function getUsersCount(){
        return $this->manager->createQuery('SELECT COUNT(user) FROM App\Entity\user user')->getSingleScalarResult();
   
    }
    public function getAdsCount(){
        return  $this->manager->createQuery('SELECT COUNT(ad) FROM App\Entity\ad ad')->getSingleScalarResult();
        
    }
    public function getBookingsCount(){
        return  $this->manager->createQuery('SELECT COUNT(booking) FROM App\Entity\booking booking')->getSingleScalarResult();
       
    }
    public function getCommentsCount(){
        return  $this->manager->createQuery('SELECT COUNT(comment) FROM App\Entity\comment comment')->getSingleScalarResult();
       
    }
}