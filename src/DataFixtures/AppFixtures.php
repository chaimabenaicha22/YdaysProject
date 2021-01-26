<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\User;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        //nos gerons les utilisateur
        $users = [];
        $genres = ['male', 'female'];
        for ($k = 1; $k <= 10; $k++) {
            $user = new User();
            $genre = $faker->randomElement($genres);

            $picture =  'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99) . '.jpg';

            $picture .=  ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
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
            $manager->persist($ad);}
        $manager->flush();
    }
}