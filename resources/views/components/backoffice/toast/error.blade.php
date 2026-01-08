@props([
    'message' => '',
])

@if ($message)
@php
    $alertId = 'backoffice-error-alert-' . \Illuminate\Support\Str::random(8);
@endphp
<div
    class="alert alert-danger fade show h-auto shadow position-relative"
    role="alert"
    id="{{ $alertId }}"
    style="max-width: 95vw; width: 100%; box-sizing: border-box; word-break: break-word; overflow-wrap: anywhere;"
>
    <button
        type="button"
        class="btn-close position-absolute top-0 end-0 m-2"
        data-bs-dismiss="alert"
        aria-label="Close"
        onclick="var alertElem = document.getElementById('{{ $alertId }}'); if(alertElem){alertElem.classList.remove('show'); alertElem.classList.add('hide'); setTimeout(function(){alertElem.parentNode && alertElem.parentNode.removeChild(alertElem);}, 150);}"
        style="z-index: 2;"
    ></button>
    <div class="d-flex align-items-start gap-2">
        <div class="alert-icon flex-shrink-0 mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="14"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                stroke-linecap="round" stroke-linejoin="round"
                class="icon alert-icon icon-2" style="vertical-align: middle;">
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                <path d="M12 8v4" />
                <path d="M12 16h.01" />
            </svg>
        </div>
        <div class="flex-grow-1 min-w-0">
            <h4 class="alert-heading mb-1" style="font-size: 1rem;">Error</h4>
            <div class="alert-description"
                 style="font-size: .95rem; word-break: break-word; overflow-wrap: anywhere; white-space: pre-line; max-width: 100vw;">
                {{ $message }}
            </div>
        </div>
    </div>
</div>
<script>
    setTimeout(function() {
        var alertElem = document.getElementById(@json($alertId));
        if (alertElem) {
            alertElem.classList.remove('show');
            alertElem.classList.add('hide');
            setTimeout(function() {
                alertElem.parentNode && alertElem.parentNode.removeChild(alertElem);
            }, 150); // wait for fade out transition
        }
    }, 3000);
</script>
@endif
