<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller associe aux vues du site
 */
class BlogController extends AbstractController
{
    /**
     * Fonction associant la vue de la page d'accueil à l'adresse /.
     * Page d'accueil liste tous les posts du blog.
     * @Route("/", name="blog")
     */
    public function index(Request $request)
    {
        // pagination stuff
        if( $request->query->get('slot')!=null){
            $slot = $request->query->get('slot');
        } else {
            $slot = 0;
        }

        // fetch all posts from DB
        $posts = $this->getDoctrine()->getRepository(Post::class)
            ->findBy([], array("published" => "DESC"), 10);
        return $this->render('blog/index.html.twig', [
            "title" => "Bienvenue",
            "posts" => $posts,
            "slot" => $slot
        ]);
    }

    /**
     * Fonction associant la vue d'un post individuel à l'adresse /posts/{idPost}.
     * @Route("/post/{url}", name="post", requirements={"url"="[a-zA-Z0-9]+([a-zA-Z0-9]*)(_[a-zA-Z0-9]+|-[a-zA-Z0-9]+)*"})
     */
    public function post(string $url)
    {
        // fetch post from DB
        $entityManager = $this->getDoctrine()->getManager();
        $post = $entityManager->getRepository(Post::class)->findOneBy(['url_alias' => $url]);

        if(!$post){
            return $this->render('blog/notfound.html.twig',[]);
        }

        return $this->render('blog/post.html.twig', [
            'post' => $post,
        ]);
    }
}
