@if(config('powerbi-embed.test_mode', false))
    <div id="reportContainer" style="height: 600px; display: flex; justify-content: center; align-items: center; background-color: #f0f0f0;">
        <img src="{{ $placeholderImage }}" alt="Placeholder Graph" style="max-width: 100%; max-height: 100%; object-fit: contain;">
    </div>
@else
    <div id="reportContainer" style="height: 600px;"></div>
    <script src="https://cdn.jsdelivr.net/npm/powerbi-client@2.19.1/dist/powerbi.min.js"></script>
    <script>
        var models = window['powerbi-client'].models;
        var reportContainer = document.getElementById('reportContainer');
        var reportLoadConfig = {
            type: 'report',
            tokenType: models.TokenType.Embed,
            accessToken: '{{ $embedToken }}',
            embedUrl: '{{ $embedUrl }}',
            id: '{{ $reportId }}',
            permissions: models.Permissions.All,
            settings: {
                filterPaneEnabled: false,
                navContentPaneEnabled: true
            }
        };
        var report = powerbi.embed(reportContainer, reportLoadConfig);
    </script>
@endif
