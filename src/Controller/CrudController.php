<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CrudController extends AbstractController
{
    /**
     * @Route("/new", name="new")
     */
    public function new()
    {
        return $this->render('crud/new.html.twig', [
            'controller_name' => 'CrudController',
        ]);
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
