<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        //Agregar usuario admin.
        $admin = new User();
        $admin->setEmail('admin@apicore.local')
              ->setPassword('$2y$13$3gPr7jQWdp8EdyUH6c2nhOwgs/Qb.suxfNKi21HQioI1.CoNb9q8m');

        $manager->persist($admin);





        $manager->flush();
    }
}
