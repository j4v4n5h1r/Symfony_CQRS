<?php

namespace App\Tests\Entity\Users\UseCase;

use App\Tests\Entity\TestCase;
use App\Entity\Users\User;
use App\Entity\Tenants\Tenant;

class CreateUserTest extends TestCase implements UserResponder
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var Tenant
     */
    private $tenant;

    /**
     * @test
     */
    public function createUser()
    {
        $commandTenant = new CommandTenant(
            'test',
            'janTestowy@gmail.com',
            'abc',
            'Uzyt',
            'Testowy',
            123123123
        );
        $this->getCreateTenant()->execute($commandTenant);
        $this->tenant = $this->getTenants()->findByName('test');
        $command = new Command(
            $this->tenant,
            'Jan',
            'Kowalski',
            'jan@gmail.com',
            123123123
        );
        $command->setResponder($this);
        $this->getCreateUser()->execute($command);
    }

    public function userCreated(User $user)
    {
        $this->user = $this->getUsers()->findOneByEmail('jan@gmail.com');
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertSame('Jan', $this->user->getName());
        $this->assertSame('Kowalski', $this->user->getSurname());
        $this->assertSame('jan@gmail.com', $this->user->getEmail());
        $this->assertSame('123123123', $this->user->getPhone());
    }

    public function providedEmailIsInUse(string $email)
    {
    }

    private function getCreateTenant(): CreateTenant
    {
        return $this->get('use_case.create_tenant');
    }

    private function getCreateUser(): CreateUser
    {
        return $this->get('use_case.create_user');
    }

    private function getTenants(): Tenants
    {
        return $this->get('tenants');
    }

    private function getUsers(): Users
    {
        return $this->get('users');
    }
}