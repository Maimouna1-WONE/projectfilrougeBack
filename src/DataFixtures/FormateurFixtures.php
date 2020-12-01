<?php

namespace App\DataFixtures;

use App\Entity\Formateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormateurFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder )
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $form= new Formateur();
        $form->setLogin("formateur1");
        $password = $this->encoder->encodePassword ($form, 'pass_1234' );
        $form->setPassword ($password );
        $form->setArchive(0);
        $form->setTelephone("776547688");
        $form->setNom("maina");
        $form->setAdresse("dfjfsjd");
        $form->setEmail("jefdfsd@gmail.com");
        $form->setGenre("F");
        $form->setPrenom("jzkjdzq");
        $form->setProfil($this->getReference(ProfilFixtures::PROFIL_FORM_REFERENCE));
        $manager->persist($form);
        $manager->flush();
    }
    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
