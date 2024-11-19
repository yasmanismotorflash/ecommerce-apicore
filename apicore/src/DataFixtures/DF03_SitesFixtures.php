<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Site;


class DF03_SitesFixtures extends Fixture implements OrderedFixtureInterface
{
    public function getOrder():int
    {
        return 3;
    }

    public function load(ObjectManager $manager): void
    {
        $sites = [
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

        foreach ($sites as $site) {
            $newSite = new Site();
            $newSite->setName($site['name'])
                ->setUrl($site['url'])
                ->setMfSiteId($site['mfSiteId'])
                ->setApicoreClientId($site['apicoreClientId'])
                ->setApicoreClientSecret($site['apicoreClientSecret'])
                ->setApimfClientId($site['apimfClientId'])
                ->setApimfClientSecret($site['apimfClientSecret'])
                ->setActive($site['active']);

            $manager->persist($newSite);
        }

        $manager->flush();
    }
}
