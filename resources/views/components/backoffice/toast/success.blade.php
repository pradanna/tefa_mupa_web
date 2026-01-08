@props([
    'message' => '',
])

@if ($message)
<div class="toast show toast-success"
     role="alert"
     aria-live="assertive"
     aria-atomic="true"
     data-bs-autohide="false"
     data-bs-toggle="toast"
     style="min-width:280px;">
  <div class="toast-header bg-success text-white">
    <span class="me-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-circle-check-filled" width="24" height="24" viewBox="0 0 24 24" stroke="none" fill="currentColor">
        <path d="M12 20a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-1.45-6.86 4.15-4.15a1 1 0 0 1 1.41 1.41l-4.85 4.85a1 1 0 0 1-1.48-.06l-2.15-2.29a1 1 0 1 1 1.44-1.38l1.48 1.62z"/>
      </svg>
    </span>
    <strong class="me-auto">Success</strong>
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
