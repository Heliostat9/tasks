<?php

namespace Heliostat\Task3;

class Click
{
    public function __construct(
        public string $clickId,
        public int $offerId,
        public string $source,
        public string $timestamp,
        public string $signature
    ) {
    }
}