<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/add', name: 'app_add_book')]
    public function add(Request $req, ManagerRegistry $doctrine): Response
    {
       //objet à insérer
        $book=new Book();
        //instancier la classe du formulaire
        $form=$this->createForm(BookType::class, $book);
               //form is submitted or not + remplissage de l'objet $a
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
            //créer la requête d'ajout
            $em->persist($book);
          
            //exécuter la requête
            $em->flush();
        }
       // return $this->render("book/add.html.twig", ['f'=>$form->createView()]);
        return $this->renderForm("book/add.html.twig", ['f'=>$form]);
    }
    #[Route('/book/edit/{id}', name: 'app_add_book')]
    public function edit($id,Request $req, ManagerRegistry $doctrine): Response
    {
       //objet à insérer
        $book=$doctrine->getRepository(Book::class)->find($id);
        //instancier la classe du formulaire
        $form=$this->createForm(BookType::class, $book);
               //form is submitted or not + remplissage de l'objet $a
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
            //créer la requête d'ajout
            $em->persist($book);
          
            //exécuter la requête
            $em->flush();
        }
       // return $this->render("book/add.html.twig", ['f'=>$form->createView()]);
        return $this->renderForm("book/add.html.twig", ['f'=>$form]);
    }

    #[Route('/book/getall', name: 'app_list_books')]
    public function getAllAuthors(BookRepository $repo): Response
    {
       //récupérer la liste des auteurs
        $books=$repo->findAll();
       
            
        return $this->render('book/newlist.html.twig',
        ['list'=>$books]);
    }
}
