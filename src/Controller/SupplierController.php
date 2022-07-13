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
    
}
