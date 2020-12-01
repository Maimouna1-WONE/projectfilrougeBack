<?php

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture implements FixtureGroupInterface
{

    public const TAG_REFERENCE = 'tag';
    public function load(ObjectManager $manager)
    {
        $tag=new Tag();
        $tag->setLibelle("tag1");
        $manager->persist($tag);
        $manager->flush();
        //$this->addReference(self::TAG_REFERENCE, $tag);
    }

    public static function getGroups(): array
    {
        return array(
            "tag"
        );
    }
}