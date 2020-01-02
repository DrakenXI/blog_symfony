<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CrudController extends AbstractController
{
    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        // create new Post and prefill form
        $post = new Post();
        $post->setTitre('Un titre d\'article');
        $post->setUrlAlias('une-super-url');
        $post->setContent('Un joli contenu.');
        $post->setPublished(new \DateTime('tomorrow'));

        // create form with previous object
        $form = $this->createFormBuilder($post)
            ->add('titre', TextType::class)
            ->add('url_alias', TextType::class)
            ->add('content', TextareaType::class)
            ->add('published', DateType::class)
            ->add('save', SubmitType::class)
            ->getForm();

            $form->handleRequest($request);

        // if OK, save post in DB
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            $post = $form->getData();

            // save to database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            // inform user everything went fine
            $this->addFlash(
                'notice',
                'Post créé !'
            );

            // now data is saved, user go back to homepage
            return $this->redirectToRoute('blog');
        }

        // render the form in view
        return $this->render('crud/new.html.twig', array('form' =>     $form->createView(),
                                                        'title' => 'Création d\'un article'), );
    }

    /**
     * Fonction associant la vue de la page d'accueil à l'adresse /edit/url.
     * Page d'admin pour modifier un article du blog.
     * @Route("/edit/{url}", name="edit", requirements={"url"="[a-zA-Z0-9]+([a-zA-Z0-9]*)(_[a-zA-Z0-9]+|-[a-zA-Z0-9]+)*"})
     */
    public function edit(string $url, Request $request)
    {
        // fetch post from DB
        $entityManager = $this->getDoctrine()->getManager();
        $p = $entityManager->getRepository(Post::class)->findOneBy(['url_alias' => $url]);

        if($p) {
            // post exists, make form to edit it
            $post = new Post();
            $post->setTitre($p->getTitre());
            $post->setUrlAlias($p->getUrlAlias());
            $post->setContent($p->getContent());
            $post->setPublished($p->getPublished());

            // create form with previous object
            $form = $this->createFormBuilder($post)
                ->add('titre', TextType::class)
                ->add('url_alias', TextType::class)
                ->add('content', TextareaType::class)
                ->add('published', DateType::class)
                ->add('save', SubmitType::class)
                ->getForm();

                $form->handleRequest($request);

            // if OK, save post in DB
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values
                $newPost = $form->getData();

                //edit values
                $p->setTitre($newPost->getTitre());
                $p->setUrlAlias($newPost->getUrlAlias());
                $p->setContent($newPost->getContent());
                $p->setPublished($newPost->getPublished());
                // save to database
                $entityManager->flush();

                // inform user everything went fine
                $this->addFlash(
                    'notice',
                    'Article modifié !'
                );

                // now data is saved, user go back to article in question
                return $this->redirectToRoute('post', [
                    'url' => $post->getUrlAlias()
                ]);
            }

            return $this->render('crud/edit.html.twig',  array('form' =>     $form->createView(),
                                                        'title' => 'Modification d\'un article',));
        } else {
            // post does not exist, inform user (this should not happen)
            return $this->render('blog/notfound.html.twig',[]);
        }
    }

    /**
     * Fonction associant la vue d'un post individuel à l'adresse /delete/{url}.
     * Page d'admin de suppression d'un article.
     * @Route("/delete/{url}", name="delete", requirements={"idPost"="[a-zA-Z0-9]+([a-zA-Z0-9]*)(_[a-zA-Z0-9]+|-[a-zA-Z0-9]+)*"})
     */
    public function delete(string $url)
    {
        // fetch post from DB
        $entityManager = $this->getDoctrine()->getManager();
        $p = $entityManager->getRepository(Post::class)->findOneBy(['url_alias' => $url]);

        if($p) {
            // If post exists, delete it
            $entityManager->remove($p);
            $entityManager->flush();
            $this->addFlash(
                'notice',
                'Article supprimé !');

            return $this->redirectToRoute('blog');
        } else {
            $this->addFlash(
            'error',
            'Erreur lors de la suppression !'
            );
            return $this->render('blog/notfound.html.twig',[]);
        }
    }
}
