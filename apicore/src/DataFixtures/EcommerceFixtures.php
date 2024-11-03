<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ecommerce;


class EcommerceFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $manager): void
    {
        $ecommerces = [
            [
                'name'=>'DaswelAuto',
                'url'=>'www.daswellauto.es',
                'mfSiteId'=>67,
                'apicoreClientId'=>'---',
                'apicoreClientSecret'=>'---',
                'apimfClientId'=>'-167c6c95eaddec84e555512c342f4b6e50a',
                'apimfClientSecret'=>'0737dd801aee0031a8e874cfb54d9bacdace371ffccc80d598bdba602d7a3dba',
                'active'=>true
            ]
        ];

        foreach ($ecommerces as $ecommerce) {
            $newEcommerce = new Ecommerce();
            $newEcommerce->setName($ecommerce['name'])
                ->setUrl($ecommerce['url'])
                ->setMfSiteId($ecommerce['mfSiteId'])
                ->setApicoreClientId($ecommerce['apicoreClientId'])
                ->setApicoreClientSecret($ecommerce['apicoreClientSecret'])
                ->setApimfClientId($ecommerce['apimfClientId'])
                ->setApimfClientSecret($ecommerce['apimfClientSecret'])
                ->setActive($ecommerce['active']);

            $manager->persist($newEcommerce);
        }

        $manager->flush();
    }
}
