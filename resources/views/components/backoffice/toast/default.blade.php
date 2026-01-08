@props([
    'message' => '',
])

@if ($message)
<div
  class="toast show toast-error"
  role="alert"
  aria-live="assertive"
  aria-atomic="true"
  data-bs-autohide="false"
  data-bs-toggle="toast"
  style="min-width:280px;"
>
  <div class="toast-header bg-danger text-white">
    <span class="me-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alert-triangle-filled" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="none" fill="currentColor">
        <path d="M13.09 6.12a2 2 0 0 0-2.18 0l-6.57 4.38a2 2 0 0 0-.84 2.46l2.71 7.19a2 2 0 0 0 1.89 1.31h9.41a2 2 0 0 0 1.89-1.31l2.71-7.19a2 2 0 0 0-.84-2.46z"/>
        <path d="M12 10v4m0 4h.01"/>
      </svg>
    </span>
    <strong class="me-auto">Error</strong>
    <button
      type="button"
      class="ms-2 btn-close btn-close-white"
      data-bs-dismiss="toast"
      aria-label="Close"
    ></button>
  </div>
  <div class="toast-body">
    {{ $message }}
  </div>
</div>
@endif
