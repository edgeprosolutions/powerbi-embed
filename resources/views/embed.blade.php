@if(config('powerbi-embed.test_mode', false))
    <div id="reportContainer-{{ $elementId }}" class="powerbi-container-test-mode">
        <img src="{{ $placeholderImage }}" alt="Placeholder Graph">
    </div>
@else
    <div 
        id="reportContainer-{{ $elementId }}"
        class="powerbi-container"
        data-element-id="{{ $elementId }}"
        data-embed-token="{{ $embedToken }}"
        data-embed-url="{{ $embedUrl }}"
        data-report-id="{{ $reportId }}"
        data-page-name="{{ $pageName }}"
        data-filters="{{ $filters }}"
    >
    </div>
@endif
