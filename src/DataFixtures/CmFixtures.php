<?php

namespace App\DataFixtures;

use App\Entity\Cm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CmFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder )
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $cm= new Cm();
        $cm->setLogin("cm1");
        $password = $this->encoder->encodePassword ($cm, 'pass_1234' );
        $cm->setPassword ($password );
        $cm->setArchive(0);
        $cm->setTelephone("776547688");
        $cm->setNom("maina");
        $cm->setAdresse("dfjfsjd");
        $cm->setEmail("jefdfsd@gmail.com");
        $cm->setGenre("F");
        $cm->setPrenom("jzkjdzq");
        $cm->setProfil($this->getReference(ProfilFixtures::PROFIL_CM_REFERENCE));
        $manager->persist($cm);
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
