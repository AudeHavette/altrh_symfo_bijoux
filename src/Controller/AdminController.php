<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController

{
 /**
  * @Route("/editCategory/{id}", name="editCategory")
  * @Route("/category", name="category")
  */

public function category(Request $request,  EntityManagerInterface $manager, CategoryRepository $categoryRepository, $id=null)
{

    $categories=$categoryRepository->findAll();


    if($id){
        $category=$categoryRepository->find($id); //si $id n'est pas null on requete l'objet category via son id


    }else{
        $category=new Category();
        // sinon on instancie un nouvel objet 

    }
   

    $form=$this->createForm(CategoryType::class, $category);
    
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
        $manager->persist($category);
        $manager->flush();


if($id){
    $this->addFlash('success','categorie modifiée');
}else{
    $this->addFlash('success','categorie créée');

}
        return $this->redirectToRoute('category');

    }


    return $this->render('admin/category.html.twig',[
     'categories'=>$categories, 
     'form'=>$form->createView()
    ]);
}

/**
 * 
 * 
 * 
 * @Route("/deleteCategory/{id}", name="deleteCategory")
 */
public function deleteCategory(EntityManagerInterface $manager, Category $category)
{
    $manager->remove($category);
    $manager->flush(); 

    $this->addFlash('success', 'Catégoeir supprimée');

    return $this->redirectToRoute('category');
}







}
