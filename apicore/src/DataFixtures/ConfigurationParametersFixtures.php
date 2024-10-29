<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ConfigurationParameter;


class ConfigurationParametersFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager): void
    {

        //---Crear usuarios----------------------------------------------------------------------------
        $parameters = [
            //---APIMF-CONFIGURACION
            ['string', 'API-MF-URL', 'https://apimf.motorflash.com'],
            ['string', 'API-MF-CLIENT-ID', '-167c6c95eaddec84e555512c342f4b6e50a'],
            ['string', 'API-MF-CLIENT-SECRET', '0737dd801aee0031a8e874cfb54d9bacdace371ffccc80d598bdba602d7a3dba'],
        ];

        foreach ($parameters as $parameter) {
            $newConfigParameter = new ConfigurationParameter();
            $newConfigParameter->setType($parameter[0])->setName($parameter[1])->setValueStr($parameter[2]);
            $manager->persist($newConfigParameter);
        }

        $manager->flush();
    }


}
