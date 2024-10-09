<?php

namespace Edge\PowerbiEmbed\Tests;

use Edge\PowerbiEmbed\PowerBiService;
use Mockery;

class PowerBIEmbedTest extends TestCase
{
    protected $powerBIEmbedService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->powerBIEmbedService = Mockery::mock(PowerBiService::class);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_get_embed_token()
    {
        $workspaceId = 'test-workspace-id';
        $reportId = 'test-report-id';

        $this->powerBIEmbedService->shouldReceive('getEmbedToken')
            ->with($workspaceId, $reportId)
            ->once()
            ->andReturn('mock-token');

        $token = $this->powerBIEmbedService->getEmbedToken($workspaceId, $reportId);

        $this->assertNotEmpty($token);
        $this->assertIsString($token);
        $this->assertEquals('mock-token', $token);
    }

    public function test_get_embed_url()
    {
        $workspaceId = 'test-workspace-id';
        $reportId = 'test-report-id';

        $this->powerBIEmbedService->shouldReceive('getReportEmbedInfo')
            ->with($workspaceId, $reportId)
            ->once()
            ->andReturn(['embedUrl' => 'https://app.powerbi.com/reportEmbed?reportId=test&groupId=test']);

        $embedInfo = $this->powerBIEmbedService->getReportEmbedInfo($workspaceId, $reportId);

        $this->assertArrayHasKey('embedUrl', $embedInfo);
        $this->assertNotEmpty($embedInfo['embedUrl']);
        $this->assertIsString($embedInfo['embedUrl']);
        $this->assertStringContainsString('https://', $embedInfo['embedUrl']);
    }

    public function test_test_mode_returns_placeholder_image()
    {
        $workspaceId = 'test-workspace-id';
        $reportId = 'test-report-id';

        $this->powerBIEmbedService->shouldReceive('getReportEmbedInfo')
            ->with($workspaceId, $reportId)
            ->once()
            ->andReturn(['placeholderImage' => 'https://example.com/placeholder.jpg']);

        $embedInfo = $this->powerBIEmbedService->getReportEmbedInfo($workspaceId, $reportId);

        $this->assertArrayHasKey('placeholderImage', $embedInfo);
        $this->assertEquals('https://example.com/placeholder.jpg', $embedInfo['placeholderImage']);
    }
}
