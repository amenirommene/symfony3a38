<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_n' => '3A38',
        ]);
    }

    #[Route('/showAuthor', name: 'app_showauthor1')]
    public function showAuthor1(): Response
    {
        return $this->render('author/show.html.twig');
    }
    #[Route('/showAuthor/{name}', name: 'app_showauthor')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig',
        ['mavariable'=>$name]);
    }
    #[Route('/list', name: 'app_list')]
    public function showListAuthor(): Response
    {
       
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
            );
            
        return $this->render('author/list.html.twig',
        ['list'=>$authors]);
    }

    #[Route('/details/{id}', name: 'app_author_details')]
    public function showAuthordetails($id): Response
    {
       
        $authors = array(
            array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
            );
            
        return $this->render('author/showauthor.html.twig',
        ['list'=>$authors, 'monid'=>$id]);
    }

    #[Route('/getall', name: 'app_list_authors')]
    public function getAllAuthors(AuthorRepository $repo): Response
    {
       //récupérer la liste des auteurs
        $authors=$repo->findAll();
       
            
        return $this->render('author/newlist.html.twig',
        ['list'=>$authors]);
    }

    #[Route('/getauthor/{id}', name: 'app_author_details_db')]
    public function getAuthorDetail(ManagerRegistry $doctrine, $id): Response
    {
        $repo=$doctrine->getRepository(Author::class);
       //récupérer l'auteur dont l'id est envoyé dans le path
        $author=$repo->find($id);
       
            
        return $this->render('author/newdetails.html.twig',
        ['a'=>$author]);
    }

    #[Route('/addauthor', name: 'app_add_author')]
    public function addAuthor(ManagerRegistry $doctrine): Response
    {
       //objet à insérer
        $a=new Author();
        $a->setUsername("Ahmed");
        $a->setEmail("Ahmed@esprit.tn");
        $a2=new Author();
        $a2->setUsername("Ahmed2");
        $a2->setEmail("Ahmed2@esprit.tn");
        //créer une instance de entity manager
        $em=$doctrine->getManager();
        //créer la requête d'ajout
        $em->persist($a);
        $em->persist($a2);
        //exécuter la requête
        $em->flush();
       
            
        return new Response("OK");
    }

    #[Route('/addauthorwithform', name: 'app_add_author_withform')]
    public function addAuthorwithform(Request $req, ManagerRegistry $doctrine): Response
    {
       //objet à insérer
        $a=new Author();
        //instancier la classe du formulaire
        $form=$this->createForm(AuthorType::class, $a);
        $form->add('Save_me', SubmitType::class);
        //form is submitted or not + remplissage de l'objet $a
        $form->handleRequest($req);
        if ($form->isSubmitted()){
            $em=$doctrine->getManager();
            //créer la requête d'ajout
            $em->persist($a);
          
            //exécuter la requête
            $em->flush();
        }
       
        return $this->render("author/add.html.twig", ['f'=>$form->createView()]);
    }
}
