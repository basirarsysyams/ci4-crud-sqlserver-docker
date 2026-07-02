<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-name" content="<?= csrf_token() ?>">
    <title><?= $title ?? 'AppStarter'; ?></title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        margin: 0;
        padding: 0;
        background: #f5f7fa;
        color: #1f2937;
    }

    .navbar {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        padding: 16px 32px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .navbar h1 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    .page-container {
        max-width: 1280px;
        margin: 24px auto;
        padding: 0 24px;
    }

    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 24px;
        margin-bottom: 24px;
    }

    h2 {
        margin: 0 0 20px 0;
        color: #1f2937;
        font-size: 24px;
        font-weight: 700;
    }

    /* DataTables custom styling */
    .dataTables_wrapper {
        padding-top: 16px;
    }

    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        margin-bottom: 16px;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        margin: 0 4px;
        font-size: 14px;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
    }

    table.dataTable thead th {
        background: #f9fafb;
        color: #374151;
        font-weight: 600;
        font-size: 14px;
        padding: 14px 16px !important;
        border-bottom: 2px solid #e5e7eb !important;
    }

    table.dataTable tbody td {
        padding: 14px 16px !important;
        border-bottom: 1px solid #e5e7eb !important;
        font-size: 14px;
        color: #4b5563;
    }

    table.dataTable tbody tr:hover {
        background: #f9fafb !important;
    }

    table.dataTable tbody tr:last-child td {
        border-bottom: none !important;
    }

    .dataTables_wrapper .dataTables_paginate {
        padding-top: 16px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 6px 12px !important;
        margin: 0 2px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 6px !important;
        background: white !important;
        color: #374151 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #3b82f6 !important;
        color: white !important;
        border-color: #3b82f6 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        border-color: #9ca3af !important;
        color: #374151 !important;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 16px;
        color: #6b7280;
        font-size: 14px;
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 16px;
        border: none;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-edit {
        background: #fbbf24;
        color: #78350f;
    }

    .btn-edit:hover {
        background: #f59e0b;
    }

    .btn-delete {
        background: #ef4444;
        color: white;
    }

    .btn-delete:hover {
        background: #dc2626;
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #f9fafb;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
        background: #dbeafe;
        color: #1e40af;
    }

    .price-cell {
        font-weight: 600;
        color: #059669;
    }

    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9998;
        align-items: center;
        justify-content: center;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 540px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 18px;
        color: #1f2937;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #6b7280;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        background: #f3f4f6;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        color: #1f2937;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
        display: none;
    }

    .form-error.active {
        display: block;
    }

    .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .required {
        color: #ef4444;
    }

    @media (max-width: 768px) {
        .page-container {
            padding: 0 16px;
        }

        .card {
            padding: 16px;
        }

        .toolbar {
            flex-direction: column;
            gap: 12px;
            align-items: stretch;
        }
    }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>Inventory Management System</h1>
    </div>

    <div class="page-container">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    // Setup CSRF token untuk semua request Ajax
    const csrfName = document.querySelector('meta[name="csrf-name"]').content;
    const csrfHash = document.querySelector('meta[name="csrf-token"]').content;

    $.ajaxSetup({
        data: {
            [csrfName]: csrfHash
        }
    });
    </script>

    <?= $this->renderSection('scripts') ?>

</body>

</html>