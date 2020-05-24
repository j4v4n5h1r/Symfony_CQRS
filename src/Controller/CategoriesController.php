<?php

namespace App\Controller;

use App\Adapter\Category\Categories;
use App\Adapter\Category\ReadModel\CategoriesQuery;
use App\Adapter\Core\Transaction;
use App\Entity\Category;
use App\Entity\Categories\UseCase\CreateCategory;
use App\Form\Categories\AddCategoryType;

//use Doctrine\Common\Persistence\ObjectManager;
//use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Categories\UseCase\CreateCategory\Responder as CreateCategoryResponder;

//use App\Controller\Core\AdvancedAbstractController;

/**
 * @Route("/{tenant}")
 */

//  * @Security("!is_anonymous() and user.getTenant().getName() == request.get('tenant')")
class CategoriesController extends AbstractController implements CreateCategoryResponder
{
//    private $objectManager;
//    private $entityManager;
//
//    public function __construct(ObjectManager $objectManager, EntityManagerInterface $entityManager)
//    {
//        $this->objectManager = $objectManager;
//        $this->entityManager = $entityManager;
//    }

    /**
     * @Route("/categories", name="categories_index", methods={"GET"})
     */
    public function indexAction($tenant, CategoriesQuery $categoriesQuery)
    {
        return $this->render('categories/index.html.twig', [
            'categories' => $categoriesQuery->getAll($tenant),
        ]);
    }

    /**
     * @Route("/category/add", name="categories_add", methods={"GET"})
     * @Route("/category/create", name="categories_create", methods={"POST"})
     */
    public function addAction($tenant, Request $request, CategoriesQuery $categoriesQuery)
    {
        $form = $this->createForm(
            AddCategoryType::class,
            [],
            [
                'method' => 'POST',
                'action' => $this->generateUrl('categories_create', ['tenant' => $tenant])
            ]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new CreateCategory\Command(
                $this->getUser()->getTenant(),
                $data['name'],
                implode("", $categoriesQuery->generateAbbreviationCode($tenant, $data['name']))
            );
            $command->setResponder($this);
//            $createCategory = new CreateCategory(
//                new Categories($this->objectManager),
//                new Transaction($this->entityManager));
//            $createCategory->execute($command);
            return $this->redirectToRoute('categories_add', ['tenant' => $tenant]);
        }
        return $this->render('categories/add.html.twig', [
            'form' => $form->createView(),
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