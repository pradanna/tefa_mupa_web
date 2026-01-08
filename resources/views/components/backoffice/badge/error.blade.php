<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition>
    <style>
        .toast {
            background-color: #fff;
            border: 1px solid #e6e8ec;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            font-family: 'Inter', sans-serif;
            max-width: 350px;
        }
        .toast-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e6e8ec;
            color: #495057;
            font-weight: 600;
        }
        .toast-body {
            color: #6c757d;
        }
        .toast-danger .toast-header {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
    <div class="toast toast-danger show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false">
                <rect width="100%" height="100%" fill="#dc3545"></rect>
            </svg>
            <strong class="me-auto">Error</strong>
            <!-- bisa tambahkan slot untuk waktu/error type lain jika diperlukan -->
            <small>{{ $time ?? '' }}</small>
            <button type="button" class="btn-close ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close" @click="show = false"></button>
        </div>
        <div class="toast-body">
            {{ $slot }}
        </div>
    </div>
</div>
