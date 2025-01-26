<?php

declare(strict_types=1);

namespace Rodrifarias\ShortUrl\Infra\Database\Repository;

use Exception;
use PDO;
use PDOException;
use Psr\Log\LoggerInterface;

class BaseRepository
{
    public function __construct(protected PDO $pdo, protected LoggerInterface $logger)
    {
    }

    /**
     * @param string $table
     * @param string $columnsSelect
     * @param array<int,string> $condition
     * @return array<string,string>|null
     */
    public function findOneBy(string $table, string $columnsSelect, array $condition): ?array
    {
        [$column, $operator, $value] = $condition;
        $stmt = $this->pdo->prepare("SELECT $columnsSelect FROM $table WHERE $column $operator ?");
        $stmt->execute([$value]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * @param array<string, int|string|bool> $columnsValues
     */
    public function insert(string $table, array $columnsValues): int|string
    {
        return $this->transaction(function () use ($table, $columnsValues) {
            $columns = array_keys($columnsValues);
            $columnsMapPrepare = array_map(fn ($c) => ':' . $c, $columns);
            $query = $this->pdo->prepare('INSERT INTO ' . $table . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $columnsMapPrepare) . ')');
            $query->execute($columnsValues);
            return $this->pdo->lastInsertId();
        });
    }

    public function transaction(callable $callable): int|string
    {
        try {
            $this->pdo->beginTransaction();
            $result = $callable();
            $this->pdo->commit();
            return $result;
        } catch (PDOException $e) {
            throw new Exception('Error creating data: ' . $e->getMessage(), 500);
        }
    }
}
