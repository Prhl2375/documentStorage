<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Files — Document Storage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #0f0f0f;
            --surface: #161616;
            --border: #2a2a2a;
            --accent: #e8ff47;
            --accent-dim: #b8cc2a;
            --text: #e0e0e0;
            --muted: #666;
            --danger: #ff4757;
            --pdf: #ff6b35;
            --docx: #4a9eff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 14px;
            min-height: 100vh;
        }

        .site-header {
            border-bottom: 1px solid var(--border);
            padding: 18px 0;
        }

        .site-header .brand {
            font-family: 'IBM Plex Mono', monospace;
            font-weight: 600;
            font-size: 15px;
            color: var(--accent);
            letter-spacing: .05em;
            text-decoration: none;
        }

        .site-header .brand span {
            color: var(--muted);
        }

        .header-nav a {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
        }

        .header-nav a:hover {
            color: var(--text);
        }

        .header-nav a.active {
            color: var(--accent);
        }

        .stats-bar {
            display: flex;
            gap: 32px;
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 32px;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .stat-value {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 20px;
            font-weight: 600;
        }

        .stat-label {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10px;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .section-label {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .files-table-wrap {
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
        }

        .files-table {
            width: 100%;
            border-collapse: collapse;
        }

        .files-table thead th {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            padding: 12px 16px;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            text-align: left;
            white-space: nowrap;
        }

        .files-table tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        .files-table tbody tr:last-child {
            border-bottom: none;
        }

        .files-table tbody tr:hover {
            background: var(--surface);
        }

        .files-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        .file-name-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .file-type-badge {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .badge-pdf {
            background: rgba(255, 107, 53, .15);
            color: var(--pdf);
            border: 1px solid rgba(255, 107, 53, .3);
        }

        .badge-docx {
            background: rgba(74, 158, 255, .15);
            color: var(--docx);
            border: 1px solid rgba(74, 158, 255, .3);
        }

        .file-name-text {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 300px;
        }

        .file-meta {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            color: var(--muted);
        }

        .expires-soon {
            color: var(--danger);
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
        }

        .expires-ok {
            color: var(--muted);
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
        }

        .btn-delete {
            background: none;
            border: 1px solid transparent;
            color: var(--muted);
            font-size: 14px;
            padding: 4px 8px;
            border-radius: 2px;
            cursor: pointer;
            transition: color .15s, border-color .15s, background .15s;
            line-height: 1;
        }

        .btn-delete:hover {
            color: var(--danger);
            border-color: rgba(255, 71, 87, .3);
            background: rgba(255, 71, 87, .08);
        }

        .empty-state {
            padding: 80px 24px;
            text-align: center;
            color: var(--muted);
        }

        .empty-state i {
            font-size: 36px;
            display: block;
            margin-bottom: 16px;
            opacity: .3;
        }

        .empty-state p {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .btn-go-upload {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            background: var(--accent);
            color: #0f0f0f;
            border: none;
            padding: 9px 22px;
            border-radius: 2px;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s;
        }

        .btn-go-upload:hover {
            background: var(--accent-dim);
        }

        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 4px;
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 16px 20px;
        }

        .modal-title {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
        }

        .modal-body {
            padding: 20px;
            font-size: 13px;
            color: var(--muted);
        }

        .modal-body strong {
            font-family: 'IBM Plex Mono', monospace;
            color: var(--text);
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: 12px 20px;
            gap: 8px;
        }

        .btn-modal-cancel {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 7px 16px;
            border-radius: 2px;
            cursor: pointer;
            transition: border-color .15s, color .15s;
        }

        .btn-modal-cancel:hover {
            border-color: var(--text);
            color: var(--text);
        }

        .btn-modal-confirm {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            background: var(--danger);
            border: none;
            color: #fff;
            padding: 7px 16px;
            border-radius: 2px;
            cursor: pointer;
            transition: opacity .15s;
        }

        .btn-modal-confirm:hover {
            opacity: .85;
        }

        .btn-close {
            filter: invert(1) opacity(.4);
        }

        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast-msg {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            padding: 12px 18px;
            border-radius: 2px;
            border-left: 3px solid;
            background: var(--surface);
            box-shadow: 0 4px 24px rgba(0, 0, 0, .5);
            animation: toastIn .2s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 260px;
        }

        .toast-msg.success {
            border-color: var(--accent);
            color: var(--accent);
        }

        .toast-msg.error {
            border-color: var(--danger);
            color: var(--danger);
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <header class="site-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" class="brand">doc<span>/</span>storage</a>
                <nav class="header-nav d-flex gap-4">
                    <a href="{{ route('home') }}">Upload</a>
                    <a href="{{ route('files.list') }}" class="active">Files</a>
                    <a href="{{ route('messages') }}">Messages</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container py-5">

        <div class="stats-bar">
            <div class="stat-item">
                <span class="stat-value">{{ $documents->count() }}</span>
                <span class="stat-label">files</span>
            </div>
            <div class="stat-item">
                <span class="stat-value">
                    @php
                        $totalBytes = $documents->sum('size');
                        if ($totalBytes < 1024) {
                            echo $totalBytes . ' B';
                        } elseif ($totalBytes < 1048576) {
                            echo round($totalBytes / 1024, 1) . ' KB';
                        } else {
                            echo round($totalBytes / 1048576, 2) . ' MB';
                        }
                    @endphp
                </span>
                <span class="stat-label">total size</span>
            </div>
            <div class="stat-item">
                <span
                    class="stat-value">{{ $documents->filter(fn($d) => $d->expires_at->diffInHours(now()) < 2)->count() }}</span>
                <span class="stat-label">expiring soon</span>
            </div>
        </div>

        <div class="section-label">all files</div>

        <div class="files-table-wrap">
            <table class="files-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Uploaded</th>
                        <th>Expires</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $document)
                        @php
                            $hoursLeft = now()->diffInHours($document->expires_at, false);
                            $expiresSoon = $hoursLeft < 2;
                            $size = $document->size;
                            if ($size < 1024) {
                                $formatted = $size . ' B';
                            } elseif ($size < 1048576) {
                                $formatted = round($size / 1024, 1) . ' KB';
                            } else {
                                $formatted = round($size / 1048576, 2) . ' MB';
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="file-name-cell">
                                    @if ($document->mime_type === 'application/pdf')
                                        <span class="file-type-badge badge-pdf">PDF</span>
                                    @else
                                        <span class="file-type-badge badge-docx">DOCX</span>
                                    @endif
                                    <span class="file-name-text" title="{{ $document->original_name }}">
                                        {{ $document->original_name }}
                                    </span>
                                </div>
                            </td>
                            <td><span class="file-meta">{{ $formatted }}</span></td>
                            <td><span class="file-meta">{{ $document->created_at->format('d.m.Y H:i') }}</span></td>
                            <td>
                                <span class="{{ $expiresSoon ? 'expires-soon' : 'expires-ok' }}">
                                    @if ($hoursLeft <= 0)
                                        deleting...
                                    @elseif($hoursLeft < 1)
                                        less than 1h
                                    @else
                                        in {{ $hoursLeft }}h
                                    @endif
                                </span>
                            </td>
                            <td>
                                <button class="btn-delete" data-id="{{ $document->id }}"
                                    data-name="{{ $document->original_name }}">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    <p>No files yet</p>
                                    <a href="{{ route('home') }}" class="btn-go-upload">
                                        Upload file
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete file</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Delete <strong id="deleteFileName"></strong>?
                    <br>
                    <small
                        style="color:var(--danger);font-family:'IBM Plex Mono',monospace;font-size:11px;margin-top:8px;display:block;">
                        This action is irreversible. An email notification will be sent.
                    </small>
                </div>
                <div class="modal-footer">
                    <button class="btn-modal-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn-modal-confirm" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {
            const CSRF = $('meta[name="csrf-token"]').attr('content');
            let deleteFileId = null;
            const deleteModal = new bootstrap.Modal($('#deleteModal')[0]);

            function toast(msg, type = 'success') {
                const icon = type === 'success' ?
                    '<i class="bi bi-check-circle"></i>' :
                    '<i class="bi bi-exclamation-triangle"></i>';
                const el = $(`<div class="toast-msg ${type}">${icon} ${msg}</div>`);
                $('#toastContainer').append(el);
                setTimeout(() => el.fadeOut(300, () => el.remove()), 3500);
            }

            $(document).on('click', '.btn-delete', function() {
                deleteFileId = $(this).data('id');
                $('#deleteFileName').text($(this).data('name'));
                deleteModal.show();
            });

            $('#confirmDeleteBtn').on('click', function() {
                if (!deleteFileId) return;

                $.ajax({
                    url: '/documents/' + deleteFileId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    success: function() {
                        deleteModal.hide();
                        toast('File deleted');
                        $(`tr [data-id="${deleteFileId}"]`).closest('tr').fadeOut(300,
                            function() {
                                $(this).remove();
                            });
                        deleteFileId = null;
                    },
                    error: function() {
                        toast('Delete error', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>
