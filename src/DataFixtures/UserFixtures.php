<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;


    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for($i=0;$i<5;$i++){
            $user = new User();
            $user->setUsername("user".$i);
            $user->setLastname("lastName".$i);
            $user->setFirstname("firstName".$i);
            $user->setEmail("email_".$i."@fragile.fr");
            $password = $this->encoder->encodePassword($user, $i);
            $user->setPassword($password);
            $user->setActive(true);
            $user->setCreatedAt(new \DateTime());
            if($i<10){
                $user->setTelephone("0".$i."1".$i."2".$i."3".$i."4".$i);
            }else{
                $user->setTelephone("0011223344");
            }
            $user->setSite($this->getReference("site1"));
            $user->setPhoto("default_profile_pic_fixtures.png");
            $this->addReference("user".$i, $user);
            $manager->persist($user);
        }
        $manager->flush();
    }

    public function getDependencies() {
        return array(
            SiteFixtures::class,
        );
    }
}
