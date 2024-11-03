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

        //TODO: definir donde se almacenaran las credenciales para importar datos iniciales, y que no se suban al repositorio.
        //---Crear usuarios----------------------------------------------------------------------------
        $parameters = [
            //---APIMF-CONFIGURACION
            ['string', 'API-MF-URL', 'https://apimf.motorflash.com'],
        ];

        foreach ($parameters as $parameter) {
            $newConfigParameter = new ConfigurationParameter();
            $newConfigParameter->setType($parameter[0])->setName($parameter[1])->setValueStr($parameter[2]);
            $manager->persist($newConfigParameter);
        }

        $manager->flush();
    }


}
