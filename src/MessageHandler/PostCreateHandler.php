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
        $category = new Category();
        $category->setName($request->query->get("category"));

        $post = new Post();
        $post->setName($request->query->get("name"));

        $post->setCategory($category);

        // $this->$entityManager = $this->getDoctrine()->getManager();

        $this->entityManager->persist($category);
        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}