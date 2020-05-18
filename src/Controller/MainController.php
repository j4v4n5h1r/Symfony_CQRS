<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\PostCreate;
use App\Message\Register;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Category;

class MainController extends AbstractController {
    /**
     * @Route("/")
     */
    public function index() 
    {
        $posts = $this->getDoctrine()
        ->getRepository(Post::class)
        ->findAll();

        return $this->render('index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/post", methods="GET")
     */
    public function createPost(Request $request, MessageBusInterface $messageBus): Response
    {
        $messageBus->dispatch(new PostCreate($request));

        return new Response('Saved new post and new category');
    }

    /**
     * @Route("/post/{id}")
     */
    public function show($id)
    {
        $post = $this->getDoctrine()
        ->getRepository(Post::class)
        ->find($id);

        if (!$post) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
            );
        }

        $categoryName = $post->getCategory()->getName();

        $comments = $this->getDoctrine()
        ->getRepository(Comment::class)
        ->findAll();

        // return new Response('Check out this great post: '.$post->getName());
        return $this->render('show.html.twig', ['post' => $post, 'categoryName' => $categoryName, 'comments' => $comments]);
    }

    /**
     * @Route("/comment", methods="GET")
     */
    public function createComment(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $post = $this->getDoctrine()
        ->getRepository(Post::class)
        ->find($request->query->get("postid"));

        $comment = new Comment();
        $comment->setComment($request->query->get("comment"));
        $comment->setPost($post);
        $post->addComment($comment);
        $entityManager->persist($comment);
        $entityManager->persist($post);
        $entityManager->flush();

        $comments = $this->getDoctrine()
        ->getRepository(Comment::class)
        ->findAll();

        // return $this->render('show.html.twig', ['post' => $post, 'comments' => $comments]);

        return new Response('Saved new comment with id '.$comment->getId());
    }

    /**
     * @Route("/user")
     */
    public function userAll() 
    {
        $users = $this->getDoctrine()
        ->getRepository(User::class)
        ->findAll();

        return $this->render('user.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/login", methods="GET")
     */
    public function login(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->findOneBy(['email' => $request->query->get("email")]);

        $xx = "Error in logging in!";

        if ($user) {
            if ($request->query->get("pass") == $user->getPass()) {
                $xx = "Logged in!";
            }
        }

        return new Response($xx);
    }

    /**
     * @Route("/register", methods="GET")
     */
    public function register(Request $request, MessageBusInterface $messageBus): Response
    {
        $messageBus->dispatch(new Register($request));

        return new Response('Saved new user');
    }

}