<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


 /**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private const POSTS = [
        [
            'id' => 1,
            'slug' => 'hello-world',
            'title' => "Hello world"
        ],
        [
            'id' => 2,
            'slug' => 'another-post',
            'title' => "Ceci est un autre post" 
        ],
        [
            'id' => 3,
            'slug' => 'last-example',
            'title' => "Ceci est un autre exemple de post post" 
        ],
    ];

    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 5})
     */
    public function list($page = 1)
    {
        return new JsonResponse(
            [
                'page' => $page,
                'data' => array_map(function($item){
                    return $this->generateUrl('blog_by_slug', ['slug' => $item['slug']]);
                },  self::POSTS)
            ]
            );
    }
      /**
     * @Route("/{id}", name="blog_id" , requirements={"id"="\d+"})
     */
    public function post($id)
    {
        return new JsonResponse(
          
                self::POSTS[array_search($id , array_column(self::POSTS, 'id'))] 
        ); 
    }

    /**
    * @Route("/{slug}", name="blog_by_slug")
    */
    public function postBySlug($slug)
    {
        return new JsonResponse(
            self::POSTS[array_search($slug , array_column(self::POSTS, 'slug'))]
        );  
    }

    /**
    * @Route("/create", name="blog_add", methods={"POST"})
    */
    public function add(Request $request)
    {
       /** @SerialiZer $serializer */
       $serializer =  $this->get('serializer');
       $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

       $em = $this->getDoctrine()->getManager();
       $em->persist($blogPost);
    }
}
