<?php

use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupeFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $groupe=new Groupe();
        $groupe->setLibelle("grpfix");
        $groupe->setPeriode("1 semaine");
        $manager->persist($groupe);
        $manager->flush();
    }
}