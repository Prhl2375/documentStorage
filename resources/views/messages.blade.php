<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages — Document Storage</title>
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
            --text: #e0e0e0;
            --muted: #666;
            --danger: #ff4757;
            --pdf: #ff6b35;
            --docx: #4a9eff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

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

        .site-header .brand span { color: var(--muted); }

        .header-nav a {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            color: var(--muted);
            text-decoration: none;
            transition: color .15s;
        }

        .header-nav a:hover { color: var(--text); }
        .header-nav a.active { color: var(--accent); }

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
        }

        .files-table tbody tr:last-child { border-bottom: none; }

        .files-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        .file-meta {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            color: var(--muted);
        }

        .file-name {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 280px;
        }

        .file-type-badge {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 2px;
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

        .source-badge {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 9px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 2px;
        }

        .source-manual {
            background: rgba(232, 255, 71, .1);
            color: var(--accent);
            border: 1px solid rgba(232, 255, 71, .25);
        }

        .source-expired {
            background: rgba(255, 71, 87, .1);
            color: var(--danger);
            border: 1px solid rgba(255, 71, 87, .25);
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
                    <a href="{{ route('files.list') }}">Files</a>
                    <a href="{{ route('messages') }}" class="active">Messages</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="container py-5">

        <div class="section-label">deletion log</div>

        <div class="files-table-wrap">
            <table class="files-table">
                <thead>
                    <tr>
                        <th>File</th>
                        <th>Size</th>
                        <th>Source</th>
                        <th>Deleted at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $msg)
                        @php
                            $ctx  = $msg['context'] ?? [];
                            $size = $ctx['size'] ?? 0;
                            if ($size < 1024)         $fmt = $size . ' B';
                            elseif ($size < 1048576)  $fmt = round($size / 1024, 1) . ' KB';
                            else                      $fmt = round($size / 1048576, 2) . ' MB';
                            $isPdf = ($ctx['mime_type'] ?? '') === 'application/pdf';
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="file-type-badge {{ $isPdf ? 'badge-pdf' : 'badge-docx' }}">
                                        {{ $isPdf ? 'PDF' : 'DOCX' }}
                                    </span>
                                    <span class="file-name" title="{{ $ctx['name'] ?? '' }}">
                                        {{ $ctx['name'] ?? '—' }}
                                    </span>
                                </div>
                            </td>
                            <td><span class="file-meta">{{ $fmt }}</span></td>
                            <td>
                                <span class="source-badge source-{{ $ctx['source'] ?? 'manual' }}">
                                    {{ $ctx['source'] ?? '—' }}
                                </span>
                            </td>
                            <td><span class="file-meta">{{ $ctx['deleted_at'] ?? $msg['datetime'] ?? '—' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x"></i>
                                    <p>No deletion messages yet</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
