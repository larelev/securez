<?php

namespace App\Context\User\Application\DTO;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource(
    graphql: ['item_query', 'collection_query']
)]
final class UserReadModel
{
    public function __construct(
        public string $id,
        public string $email
    ) {
    }
}
