<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function register(string $email, string $password): User
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        if (strlen($password) < 6) {
            throw new \InvalidArgumentException('Password must be at least 6 characters');
        }

        $existingUser = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if ($existingUser) {
            throw new \RuntimeException('User already exists');
        }

        $user = new User($email, $password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}