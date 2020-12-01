<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use App\Repository\ApprenantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApprenantFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder )
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $app= new Apprenant();
        $app->setLogin("apprenant3");
        $password = $this->encoder->encodePassword ($app, 'pass_1234' );
        $app->setPassword ($password );
        $app->setArchive(0);
        $app->setStatut("actif");
        $app->setTelephone("776547688");
        $app->setNom("maina");
        $app->setAdresse("dfjfsjd");
        $app->setEmail("jefdfsd@gmail.com");
        $app->setGenre("F");
        $app->setPrenom("jzkjdzq");
        $app->setProfil($this->getReference(ProfilFixtures::PROFIL_APP_REFERENCE));
        $manager->persist($app);
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
