<?php

namespace Heliostat\Task3;

class ClickService
{
    public function __construct(private ClickRepository $repo)
    {
    }

    public function accept(array $data): void
    {
        $click = new Click(
            $data['click_id'],
            (int)$data['offer_id'],
            $data['source'],
            $data['timestamp'],
            $data['signature']
        );
        $this->repo->store($click);
    }

    public function stats(string $from, string $to, string $sort): array
    {
        return $this->repo->stats($from, $to, $sort);
    }

    public function byDate(string $date): array
    {
        return $this->repo->byDate($date);
    }
}