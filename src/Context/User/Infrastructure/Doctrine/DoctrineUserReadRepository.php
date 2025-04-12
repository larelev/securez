<?php

namespace App\Context\User\Infrastructure\Doctrine;

use App\Context\User\Application\DTO\UserReadModel;
use App\Context\User\Application\Query\UserReadRepositoryInterface;
use Doctrine\DBAL\Connection;

final class DoctrineUserReadRepository implements UserReadRepositoryInterface
{
    public function __construct(private Connection $db) {}

    public function findAll(): array
    {
        $rows = $this->db->fetchAllAssociative('SELECT id, email FROM user');

        return array_map(fn ($row) => new UserReadModel($row['id'], $row['email']), $rows);
    }
}
