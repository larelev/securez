<?php
// api/src/Entity/Person.php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Symfony\Action\NotFoundAction;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(operations: [
    new Get(controller: NotFoundAction::class, read: false, status: 404),
    new Post(messenger: true, output: false, status: 202)
])]
final class Person
{
    #[ApiProperty(identifier: true)]
    public string $id;

    #[Assert\NotBlank]
    public string $name;
}
