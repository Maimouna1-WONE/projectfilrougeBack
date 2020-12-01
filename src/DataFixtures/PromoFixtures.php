<?php

use App\Entity\Promo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PromoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $promo=new Promo();
        $promo->setLibelle("promofix");
        $promo->setLangue("francais");
        $promo->setLibelle("voici la promofix");
        $manager->persist($promo);
        $manager->flush();
    }
}