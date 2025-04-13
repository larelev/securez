<?php

namespace App\Shared\Infrastructure\Event;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Symfony\Component\Serializer\SerializerInterface;
use App\Shared\Domain\Event\DomainEvent;

final class DoctrineEventStore
{
    public function __construct(
        private Connection $db,
        private SerializerInterface $serializer
    ) {}

    /**
     * @throws Exception
     */
    public function append(DomainEvent $event): void
    {
        $this->db->insert('event_store', [
            'type' => $event::class,
            'payload' => $this->serializer->serialize($event, 'json'),
            'occurred_at' => $event->occurredAt()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return DomainEvent[]
     * @throws Exception
     */
    public function all(): array
    {
        $rows = $this->db->fetchAllAssociative('SELECT * FROM event_store ORDER BY occurred_at ASC');

        return array_map(function ($row) {
            return $this->serializer->deserialize($row['payload'], $row['type'], 'json');
        }, $rows);
    }
}
