<?php
namespace App\Entity;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;



/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 *  @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *         "title": "partial",
 *         "content": "partial",
 *         "author": "exact",
 *         "author.name": "partial"
 *     }
 * )
 * @ApiFilter(
 *     DateFilter::class,
 *     properties={
 *         "postedAt"
 *     }
 * )
 * @ApiFilter(RangeFilter::class, properties={"id"})
 * @ApiFilter(
 *     OrderFilter::class,
 *     properties={
 *         "id",
 *         "published",
 *         "title"
 *     },
 *     arguments={"orderParameterName"="_order"}
 * )
 * @ApiFilter(PropertyFilter::class, arguments={
 *     "parameterName": "properties",
 *     "overrideDefaultProperties": false,
 *     "whitelist": {"id", "slug", "title", "content", "author"}
 * })
 * @ApiResource(
 *     attributes={"order"={"postedAt": "DESC"}, "maximum_items_per_page"=30},
 *     itemOperations={
 *          "get"={
 *             "normalization_context"={
 *                 "groups"={"get-post"}
 *             }
 *         },
 *          "put"={
 *               "access_control"="is_granted('ROLE_EDITOR') or (is_granted('ROLE_WRITER') and object.getAuthor() == user)"
 *           
 *   }
 * 
 *  },
 *       collectionOperations={
 *         "get", 
 *         "post"={
 *             "access_control"="is_granted('ROLE_WRITER')"
 * }
 * },
 * normalizationContext={"groups"={"post_read"}},
 * denormalizationContext={
 *            "groups"={"post"}
 * }
* )
 *  
  */

 class Post implements AuthoredEntityInterface
 {
     /**
      * @var int|null
      * @ORM\Id
      * @ORM\Column(type="integer")
      * @ORM\GeneratedValue
      * @Groups({ "post_read", "get-post"})
      * 
      */
   private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Le tire  de l'article est obligatoire")
     * @Assert\Length(min=5,minMessage="Le titre doit faire entre 5 et 255 caractères")
     *  @Groups({"post",  "post_read", "get-post"})
     */

   private string $title;

   /**
    * 
    * @ORM\Column(type="text")
    * @Assert\NotBlank(message="Le contenu de l'article  est obligatoire")
   *  @Assert\Length(min=20, minMessage="Le contenu doit faire  20   caractères au minumum  ")
   *  @Groups({"post", "post_read", "get-post"})
 */
   private $content;
   /**
    * 
    * @ORM\Column(type="text")
    * @Groups({"post_read", "get-post"})
    *
    */
   private  $slug;

   /**
    * @var DateTimeInterface
    * @ORM\Column(type="datetime_immutable")
    * @Groups({"post_read", "get-post" })
    *
    */
   private DateTimeInterface $postedAt;

/**
 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
 * @ORM\JoinColumn(nullable=false)
 * @Groups({"post_read", "get-post" })
 * 
 */

  private $author;

/**
 * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
 * @ApiSubresource()
 * @Groups({"get-post-with-author" })
 * 
 */
private $comments;

/**
  * @ORM\ManyToMany(targetEntity="App\Entity\Image")
  * @ORM\JoinTable()
  * @ApiSubresource()
  * @Groups({"post", "get-post-with-author"})
  */
  private $images;

public function __construct()
{
    $this->postedAt = new DateTimeImmutable();
    $this->comments = new ArrayCollection();
    $this->images = new ArrayCollection();
       
}

public function getComments(): Collection
{
    return $this->comments;
}
   /**
    * Get the value of id
    *
    * @return  int|null
    */ 
   public function getId()
   {
      return $this->id;
   }

   
 /**
    * Get the value of content
    *
    * @return  string
    */ 
   public function getContent()
   {
      return $this->content;
   }

   /**
    * Set the value of content
    *
    * @param  string  $content
    *
    * @return  self
    */ 
   public function setContent(string $content)
   {
      $this->content = $content;

      return $this;
   }

   /**
    * Get the value of postedAt
    *
    * @return  DateTimeInterface
    */ 
   public function getPostedAt()
   {
      return $this->postedAt;
   }

   /**
    * Set the value of postedAt
    *
    * @param  DateTimeInterface  $postedAt
    *
    * @return  self
    */ 
   public function setPostedAt(DateTimeInterface $postedAt)
   {
      $this->postedAt = $postedAt;

      return $this;
   }

  

   public function getSlug(): ?string
   {
       return $this->slug;
   }
         
   public function setSlug(string $slug): self
   {
       $this->slug = $slug;
         
       return $this;
   }
   
   public function getTitle(): ?string
   {
       return $this->title;
   }

   public function setTitle(string $title): self
   {
       $this->title = $title;

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
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image)
    {
        $this->images->add($image);
    }

    public function removeImage(Image $image)
    {
        $this->images->removeElement($image);
    }

    public function __toString(): string
    {
        return $this->title;
    }
 }