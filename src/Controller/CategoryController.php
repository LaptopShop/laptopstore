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
 
}   
