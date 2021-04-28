<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use App\Entity\Utilisateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        //nos gerons les utilisateur
        $users = [];
        $genres = ['male', 'female'];
        for ($k = 1; $k <= 10; $k++) {
            $user = new Utilisateur();
            $genre = $faker->randomElement($genres);

            $picture =  'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .=  ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $hash= ($this->encoder->encodePassword($user, 'password'));
            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setHash($hash)
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setPicture($picture);

            $manager->persist($user);
            $users[] = $user;
        }
        for ($i = 1; $i <= 30; $i++) {
            $ad = new  Ad();
            $user = $users[mt_rand(0, count($users) - 1)];
            $ad->setTitle($faker->sentence())
            ->setCoverImage($faker->imageUrl(1000, 350))
            ->setIntroduction($faker->paragraph(2))
            ->setContent('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
            ->setPrice(mt_rand(40, 200))
            ->setRooms(mt_rand(1, 5))
            ->setAuthor($user);
            for ($j = 1; $j <= mt_rand(2, 5); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                $manager->persist($image);}
                 //gestion des reservations 
            for ($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();
                $creatAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');
                //gestion de la date de fin 
                $duration = mt_rand(3, 10);
                $endDate = clone ($startDate)->modify("+ $duration Days ");
                $amount = $ad->getPrice() * $duration;
                $booker = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($creatAt)
                    ->setAmount($amount)
                    ->setComment($comment);


                $manager->persist($booking);
               
            }
            $manager->persist($ad);}
        $manager->flush();
    }
}