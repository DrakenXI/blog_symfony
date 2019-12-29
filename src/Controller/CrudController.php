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
        // TODO modify date time

        // create form with previous object
        $form = $this->createFormBuilder($post)
            ->add('titre', TextType::class)
            ->add('url_alias', TextType::class)
            ->add('content', TextareaType::class)
            ->add('published', DateType::class)
            ->add('save', SubmitType::class)
            ->getForm();

            $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            $post = $form->getData();

            // TODO : save to database
            //$entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($post);
            //$entityManager->flush();

            // inform user everything went fine
            $this->addFlash(
                'notice',
                'Post créé !'
            );

            // now data is saved, user go back to homepage
            return $this->redirectToRoute('blog');
        }

        // render the form in view
        return $this->render('crud/new.html.twig', array('form' =>     $form->createView(), ));
    }

    /**
     * Fonction associant la vue de la page d'accueil à l'adresse /.
     * Page d'accueil liste tous les posts du blog.
     * @Route("/update/{idPost}", name="update", requirements={"idPost"="\d+"})
     */
    public function update(int $idPost)
    {
        return $this->render('crud/update.html.twig', [
            'id_post' => $idPost,
        ]);
    }

    /**
     * Fonction associant la vue d'un post individuel à l'adresse /posts/{idPost}.
     * @Route("/delete/{idPost}", name="delete", requirements={"idPost"="\d+"})
     */
    public function delete(int $idPost)
    {
        return $this->render('crud/delete.html.twig', [
            'id_post' => $idPost,
        ]);
    }
}
