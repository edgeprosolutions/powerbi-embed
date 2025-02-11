<?php

namespace Edge\PowerBiEmbed\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Log;
use Edge\PowerBiEmbed\PowerBiService;
use Illuminate\Support\Str;

class PowerBiEmbed extends Component
{
    public int $elementId;

    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $workspaceId,
        public string $reportId,
        public string $pageName,
        public string $filters
        )
    {  
        $this->elementId = rand(1,99999999999);
        $this->pageName = $pageName ?? null;
        $this->filters = $filters ?? null;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        try {
            if (empty($this->workspaceId) || empty($this->reportId)) {
                throw new \Exception('Workspace ID and report ID are required parameters.');
            }
            $clientId = config('powerbi-embed.client_id');
            $clientSecret = config('powerbi-embed.client_secret');
            $tenantId = config('powerbi-embed.tenant_id');
            $testMode = config('powerbi-embed.test_mode', false);

            $powerBiService = new PowerBiService($clientId, $clientSecret, $tenantId, $testMode);

            $embedInfo = $powerBiService->getReportEmbedInfo($this->workspaceId, $this->reportId);

            Log::info('embedInfo: ' . json_encode($embedInfo));
            if ($testMode) {
                return view('powerbi-embed::test-embed', $embedInfo);
            }

            return view('powerbi-embed::embed', $embedInfo);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return view('powerbi-embed::error', ['error' => $e->getMessage()]);
        }
    }
}
