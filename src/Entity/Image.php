<?php

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\UploadImageAction;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;


/**
 * @ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 * attributes={
 *         "order"={"id": "ASC"},
 *        
 *     },
 *   collectionOperations={
 *         "get",
 *         "post"={
 *             "method"="POST",
 *             "path"="/images",
 *             "controller"=UploadImageAction::class,
 *             "defaults"={"_api_receive"=false}
 *         }
 *     },
 *     itemOperations={
 *         "get",
 *         "delete"={
 *             "access_control"="is_granted('ROLE_WRITER')"
 *         }
 * 
 *     }
 * )
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     */
    private $id;

    /**
     * @UploadableField(mapping="images", fileNameProperty="url")
     * @NotNull()
     */
    private $file;

    /**
     * 
     * @ORM\Column(nullable=true)
     * @Groups({"get-post-with-author" })
     */
    private $url;

    public function getId()
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): void
    {
        $this->file = $file;
    }

    public function getUrl()
    {
        return '/images/' . $this->url;
    }

    public function setUrl($url): void
    {
        $this->url = $url;
    }

    public function __toString()
    {
        return $this->id . ':' . $this->url;
    }
}


