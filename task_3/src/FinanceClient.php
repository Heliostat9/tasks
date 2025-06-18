<?php

namespace Heliostat\Task3;

class FinanceClient
{
    public function send(array $clicks): void
    {
        file_put_contents('php://stdout', "Exported " . count($clicks) . " clicks\n");
    }
}