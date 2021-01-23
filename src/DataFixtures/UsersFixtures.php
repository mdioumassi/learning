<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends Fixture
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $fake = Factory::create('fr_FR');
        $admin = new Users();

        $admin
            ->setFirstname('mohamed')
            ->setLastname('dioumassi')
            ->setAddress('1 rue edouard sylvestre 93270')
            ->setPostalCode('93270')
            ->setEmail("admin@yahoo.fr")
            ->setCity('Sevran')
            ->setPhoneMobile('0749780547')
            ->setCivility('CIVILITE-M')
            ->setIsVerified('1')
            ->setPassword($this->encoder->encodePassword($admin, 'password'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        for ($u = 0; $u < 10; $u++) {
            $users = new Users();
            $passHash = $this->encoder->encodePassword($users, 'password');

            $users
                ->setPassword($passHash)
                ->setFirstname($fake->firstName())
                ->setLastname($fake->lastName())
                ->setAddress($fake->address)
                ->setPostalCode('93270')
                ->setEmail($fake->email)
                ->setCity($fake->city)
                ->setPhoneMobile('0602732975')
                ->setCivility('CIVILITE-M');
            $manager->persist($users);
        }
        $manager->flush();
    }
}
