<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Message\PostCreate;
use App\Message\Register;
use App\Entity\Post;
use App\Entity\Users\User;
use App\Entity\Comment;

//use App\Entity\Categories;

class MainController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/")
     */
    public function index()
    {
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        $email = $this->session->get('email');

        return $this->render('index.html.twig', [
            'posts' => $posts, 'email' => $email
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

        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findBy(['post' => $post]);

        $email = $this->session->get('email');

//        if (!$post) {
//            throw $this->createNotFoundException(
//                'No post found for id ' . $id
//            );
//        }

//        $categoryName = $post->getCategory()->getName();

        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findAll();

        // return new Response('Check out this great post: '.$post->getName());
        return $this->render('show.html.twig', ['post' => $post, 'categories' => $categories, 'comments' => $comments, 'email' => $email]);
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

        // return new Response('Saved new comment with id '.$comment->getId());

        return $this->redirect('post/' . $post->getId());
    }

    /**
     * @Route("/user")
     */
    public function userAll()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $email = $this->session->get('email');

        return $this->render('user.html.twig', [
            'users' => $users, 'email' => $email
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

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        $email = "";

        if ($user) {
            if ($request->query->get("pass") == $user->getPass()) {
                $email = $user->getEmail();
                $this->session->set('email', $user->getEmail());
            }
        }

        return $this->render('index.html.twig', [
            'posts' => $posts, 'email' => $email
        ]);
    }

    /**
     * @Route("/register", methods="GET")
     */
    public function register(Request $request, MessageBusInterface $messageBus): Response
    {
        $messageBus->dispatch(new Register($request));

        return new Response('Saved new user');
    }

    /**
     * @Route("/logout", methods="GET")
     */
    public function logout(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findAll();

        $this->session->set('email', "");

        $email = "";

        return $this->render('index.html.twig', [
            'posts' => $posts, 'email' => $email
        ]);
    }

}