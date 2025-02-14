@if(config('powerbi-embed.test_mode', false))
    <div id="reportContainer-{{ $elementId }}" style="height: 700px; display: flex; justify-content: center; align-items: center; background-color: #f0f0f0;">
        <img src="{{ $placeholderImage }}" alt="Placeholder Graph" style="max-width: 100%; max-height: 100%; object-fit: contain;">
    </div>
@else
    <div id="reportContainer-{{ $elementId }}" style="height: 700px;"></div>
    <script src="https://cdn.jsdelivr.net/npm/powerbi-client@2.19.1/dist/powerbi.min.js"></script>
     <script>
        (function() {
            // Capture unique element ID and embed config for this instance
            var elementId = '{{ $elementId }}';
            
            function initPowerBIEmbed() {
                var models = window['powerbi-client'].models;
                var container = document.getElementById('reportContainer-' + elementId);
                if (!container) return;

                var config = {
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

                powerbi.embed(container, config);
            }

            // Initialize on DOMContentLoaded
            document.addEventListener('DOMContentLoaded', function () {
                initPowerBIEmbed();
                console.log('PowerBI DOMContentLoaded for element ' + elementId);
            });

            // If Livewire is present, reinitialize on update and navigated events
            if (window.Livewire) {
                document.addEventListener('livewire:update', function () {
                    initPowerBIEmbed();
                    console.log('PowerBI Livewire update for element ' + elementId);
                });
                document.addEventListener('livewire:navigated', function () {
                    initPowerBIEmbed();
                    console.log('PowerBI Livewire navigated for element ' + elementId);
                });
            }
        })();
    </script>
@endif
