<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *  @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ApiResource(
 *      attributes={
 *         "order"={"published": "DESC"}
 *      },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_COMMENTATOR') and object.getAuthor() == user)" }
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "access_control"="is_granted('ROLE_COMMENTATOR')"
 *            },
 *          
 *          "api_posts_comments_get_subresource"={
 *             "normalization_context"={
 *                 "groups"={"get-comment-with-author"}
 *             }
 *         }
 *   },
 *  normalizationContext={"groups"={"comments_read"}},
 *     denormalizationContext={
 *         "groups"={"post"}
 *     }
 * )
 *
 */
class Comment implements AuthoredEntityInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"comments_read","get-comment-with-author"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"post", "get-comment-with-author", "comments_read"})
     * @Assert\NotBlank()
     * @Assert\Length(min=5, max=3000)
     */
    private $content;

    /**
     * @var DateTimeInterface
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"get-comment-with-author", "comments_read"})
     * 
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-comment-with-author", "comments_read"})
     * 
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post"})
     */
    private $post;

    public function __construct()
    {
        $this->published = new DateTimeImmutable();
      
       
    }


    public function getId()
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublished(): ?DateTimeInterface
    {
        return $this->published;
    }

     /**
    * Get the value of postedAt
    *
    * @return  DateTimeInterface
    */ 
    public function setPublished(\DateTimeInterface $published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param UserInterface $author
     */
    public function setAuthor(UserInterface $author): AuthoredEntityInterface
    {
        $this->author = $author;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

  
}