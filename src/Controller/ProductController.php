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
   /**
   * @Route("/product/add", name="addProduct")
   */
  public function addProductAction(ManagerRegistry $res, Request $req, SluggerInterface $slugger, ValidatorInterface $valid): Response
  {
    $product = new Product();
    $productForm = $this->createForm(ProductFormType::class, $product);
    $productForm->handleRequest($req);
    $entity = $res->getManager();
    if ($productForm->isSubmitted() && $productForm->isValid()) {
      $data = $productForm->getData();
      $product->setProductName($data->getProductName());
      $product->setPrice($data->getPrice());
      $product->setOldPrice($data->getOldPrice());
      $product->setSmallDesc($data->getSmallDesc());
      $product->setDetailDesc($data->getDetailDesc());
      $product->setProDate($data->getProDate());
      $product->setProQty($data->getProQty());
      $product->setSupplier($data->getSupplier());
      $product->setCategories($data->getCategories());

      $imgFile = $productForm->get('image')->getData();
      if ($imgFile) {
        $originalFilename = pathinfo($imgFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile->guessExtension();
        try {
          $imgFile->move(
            $this->getParameter('image_dir'),
            $newFilename
          );
        } catch (FileException $e) {
          echo $e;
        }
        $product->setImage($newFilename);
      }
      $entity->persist($product);
      $entity->flush();
      return $this->redirectToRoute("app_product");
    }
    return $this->render('product/add.html.twig', [
      'form' => $productForm->createView()
    ]);
  }
  
  /**
   * @Route("product/edit/{id}", name="editProduct")
   */
  public function editProductAction(ManagerRegistry $res, Request $req, ValidatorInterface $valid, SluggerInterface $slugger, ProductRepository $repo, $id): Response
  {
    $product = $repo->find($id);
    $productForm = $this->createForm(ProductFormType::class, $product);
    $productForm->handleRequest($req);
    $entity = $res->getManager();
    if ($productForm->isSubmitted() && $productForm->isValid()) {
      $data = $productForm->getData();
      $product->setProductName($data->getProductName());
      $product->setPrice($data->getPrice());
      $product->setOldPrice($data->getOldPrice());
      $product->setSmallDesc($data->getSmallDesc());
      $product->setDetailDesc($data->getDetailDesc());
      $product->setProDate($data->getProDate());
      $product->setProQty($data->getProQty());
      $product->setSupplier($data->getSupplier());
      $product->setCategories($data->getCategories());
      $product->setImage($data->getImage());
      $err = $valid->validate($product);
      if (count($err) > 0) {
        $string_err = (string)$err;
        return new Response($string_err, 400);
      }
      $entity->persist($product);
      $entity->flush();
      return $this->redirectToRoute("app_product");
    }
    return $this->render('product/add.html.twig', [
      'form' => $productForm->createView()
    ]);
  }
  /**
     * @Route("product/delete/{id}", name="deleteProduct")
     */
    public function deleteProductFunction(ProductRepository $repo, ManagerRegistry $doc, $id): Response
    {
        $product = $repo->find($id);
 
        if (!$product) {
            throw
            $this->createNotFoundException('Invalid ID ' . $id);
        }
        $entity = $doc->getManager();
        $entity->remove($product);  
        $entity->flush();
        return $this->redirectToRoute("app_product");
    }
   /**
     * @Route("/product/getImage/{filename}", name="get_image")
     */
    public function getImage($filename): Response
    {
        $file = $this->getParameter('image_dir') . '/' . $filename;
        $response = new Response();
        $response->headers->set('Content-Type', 'image/jpg');
        $response->setContent(file_get_contents($file));
        return $response;
    }
}


