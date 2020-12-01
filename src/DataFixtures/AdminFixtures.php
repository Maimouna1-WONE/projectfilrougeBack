<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Apprenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder )
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $adm= new Admin();
        $adm->setLogin("admintoken");
        $password = $this->encoder->encodePassword ($adm, 'pass_1234' );
        $adm->setPassword ($password );
        $adm->setArchive(0);
        $adm->setTelephone("776547688");
        $adm->setNom("maina");
        $adm->setAdresse("dfjfsjd");
        $adm->setEmail("jefdssfsd@gmail.com");
        $adm->setGenre("F");
        $adm->setPrenom("jzkjdzq");
        $adm->setProfil($this->getReference(ProfilFixtures::PROFIL_ADM_REFERENCE));
        $manager->persist($adm);
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
