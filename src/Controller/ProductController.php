<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\ProductFormType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
  /**
   * @Route("/product", name="app_product")
   */
  public function showProductAction(ProductRepository $repo): Response
  {
    $product = $repo->findAll();
    return $this->render('Product/index.html.twig', [
      'product' => $product
    ]);
  }
  /**
   * @Route("/product/show/{id}", name="ProductShow")
   */
  public function showView(ProductRepository $repo, $id)
  {
    $product = $repo->find($id);

    return $this->render('Product/show.html.twig', [
      'products' => $product
    ]);
  }
}

