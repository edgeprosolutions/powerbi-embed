<?php

namespace Edge\PowerBiEmbed;

use GuzzleHttp\Client;

class PowerBiService
{
    private $client;
    private $clientId;
    private $clientSecret;
    private $tenantId;
    private $accessToken;
    private $testMode;
    private $placeholderImage;

    public function __construct($clientId, $clientSecret, $tenantId, $testMode = false)
    {
        $this->client = new Client();
        $this->clientId = trim($clientId);
        $this->clientSecret = trim($clientSecret);
        $this->tenantId = trim($tenantId);
        $this->testMode = $testMode;
        $this->placeholderImage = env('POWERBI_PLACEHOLDER_IMAGE', 'https://example.com/path/to/default-placeholder.jpg');

        if (empty($this->clientSecret)) {
            throw new \Exception('Client secret is empty or not set.');
        }
    }

    public function authenticate()
    {
        \Log::info('Attempting Power BI authentication');
        \Log::info('Client ID: ' . $this->clientId);
        \Log::info('Tenant ID: ' . $this->tenantId);
        // Don't log the full client secret for security reasons
        \Log::info('Client Secret length: ' . strlen($this->clientSecret));

        if (empty($this->clientSecret)) {
            throw new \Exception('Client secret is not set or empty.');
        }

        if (empty($this->tenantId)) {
            throw new \Exception('Tenant ID is not set or empty.');
        }

        try {
            $response = $this->client->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token", [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'scope' => 'https://analysis.windows.net/powerbi/api/.default',
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            $this->accessToken = $result['access_token'];
            
            // Log successful authentication
            \Log::info('Power BI authentication successful');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $errorBody = json_decode((string) $response->getBody(), true);
            $errorMessage = 'Failed to authenticate with Power BI: ' . ($errorBody['error_description'] ?? 'Unknown error');
            \Log::error($errorMessage);
            throw new \Exception($errorMessage);
        }
    }

    public function getEmbedToken($workspaceId, $reportId, $datasetId = null, $username = null)
    {
        $url = "https://api.powerbi.com/v1.0/myorg/groups/{$workspaceId}/reports/{$reportId}/GenerateToken";
        $body = [
            'accessLevel' => 'View',
            'datasetId' => $datasetId,
            'allowSaveAs' => false,
        ];
        if ($username) {
            $body['identities'] = [
                [
                    'username' => $username,
                    'roles' => [],
                    'datasets' => [$datasetId]
                ]
            ];
        }
        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => "Bearer {$this->accessToken}",
                'Content-Type' => 'application/json',
            ],
            'json' => $body,
        ]);

        $result = json_decode($response->getBody()->getContents(), true);
        return $result['token'];
    }

    public function getReportEmbedInfo($workspaceId, $reportId, $username = null)
    {
        $this->authenticate();

        $embedToken = $this->generateEmbedToken($workspaceId, $reportId, $username);
        $reportDetails = $this->getReportDetails($workspaceId, $reportId);

        return [
            'embedToken' => $embedToken,
            'embedUrl' => $reportDetails['embedUrl'],
            'reportId' => $reportId,
            'placeholderImage' => $this->placeholderImage,
        ];
    }

    public function getReportDetails($workspaceId, $reportId)
    {
        $url = "https://api.powerbi.com/v1.0/myorg/groups/{$workspaceId}/reports/{$reportId}";

        $response = $this->client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    public function generateEmbedToken($workspaceId, $reportId, $username = null)
    {
        $this->authenticate();

        $url = "https://api.powerbi.com/v1.0/myorg/groups/{$workspaceId}/reports/{$reportId}/GenerateToken";
        $body = [
            'accessLevel' => 'View',
            'allowSaveAs' => false,
        ];
        if ($username) {
            $body['identities'] = [
                [
                    'username' => $username,
                    'roles' => ['report.read'],
                    'datasets' => ['*']
                ]
            ];
        }

        $response = $this->client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => $body
        ]);

        $result = json_decode($response->getBody(), true);
        return $result['token'];
    }
}
