<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;


class UsersFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder():int
    {
        return 3;
    }

    public function load(ObjectManager $manager): void
    {

        //---Crear usuarios----------------------------------------------------------------------------
        $users = [
            ['admin', 'admin@apicore.local', '$2y$13$3gPr7jQWdp8EdyUH6c2nhOwgs/Qb.suxfNKi21HQioI1.CoNb9q8m'], //123
            ['user',   'user@apicore.local', '$2y$13$kt58x0thz2UQzwa/hKVvveq10AZZ7pd7eTQKk262uo86IHwPAil4q'], //456
            ['user2', 'user2@apicore.local', '$2y$13$c4DSMNbebGsS85NgNcb1H.5v8Hi5lgO8HSwbeH57Af.GbE9QvMSYe'], //789
        ];

        foreach ($users as $user) {
            $newUser = new User();
            $newUser->setEmail($user[1])->setPassword($user[2]);
            $manager->persist($newUser);
        }

        $manager->flush();
    }
}
