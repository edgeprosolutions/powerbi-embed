<?php

namespace Edge\PowerBiEmbed\Tags;

use Statamic\Tags\Tags;
use Edge\PowerBiEmbed\PowerBiService;
use Illuminate\Support\Facades\Log;

class PowerBiEmbed extends Tags
{
    protected static $handle = 'powerbi_embed';

    public function index()
    {
        $workspaceId = $this->params->get('workspace_id');
        $reportId = $this->params->get('report_id');
        $clientId = config('powerbi-embed.client_id');
        $clientSecret = config('powerbi-embed.client_secret');
        $tenantId = config('powerbi-embed.tenant_id');
        $testMode = config('powerbi-embed.test_mode', false);

        $powerBiService = new PowerBiService($clientId, $clientSecret, $tenantId, $testMode);

        try {
            $embedInfo = $powerBiService->getReportEmbedInfo($workspaceId, $reportId);
            Log::info('embedInfo: ' . json_encode($embedInfo));
            if ($testMode) {
                return view('powerbi-embed::test-embed', $embedInfo);
            }

            return view('powerbi-embed::embed', $embedInfo);
        } catch (\Exception $e) {
            return view('powerbi-embed::error', ['error' => $e->getMessage()]);
        }
    }
}
