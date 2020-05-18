<?php
namespace App\MessageHandler;

use App\Entity\User;
use App\Message\Register;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RegisterHandler implements MessageHandlerInterface
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(Register $register)
    {
    	$request = $register->getRequest();

        $user = new User();
        $user->setEmail($request->query->get("email"));
        $user->setPass($request->query->get("pass"));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}