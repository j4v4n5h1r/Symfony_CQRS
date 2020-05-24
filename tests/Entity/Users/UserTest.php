<?php

namespace App\Tests\Entity\Users;

use App\Tests\Entity\TestCase;
use App\Entity\Users\User;
use App\Entity\Tenants\Tenant;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;
    public function setUp()
    {
        $tenant = new Tenant(
            'Ihor',
            null
        );
        $this->user = new User(
            $tenant,
            'Jan',
            'Kowalski',
            'jan@gmail.com',
            'abc',
            ['Role_Admin'],
            59495945,
            1
        );
    }
    /**
     * @test
     */
    public function getInitials()
    {
        $this->assertSame('JK',$this->user->getInitials());
    }
}