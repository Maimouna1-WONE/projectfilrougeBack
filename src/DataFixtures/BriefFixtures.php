<?php

use App\Entity\Brief;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class BriefFixtures extends Fixture implements FixtureGroupInterface
{
    //public const BRIEF_REFERENCE = 'brief';
    public function load(ObjectManager $manager)
    {
        $briref=new Brief();
        $briref->setLangue("anglais")
                ->setTitre("fil rouge")
                ->setDescription("voici le brief anglais")
                ->setContexte("covid")
                ->setModalitePedagogique("front et back")
                ->setModaliteEvaluation("dsdss")
                ->setCriterePerformance("sdseds")
                ->setDateCreation(new \DateTime)
                ->setStatut("statut");
        $manager->persist($briref);
        $manager->flush();
        //$this->addReference(self::BRIEF_REFERENCE, $briref);
    }

    public static function getGroups(): array
    {
        return  ['brief'];
    }
}