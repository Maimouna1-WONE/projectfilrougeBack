<?php

use App\Entity\Referentiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ReferentielFixtures extends Fixture
{

    public const REFERENTIEL_REFERENCE = 'referentiel';
    public function load(ObjectManager $manager)
    {
        $ref=new Referentiel();
        $ref->setLibelle("referentiel1");
        $ref->setPresentation("voici le referentiel1");
        $manager->persist($ref);
        $manager->flush();
        $this->addReference(self::REFERENTIEL_REFERENCE, $ref);
    }
}