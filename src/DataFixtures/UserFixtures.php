<?php

namespace App\DataFixtures;

use App\Entity\Site;
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
        $i=0;
        $user = new User;
        $user->setUsername("user1");
        $user->setLastname("Martin");
        $user->setFirstname("Gérard");
        $user->setEmail("G.Martin25@gmail.com");
        $password=$this->encoder->encodePassword($user,"1");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0238659897");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_ADMIN"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;

        $user = new User;
        $user->setUsername("user2");
        $user->setLastname("Durand");
        $user->setFirstname("Laura");
        $user->setEmail("L.Durand32@gmail.com");
        $password=$this->encoder->encodePassword($user,"2");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0611458203");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_ADMIN"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;


        $user = new User;
        $user->setUsername("user3");
        $user->setLastname("Gomez");
        $user->setFirstname("Vanessa");
        $user->setEmail("V.Gomez15@hotmail.fr");
        $password=$this->encoder->encodePassword($user,"3");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0796330215");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_USER"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;

        $user = new User;
        $user->setUsername("user4");
        $user->setLastname("Moulin");
        $user->setFirstname("Stéphane");
        $user->setEmail("Stephane.M@hotmail.com");
        $password=$this->encoder->encodePassword($user,"4");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0365201548");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_USER"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;

        $user = new User;
        $user->setUsername("user5");
        $user->setLastname("Boisgontier");
        $user->setFirstname("Hervé");
        $user->setEmail("H.Boisgontier@campus-eni.fr");
        $password=$this->encoder->encodePassword($user,"5");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0240563296");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_USER"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;

        $user = new User;
        $user->setUsername("user6");
        $user->setLastname("Pouliquen");
        $user->setFirstname("Axel");
        $user->setEmail("Axel.Pouliquen@wanadoo.fr");
        $password=$this->encoder->encodePassword($user,"6");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0754823025");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_USER"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;

        $user = new User;
        $user->setUsername("user7");
        $user->setLastname("David");
        $user->setFirstname("Fabienne");
        $user->setEmail("Fabienne.David@gmail.com");
        $password=$this->encoder->encodePassword($user,"7");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0754878625");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_USER"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;

        $user = new User;
        $user->setUsername("user8");
        $user->setLastname("SCHIANO DI COLAS");
        $user->setFirstname("Julien");
        $user->setEmail("Julien.SDC@aol.fr");
        $password=$this->encoder->encodePassword($user,"8");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0754823025");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_USER"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;

        $user = new User;
        $user->setUsername("user9");
        $user->setLastname("Bouteiller");
        $user->setFirstname("Jennifer");
        $user->setEmail("J.Bouteiller@free.fr");
        $password=$this->encoder->encodePassword($user,"9");
        $user->setPassword($password);
        $user->setActive(true);
        $user->setCreatedAt(new\DateTime());
        $user->setTelephone("0549980525");
        $user->setSite($this->getReference("site".rand(0,2)));
        $user->setPhoto("default_profile_pic_fixtures.png");
        $user->setRoles(["ROLE_USER"]);
        $this->addReference("user".$i, $user);
        $manager->persist($user);
        $i++;



        $manager->flush();
    }

    public function getDependencies() {
        return array(
            SiteFixtures::class,
        );
    }


}
