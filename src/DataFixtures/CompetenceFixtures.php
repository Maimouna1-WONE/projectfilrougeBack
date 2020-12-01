<?php

use App\Entity\Competence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompetenceFixtures extends Fixture
{

    public const COMPETENCE_REFERENCE = 'competence';
    public function load(ObjectManager $manager)
    {
        $comp=new Competence();
        $comp->setLibelle("grpcompefix1");
        $comp->setDescription("voici le grpcompe1");
        $manager->persist($comp);
        $manager->flush();
        $this->addReference(self::COMPETENCE_REFERENCE, $comp);
    }
}