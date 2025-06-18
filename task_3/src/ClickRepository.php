<?php

namespace Heliostat\Task3;

use PDO;

class ClickRepository
{
    private PDO $db;

    public function __construct(string $dsn = 'sqlite:' . __DIR__ . '/../data.db')
    {
        $this->db = new PDO($dsn);
        $this->init();
    }

    private function init(): void
    {
        $this->db->exec(
            'CREATE TABLE IF NOT EXISTS clicks (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                click_id TEXT,
                offer_id INTEGER,
                source TEXT,
                timestamp TEXT,
                signature TEXT
            )'
        );
    }

    public function store(Click $click): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO clicks (click_id, offer_id, source, timestamp, signature)
             VALUES (:click_id, :offer_id, :source, :timestamp, :signature)'
        );
        $stmt->execute([
            ':click_id' => $click->clickId,
            ':offer_id' => $click->offerId,
            ':source' => $click->source,
            ':timestamp' => $click->timestamp,
            ':signature' => $click->signature,
        ]);
    }

    public function stats(string $from, string $to, string $orderBy = 'offer_id'): array
    {
        $allowed = ['offer_id', 'source', 'timestamp'];
        if (!in_array($orderBy, $allowed, true)) {
            $orderBy = 'offer_id';
        }
        $stmt = $this->db->prepare(
            "SELECT offer_id, source, count(*) as cnt FROM clicks \n            WHERE date(timestamp) BETWEEN :from AND :to\n            GROUP BY offer_id, source ORDER BY $orderBy"
        );
        $stmt->execute([':from' => $from, ':to' => $to]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function byDate(string $date): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM clicks WHERE date(timestamp) = :date'
        );
        $stmt->execute([':date' => $date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}