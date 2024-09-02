<div class="modal fade" id="action-{{ $id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vendor API Response</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"> 
                @if (!empty($response))           
                @php
                    $decodedHtml = html_entity_decode($response);
                    $decodedResponse = json_decode($decodedHtml, true);
                @endphp
                <ul>
                    @if (is_array($decodedResponse))
                    @foreach ($decodedResponse as $key => $value)
                        @if (!is_array($key) && !is_array($value))
                        <li>{{ $key }}: {{ $value }}</li>
                        @endif
                    @endforeach
                    @endif
                </ul>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    pre {
        white-space: pre-wrap;
        white-space: -moz-pre-wrap;
        white-space: -pre-wrap;
        white-space: -o-pre-wrap;
        word-wrap: break-word;
        background-color: #f8f8f8;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
</style>