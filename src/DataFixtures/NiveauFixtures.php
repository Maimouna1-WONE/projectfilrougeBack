<?php

use App\Entity\Niveau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class NiveauFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $niv=new Niveau();
        $niv->setLibelle("nivfix")
            ->setCritereEvaluation("fil rouge")
            ->setAction("voici le niv fix");
        $manager->persist($niv);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return  ['niveau'];
    }
}