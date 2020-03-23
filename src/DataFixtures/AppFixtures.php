<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Ad; 
use App\Entity\User;
use App\Entity\Image; 
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
                ->setDescription('<p>'.join('</p><p>', $faker->sentences(3)).'</p>')
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);
            $users[] =  $user;
        }
        //nous gérons es annonces
        for($i=1; $i<=30; $i++){

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
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

                $image->setUrl($faker->imageUrl())
                        ->setCaption($faker->sentence())
                        ->setAd($ad);

                $manager->persist($image);
            }

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
