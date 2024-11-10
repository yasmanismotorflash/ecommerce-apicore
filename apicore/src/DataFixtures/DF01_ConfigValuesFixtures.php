<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ConfigValue;


class DF01_ConfigValuesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder():int
    {
        return 1;
    }

    public function load(ObjectManager $manager): void
    {

        //TODO: definir donde se almacenaran las credenciales para importar datos iniciales, y que no se suban al repositorio.
        //---Crear usuarios----------------------------------------------------------------------------
        $configValues = [
            //---APIMF-CONFIGURACION
            ['string', 'API-MF-URL', 'https://apimf.motorflash.com'],
        ];

        foreach ($configValues as $cfgValue) {
            $newCfgValue = new ConfigValue();
            $newCfgValue->setType($cfgValue[0])->setName($cfgValue[1])->setValueStr($cfgValue[2]);
            $manager->persist($newCfgValue);
        }

        $manager->flush();
    }


}
