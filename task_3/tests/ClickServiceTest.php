<?php

use Heliostat\Task3\ClickService;
use Heliostat\Task3\ClickRepository;
use PHPUnit\Framework\TestCase;

class ClickServiceTest extends TestCase
{
    private ClickService $service;

    protected function setUp(): void
    {
        $repo = new ClickRepository('sqlite::memory:');
        $this->service = new ClickService($repo);
    }

    public function testAcceptStoresClick(): void
    {
        $data = [
            'click_id' => 'abc',
            'offer_id' => 1,
            'source' => 'net',
            'timestamp' => '2025-06-11T14:00:00Z',
            'signature' => 'sig'
        ];
        $this->service->accept($data);
        $rows = $this->service->byDate('2025-06-11');
        $this->assertCount(1, $rows);
        $this->assertEquals('abc', $rows[0]['click_id']);
    }

    public function testStatsAggregation(): void
    {
        $d = [
            'click_id' => 'a',
            'offer_id' => 2,
            'source' => 'n1',
            'timestamp' => '2025-06-11T10:00:00Z',
            'signature' => 'x'
        ];
        $this->service->accept($d);
        $d['click_id'] = 'b';
        $this->service->accept($d);
        $stats = $this->service->stats('2025-06-11', '2025-06-11', 'offer_id');
        $this->assertEquals(2, $stats[0]['cnt']);
    }
}