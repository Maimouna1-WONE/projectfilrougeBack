<?php

use App\Entity\GroupeCompetence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupeCompetenceFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $gc=new GroupeCompetence();
        $gc->setLibelle("grpcompe1");
        $gc->setDescription("voici le grpcompe1");
        $gc->addCompetence($this->getReference(CompetenceFixtures::COMPETENCE_REFERENCE));
        $manager->persist($gc);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            CompetenceFixtures::class
        );
    }

}