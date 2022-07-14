<?php

namespace App\Controller;

use App\Entity\Supplier;
use App\Form\Type\SupplierFormType;
use App\Repository\SupplierRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SupplierController extends AbstractController
{
    /**
     * @Route("/supplier", name="app_supplier")
     */
    public function showSupplierAction(SupplierRepository $repo): Response
    {
        $supplier = $repo->findAll();
        return $this->render('Supplier/index.html.twig',[
            'supplier'=> $supplier
        ]);
    }
    /**
    * @Route("/supplier/add", name="addSupplier")
    */
    public function addSupplierAction(ManagerRegistry $res, Request $req, ValidatorInterface $valid): Response
    {
        $supplier = new Supplier();
        $supplierForm = $this->createForm(SupplierFormType::class, $supplier);
        $supplierForm ->handleRequest($req);
        $entity = $res->getManager();
        if($supplierForm->isSubmitted() && $supplierForm->isValid())
        {
            $data = $supplierForm->getData();
            $supplier->setSupName($data->getSupName());
            $supplier->setTel($data->getTel());
            $err = $valid->validate($supplier);
            if (count($err) > 0) {
                $string_err = (string)$err;
                return new Response($string_err, 400);
            }
            $entity->persist($supplier);
            $entity->flush();
 
            $this->addFlash(
                'success',
                'Your post was added'
            );
            return $this->redirectToRoute("app_supplier");
        }
        return $this->render('Supplier/add.html.twig', [
            'form' => $supplierForm->createView()
        ]);
    }
    /**
     * @Route("/edit/{id}", name="editSupplier")
     */

    public function editSupplierAction(ManagerRegistry $res, Request $req, ValidatorInterface $valid, SupplierRepository $repo, $id): Response
    {
        $supplier = $repo->find($id);
        $supplierForm = $this->createForm(SupplierFormType::class, $supplier);
        $supplierForm ->handleRequest($req);
        $entity = $res->getManager();
        if($supplierForm->isSubmitted() && $supplierForm->isValid())
        {
            $data = $supplierForm->getData();
            $supplier->setSupName($data->getSupName());
            $supplier->setTel($data->getTel());
            $err = $valid->validate($supplier);
            if (count($err) > 0) {
                $string_err = (string)$err;
                return new Response($string_err, 400);
            }
            $entity->persist($supplier);
            $entity->flush();
 
            $this->addFlash(
                'success',
                'Your post was added'
            );
            return $this->redirectToRoute("app_supplier");
        }
        return $this->render('Supplier/add.html.twig', [
            'form' => $supplierForm->createView()
        ]);
    }
      /**
     * @Route("/delete/{id}", name="deleteSupplier")
     */
    public function deleteSupplierFunction(SupplierRepository $repo, ManagerRegistry $doc, $id): Response
    {
        $supplier = $repo->find($id);
 
        if (!$supplier) {
            throw
            $this->createNotFoundException('Invalid ID ' . $id);
        }
        $entity = $doc->getManager();
        $entity->remove($supplier);
        $entity->flush();
        return $this->redirectToRoute("app_supplier");
    }
}