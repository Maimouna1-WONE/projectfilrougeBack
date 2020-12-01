<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public const PROFIL_APP_REFERENCE = 'apprenant';
    public const PROFIL_ADM_REFERENCE = 'admin';
    public const PROFIL_CM_REFERENCE = 'cm';
    public const PROFIL_FORM_REFERENCE = 'formateur';
    public function load(ObjectManager $manager)
    {
        $profil=new Profil();
        $profil->setLibelle("APPRENANT");
        $profil->setArchive(0);
        $manager->persist($profil);
        $manager->flush();
        $this->addReference(self::PROFIL_APP_REFERENCE, $profil);

        $profil1=new Profil();
        $profil1->setLibelle("ADMIN");
        $profil1->setArchive(0);
        $manager->persist($profil1);
        $manager->flush();
        $this->addReference(self::PROFIL_ADM_REFERENCE, $profil1);

        $profil2=new Profil();
        $profil2->setLibelle("CM");
        $profil2->setArchive(0);
        $manager->persist($profil2);
        $manager->flush();
        $this->addReference(self::PROFIL_CM_REFERENCE, $profil2);

        $profil3=new Profil();
        $profil3->setLibelle("FORMATEUR");
        $profil3->setArchive(0);
        $manager->persist($profil3);
        $manager->flush();
        $this->addReference(self::PROFIL_FORM_REFERENCE, $profil3);
    }
}
