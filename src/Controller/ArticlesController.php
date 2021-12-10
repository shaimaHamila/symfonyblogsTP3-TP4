<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class ArticlesController extends AbstractController
{   


    //Fetching Artical from the Database
    /**
     * @Route("/articles", name="articles")
    */
    public function showArticles (ArticleRepository $articleRepository): Response
    {   

        $articles= $articleRepository->findAll();

        if(!$articles) {
            throw $this->createNotFoundException("The table is empty");
        }
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
        ]);
    }


    /**
     * @Route("/articles/{id}", name="articles_id")
    */
    public function showArticle (int $id, ArticleRepository $articleRepository): Response
    {   

        $article= $articleRepository->find($id);
        

        if(!$article) {
            throw $this->createNotFoundException('No article found for id '.$id);
        }
        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'state'=>"search"
        ]);
    }

    //Updating an Article
     /**
     * @Route("/articles/edit/{id}", name="article_update")
     */
    public function updateArticle(Article $article, EntityManagerInterface $entityManager)
    {   
    
        if(!$article){
            throw $this->createNotFoundException('No artical found for id '.$article->getid());          
        };

        $article->setDesignation("New Product");
        $article->setPrix(99);
        
        $entityManager->flush();

        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'state'=>"update"
        ]);
    }


    //Delete An artcle

         /**
     * @Route("/articles/delete/{id}", name="article_delete")
     */
    public function deleteArticle(Article $article, EntityManagerInterface $entityManager)
    {
        if(!$article){
            throw $this->createNotFoundException('No artical found for id '.$article->getid());
        };

        //Delete the entity
        $entityManager->remove($article);

        //Save the opration in the table
        $entityManager->flush();

        //reastor articles table

        return $this->redirectToRoute('articles');
    }



    //Add new article
    /**
     * @Route("/article/add", name="add_article")
    */
    public function addeArticle(EntityManagerInterface $entityManager):Response
    {
        $article = new Article();
        $article->setDesignation("8");
        $article->setDescription("Pc Portable MSI Gaming GF63 Thin 10SC-666XF / I7 10è Gén / 8 Go / 512 Go SSD");
        $article->setPrix(2599);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($article);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->render('articles/index.html.twig', [
            'articles'=> $article,
            'state'=>"add"
        ]);

    }




}
