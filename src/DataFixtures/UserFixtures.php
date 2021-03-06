<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @property UserPasswordEncoderInterface encoder
 */
class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
       $user = new User();
       $user->setUsername('admin');
       $user->setPassword($this->encoder->encodePassword($user,'demo'));
       $manager->persist($user);
       $manager->flush();
    }
}
