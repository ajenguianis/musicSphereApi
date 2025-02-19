<?php

// src/DataFixtures/UserFixture.php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Create Super Admin user
        $superAdminUser = new User();
        $superAdminUser->setUsername('admin');
        $superAdminUser->setEmail('admin@admin.com');
        $superAdminUser->setRoles(['ROLE_SUPER_ADMIN']);
        
        // Encode the password for Super Admin
        $superAdminUser->setPassword($this->passwordEncoder->encodePassword($superAdminUser, '123@123@1234'));

        // Create a regular user
        $regularUser = new User();
        $regularUser->setUsername('user');
        $regularUser->setEmail('user@user.com');
        $regularUser->setRoles(['ROLE_USER']);
        
        // Encode the password for Regular User
        $regularUser->setPassword($this->passwordEncoder->encodePassword($regularUser, '123@123@1234'));

        // Persist both users
        $manager->persist($superAdminUser);
        $manager->persist($regularUser);

        // Flush data to the database
        $manager->flush();
    }
    public function getGroups(): array
    {
        return ['users']; // Define this fixture as part of the 'users' group
    }
}
