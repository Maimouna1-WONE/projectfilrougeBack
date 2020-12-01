<?php

use App\Entity\GroupeTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupeTagFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $gt=new GroupeTag();
        $gt->setLibelle("grptag1");
        $gt->addTag($this->getReference(TagFixtures::TAG_REFERENCE));
        $manager->persist($gt);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            TagFixtures::class,
        );
    }
}