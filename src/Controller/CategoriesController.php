<?php

namespace App\Controller;

use App\Adapter\Categories;
use App\Adapter\Category\ReadModel\CategoriesQuery;
use App\Adapter\Core\Transaction;
use App\Entity\Category;
use App\Entity\Categories\UseCase\CreateCategory;
use App\Entity\Post;
use App\Form\Categories\AddCategoriesType;
use App\Repository\Categories\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Categories\UseCase\CreateCategory\Responder as CreateCategoryResponder;

//use App\Controller\Core\AdvancedAbstractController;

/**
 * @Route("/{post}")
 */
//  * @Security("!is_anonymous() and user.getTenant().getName() == request.get('tenant')")
class CategoriesController extends AbstractController implements CreateCategoryResponder
{
    private $entityManager;
    private $session;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/categories", name="categories_index", methods={"GET"})
     */
    public function indexAction($post, CategoriesQuery $categoriesQuery)
    {
        $email = $this->session->get('email');

        return $this->render('categories/index.html.twig', [
            'categories' => $categoriesQuery->getAll($post), 'email' => $email
        ]);
    }

    /**
     * @Route("/category/add", name="categories_add", methods={"GET"})
     * @Route("/category/create", name="categories_create", methods={"POST"})
     */
    public function addAction($post, Request $request, CategoriesQuery $categoriesQuery)
    {
        $form = $this->createForm(
            AddCategoriesType::class,
            [],
            [
                'method' => 'POST',
                'action' => $this->generateUrl('categories_create', ['post' => $post])
            ]
        );

        $postx = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findOneBy(['name' => $post]);

        $categoryRepository = $this->getDoctrine()
            ->getRepository(Category::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new CreateCategory\Command(
                $postx,
                $data['name'],
//                implode("", $categoriesQuery->generateAbbreviationCode($post, $data['name']))
                ""
            );
            $command->setResponder($this);
            $createCategory = new CreateCategory(
                new Categories($this->getDoctrine()->getManager(), $categoryRepository));
            $createCategory->execute($command);
            return $this->redirectToRoute('categories_add', ['post' => $post]);
        }

        $email = $this->session->get('email');

        return $this->render('categories/add.html.twig', [
            'form' => $form->createView(), 'email' => $email
        ]);
    }

    public function categoryCreated(Category $category)
    {
        $this->addFlash('success', 'Categories ' . $category->getName() . ' created. Code category: ' . $category->getCode());
    }

    public function providedNameIsInUse(string $name)
    {
        $this->addFlash('error', 'Categories ' . $name . ' exist');
    }
}