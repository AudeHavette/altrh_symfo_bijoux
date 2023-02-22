<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{

    /**
     *
     *
     * @Route("/add", name="addProduct")
     */
    public function addProduct(Request $request, EntityManagerInterface $manager )
    {
        // création d'une instance de la classe product 
        // On est en ajout (donc en création d'un nouveau produit), on instancie donc un objet vide la classe
        $product=new Product;
        
        // création d'un formulaire grace à la méthode createForm() héritée de l'abstractController
        // 2arguments obligatoires:
        // 1er Le formulaire sur lequel on se base (le Type)
        // 2nd L'objet instance à remplir
        // 3eme (optionnel)=> tableau d'option
        // Le fait de renseigner ces arguments permet à Symfony d'effectuer les contrôles de validité
        // à savoir les typages de données en liens avec les types d'input de formulaire et le fait que chaque input du type (chaques add() ) correspondent bien à une propriété de la classe 
        $form=$this->createForm(ProductType::class, $product);
        // $form est un objet instance de Form
        //traitement de la requête 
        $form->handleRequest($request); // request est la classe qui regrp tte nos superglob
        //$request->request($_POST) 
        //$request->query($_GET) 
        //dd() pour dump and die qui permet d'afficher un var_dump() en stoppant l'exeution du script 
        //dd($product); //$product est à présent rempli de ses données de formulaire

        //condition de soumission de formulaire
        
        if($form->isSubmitted() && $form->isValid ()) //si formulaire soumis et valide (aucune erreur de constraints n'a été relevé).
        // les 2 conditions doivent etre appelées ds cet ordre. 
        {
           // on récupère tte les donnees sur l'input type file (picture)
           $pictureFile=$form->get('picture')->getData();
           //dd($pictureFile);



           $picture_bdd=date("YmdHis").$pictureFile->getClientOriginalName();
           //dd($picture_bdd);

           try{

            //$this->getParameter permet d'acceder aux constantes declarees ds le services.yaml
            // sur la partie parameter 
            $pictureFile->move($this->getParameter('upload_directory'), $picture_bdd);
            //move()= copy() de php procédural, doit etre appelé sur l'objet file
            //2 argument 1er l'emplacement de copie, le 2eme le nom du fichier
           }catch (FileException $e) {
               dd($e);
           }

           //on réaffecte à present le nouveau nom di fichier à notre objet $product grace à son setter 
           $product->setPicture ($picture_bdd);
           //dd($product);

           //EntityManagerInterface $manager oblig pour tte les req d'INSERT INTO, UPDATE, DELETE

            
           $manager->persist($product); // on lui dem de persister l'objet (prep de la req)
           $manager->flush(); // on envoie l'objet en BDD (execute)

           return $this->redirectToRoute("home");

        }

        
        // on renvoi la vue de notre formulaire dans le tableau de notre méthode render  grace à la méthode createView() de notre objet $form 
        return $this->render('product/addProduct.html.twig', [
            'form'=>$form->createView()

        ]);
    }


/**
 * 
 * 
 * 
 * 
 * @Route("/list", name="listProduct")
 */

public function listProduct()
{
return $this->render('product/listProduct.html.twig',[

]);

}







}
