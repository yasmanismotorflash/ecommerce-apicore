<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ConfigurationParameter;


class ConfigurationParametersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        //---Crear usuarios----------------------------------------------------------------------------
        $parameters = [
            //---APIMF-CONFIGURACION
            ['string', 'API-MF-URL', 'https://apimf.motorflash.com'],
            ['string', 'API-MF-CLIENT-ID', ''],
            ['string', 'API-MF-CLIENT-SECRET', ''],

        ];

        foreach ($parameters as $parameter) {
            $newConfigParameter = new ConfigurationParameter();
            $newConfigParameter->setType($parameter[0])->setValueStr($parameter[1])->setName($parameter[2]);
            $manager->persist($newConfigParameter);
        }

        $manager->flush();
    }
}
