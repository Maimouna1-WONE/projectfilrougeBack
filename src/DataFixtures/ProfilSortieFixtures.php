<?php

use App\Entity\ProfilSortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilSortieFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $ps=new ProfilSortie();
        $ps->setLibelle("ps1");
        $manager->persist($ps);
        $manager->flush();
    }
}