<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');


        /* création d'un role admin + administrateur */
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Jordan')
                ->setLastName('Berti')
                ->setEmail('admin@epse.be')
                ->setHash($this->encoder->encodePassword($adminUser,'password'))
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                ->addUserRole($adminRole);

        $manager->persist($adminUser);        



        // gestion des utilisateurs 

        $users = []; // initialisation d'un tableau pour associer Ad et User
        $genres = ['male','femelle'];

        for($i=1; $i<=10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres); // donne un élément aléatoire du tableau passé en paramètre

            $picture = "https://randomuser.me/api/portraits/";
            $pictureId= $faker->numberBetween(1,99).'jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) .'</p>')
                ->setHash($hash)
                ;
            $manager->persist($user);    
            $users[] = $user; // pour ajouter un utilisateur dans l'annonce
        }


        // gestion des annonces

        for($i=1 ; $i <= 30; $i++){
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000,350);
            $introduction = $faker->paragraph(2);
            $content = "<p>".join("</p><p>",$faker->paragraphs(5))."</p>";

            $user = $users[mt_rand(0, count($users)-1)];

            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);


            for($j=1; $j <= mt_rand(2,5) ; $j++){

                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            } 
            
            // gestion des réservations 

            for($w = 1; $w <= mt_rand(0,10); $w++){
                $booking = new Booking();
                $createAt = $faker->dateTimeBetween('-6 months','-4 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                $duration = mt_rand(3,10);
                // objet datetime de php
                // pour modifier un datetime on utilise la méthode modify 
                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;
                $comment = $faker->paragraph();
                $booker = $users[mt_rand(0, count($users) -1)];

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createAt)
                        ->setAmount($amount)
                        ->setComment($comment);

                $manager->persist($booking);

            }


            $manager->persist($ad);
            
        }




        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
