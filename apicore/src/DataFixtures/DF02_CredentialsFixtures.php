<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Credential;


class DF02_CredentialsFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder():int
    {
        return 2;
    }

    public function load(ObjectManager $manager): void
    {

        //---Crear usuarios----------------------------------------------------------------------------
        $credentials = [
            ['admin', 'admin@apicore.local', '$2y$13$3gPr7jQWdp8EdyUH6c2nhOwgs/Qb.suxfNKi21HQioI1.CoNb9q8m'], //123
            ['user',   'user@apicore.local', '$2y$13$kt58x0thz2UQzwa/hKVvveq10AZZ7pd7eTQKk262uo86IHwPAil4q'], //456
            ['user2', 'user2@apicore.local', '$2y$13$c4DSMNbebGsS85NgNcb1H.5v8Hi5lgO8HSwbeH57Af.GbE9QvMSYe'], //789
        ];

        foreach ($credentials as $credential) {
            $newCredential = new Credential();
            $newCredential->setEmail($credential[1])->setPassword($credential[2]);
            $manager->persist($newCredential);
        }

        $manager->flush();
    }
}
