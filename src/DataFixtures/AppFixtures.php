<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Ad; 
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image; 
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// use Xvladqt\Faker\LoremFlickrProvider;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;    

    }

    public function load(ObjectManager $manager){
        
        $faker = Factory::create('fr-FR');
        // $faker->addProvider(new LoremFlickrProvider($faker));
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Cyril')
                    ->setLastName('Vassallo')
                    ->setPicture('img/photo.png')
                    ->setEmail('cyrilvssll34@gmail.com')
                    ->setIntroduction($faker->sentence())
                    ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                    ->setHash($this->encoder->encodePassword($adminUser,'Cyril!123'))
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);
        
        //nous gérons les utilisateurs
        $users = [];
        for($i = 1; $i<=10 ; $i++){
            $user =  new User();
            $genres = ['male','female'];
            $genre = $faker->randomElement($genres);
    
            
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1,99) . '.jpg';
            
            // if($genre = 'male') $picture = $picture.'male/' . $pictureID;
            // else $picture = $picture. 'female/'. $pictureID;
            $picture .=  ($genre == 'male' ? 'men/': 'women/'). $pictureId;

            $hash = $this->encoder->encodePassword($user,'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] =  $user;
        }
        //nous gérons es annonces
        for($i=1; $i<=30; $i++){

            $title = $faker->sentence();
            $coverImage = $faker->unique()->imageUrl(1000, 350, 'city');
            $introduction =  $faker->paragraph(2);
            $content = '<p>'.join('</p><p>', $faker->paragraphs(5)).'</p>';
            //on prend au hasard un utilisateur dans le tableau
            $user = $users[mt_rand(0, count($users)- 1)];

            $ad = new Ad();

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);

            for($j= 1; $j<= mt_rand(2,5);$j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl(500,350))
                        ->setCaption($faker->sentence())
                        ->setAd($ad);

                $manager->persist($image);
            }

            $manager->persist($ad);
            //Gestion des réservation
            for($j = 1; $j <= mt_rand(0,10); $j++){
                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                $duration = mt_rand(3,10);
                //gestion de la date de fin
                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0 , count($users) - 1)];
                $paragraph = $faker->paragraph(1);

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($paragraph);

                $manager->persist($booking);

                //Gestion des commentaires
                if(mt_rand(0,1)){
                    $comment = new Comment();
                    $comment->setContent($faker->paragraph())
                            ->setRating(mt_rand(1,5))
                            ->setAuthor($booker)
                            ->setAd($ad);

                    $manager->persist($comment);
                        
                }
            }
        }

        $manager->flush();
    }
}
