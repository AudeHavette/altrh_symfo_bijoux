<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
  /**
   * 
   * page d'accueil de notre site 
   *@Route("/",name="home")
   */

   public function home (ProductRepository $productRepository)
   
   {

    $products=$productRepository->findAll();


    
    return $this->render('home/home.html.twig',[
      'products'=>$products

   ]);


}


}