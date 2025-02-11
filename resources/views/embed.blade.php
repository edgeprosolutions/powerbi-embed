@if(config('powerbi-embed.test_mode', false))
    <div id="reportContainer-{{ $elementId }}" style="height: 700px; display: flex; justify-content: center; align-items: center; background-color: #f0f0f0;">
        <img src="{{ $placeholderImage }}" alt="Placeholder Graph" style="max-width: 100%; max-height: 100%; object-fit: contain;">
    </div>
@else
    <div id="reportContainer-{{ $elementId }}" style="height: 700px;"></div>
    <script src="https://cdn.jsdelivr.net/npm/powerbi-client@2.19.1/dist/powerbi.min.js"></script>
    <script>
        var models = window['powerbi-client'].models;
        var reportContainer{{ $elementId }} = document.getElementById('reportContainer-{{ $elementId }}');
        var reportLoadConfig{{ $elementId }} = {
            type: 'report',
            tokenType: models.TokenType.Embed,
            accessToken: '{{ $embedToken }}',
            embedUrl: '{{ $embedUrl }}',
            id: '{{ $reportId }}',
            permissions: models.Permissions.All,
            filters: [{!! $filters !!}],
            pageName: '{{ $pageName }}',
            settings: {
                filterPaneEnabled: false,
                navContentPaneEnabled: false,
            }
        };
        var report{{ $elementId }} = powerbi.embed(reportContainer{{ $elementId }}, reportLoadConfig{{ $elementId }});
    </script>
@endif
