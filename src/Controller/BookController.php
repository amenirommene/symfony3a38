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
            //récupérer l'auteur
            $author=$book->getAuthor();
            //récupérer l'ancienne valeur de nbbooks
            $nb=$author->getNbbooks();
            //incrémenter l'ancienne valeur de 1
            $author->setNbbooks($nb+1);

            $em=$doctrine->getManager();
            
            //créer la requête d'ajout
            $em->persist($book);
            $em->persist($author);
          
            //exécuter la requête
            $em->flush();
            return $this->redirectToRoute("app_list_books");
        }
       // return $this->render("book/add.html.twig", ['f'=>$form->createView()]);
        return $this->renderForm("book/add.html.twig", ['f'=>$form]);
    }
    #[Route('/book/edit/{id}', name: 'app_edit_book')]
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
    public function getAllBooks(BookRepository $repo): Response
    {
       //récupérer la liste des auteurs
        $books=$repo->findAll();
       
            
        return $this->render('book/newlist.html.twig',
        ['list'=>$books]);
    }

    #[Route('/book/getPublished', name: 'app_list_published_books')]
    public function getPublishedBooks(BookRepository $repo): Response
    {
       //récupérer la liste des auteurs
        $books=$repo->findBy(['published'=>true]);
        $booksnp=$repo->findBy(['published'=>false]);
        $nbpublished=count($books); 
        $nbnotpublished=count($booksnp); 
        return $this->render('book/newlist.html.twig',
        ['list'=>$books, 'nbp'=>$nbpublished, 'nbn'=>$nbnotpublished]);
    }
    #[Route('/book/getwithtithe', name: 'app_withtitle_books')]
    public function getAllBooksByTitle(BookRepository $repo): Response
    {
       //récupérer la liste des auteurs
        $books=$repo->findBooksAuthor("Ahmed");
       
            
        return $this->render('book/newlist.html.twig',
        ['list'=>$books]);
    }
}
