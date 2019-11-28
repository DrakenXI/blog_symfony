<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller associé aux vues du site
 */
class BlogController extends AbstractController
{
    /**
     * Fonction associant la vue de la page d'accueil à l'adresse /.
     * Page d'accueil liste tous les posts du blog.
     * @Route("/", name="blog")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)
            ->findBy([], array("published" => "DESC"), 10);
        return $this->render('blog/index.html.twig', [
            "title" => "Acceuil",
            "posts" => $posts
        ]);
    }

    /**
     * Fonction associant la vue d'un post individuel à l'adresse /posts/{idPost}.
     * @Route("/post/{idPost}", name="post", requirements={"idPost"="\d+"})
     */
    public function post(int $idPost)
    {
        return $this->render('blog/post.html.twig', [
            'id_post' => $idPost,
        ]);
    }
}
