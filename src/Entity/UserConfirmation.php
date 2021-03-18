<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *             "path"="/users/confirm"
 *         }
 *     },
 *     itemOperations={}
 * )
 */
class UserConfirmation
{
    /**
     * @var string
     * @ApiProperty(identifier=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=30, max=30)
     */
    public $confirmationToken;
}