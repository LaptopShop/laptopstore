<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="app_category")
     */
    public function showCategoryAction(CategoryRepository $repo): Response
    {
        $category = $repo->findAll();
        return $this->render('Category/index.html.twig',[
            'category'=> $category
        ]);
    }
    /**
    * @Route("/category/add", name="addCategory")
    */
    public function addCategoryAction(ManagerRegistry $res, Request $req, ValidatorInterface $valid): Response
    {
        $category = new Category();
        $categoryForm = $this->createForm(CategoryFormType::class, $category);
        $categoryForm ->handleRequest($req);
        $entity = $res->getManager();
        if($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            $data = $categoryForm->getData();
            $category->setCatName($data->getCatName());
            $category->setCatDes($data->getCatDes());
 
            $err = $valid->validate($category);
            if (count($err) > 0) {
                $string_err = (string)$err;
                return new Response($string_err, 400);
            }
            $entity->persist($category);
            $entity->flush();
 
            $this->addFlash(
                'success',
                'Your post was added'
            );
            return $this->redirectToRoute("app_category");
        }
        return $this->render('category/add.html.twig', [
            'form' => $categoryForm->createView()
        ]);
    }
}   
