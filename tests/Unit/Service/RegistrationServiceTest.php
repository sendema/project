<?php

namespace Tests\Unit\Service;

use App\Entity\User;
use App\Service\RegistrationService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\TestCase;

class RegistrationServiceTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;
    private RegistrationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(EntityRepository::class);

        $this->entityManager
            ->method('getRepository')
            ->with(User::class)
            ->willReturn($this->repository);

        $this->service = new RegistrationService($this->entityManager);
    }

    public function testSuccessfulRegistration(): void
    {
        $email = 'test@example.com';
        $password = 'password123';

        $this->repository
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn(null);

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(User::class));

        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $user = $this->service->register($email, $password);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($email, $user->getEmail());
    }

    public function testRegistrationWithInvalidEmail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');

        $this->service->register('invalid-email', 'password123');
    }

    public function testRegistrationWithShortPassword(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be at least 6 characters');

        $this->service->register('test@example.com', '12345');
    }

    public function testRegistrationWithExistingUser(): void
    {
        $email = 'existing@example.com';
        $existingUser = new User($email, 'password123');

        $this->repository
            ->method('findOneBy')
            ->with(['email' => $email])
            ->willReturn($existingUser);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('User already exists');

        $this->service->register($email, 'password123');
    }
}