<?php
namespace App\MessageHandler;

use App\Entity\Post;
use App\Entity\Category;
use App\Message\PostCreate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PostCreateHandler implements MessageHandlerInterface
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(PostCreate $postCreate)
    {
    	$request = $postCreate->getRequest();

        $post = new Post();
        $post->setName($request->query->get("name"));

        $category = new Category($post, $request->query->get("category"), "");

        $this->entityManager->persist($category);
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}