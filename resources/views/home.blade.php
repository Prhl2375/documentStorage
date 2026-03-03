<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document Storage</title>
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

        .page-wrap {
            min-height: calc(100vh - 57px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 0;
        }

        .upload-card {
            width: 100%;
            max-width: 520px;
        }

        .section-label {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 20px;
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

        .upload-zone {
            border: 1.5px dashed var(--border);
            border-radius: 4px;
            background: var(--surface);
            padding: 56px 24px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            position: relative;
        }

        .upload-zone:hover,
        .upload-zone.drag-over {
            border-color: var(--accent);
            background: #1a1a0f;
        }

        .upload-zone.drag-over::after {
            content: 'Drop file';
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'IBM Plex Mono', monospace;
            font-size: 18px;
            color: var(--accent);
            background: rgba(15, 15, 15, .88);
            border-radius: 4px;
        }

        .upload-icon {
            width: 56px;
            height: 56px;
            border: 1.5px solid var(--border);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: var(--accent);
            background: var(--bg);
        }

        .upload-title {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .upload-sub {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 24px;
        }

        .btn-browse {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            font-weight: 500;
            background: var(--accent);
            color: #0f0f0f;
            border: none;
            padding: 9px 22px;
            border-radius: 2px;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-browse:hover {
            background: var(--accent-dim);
        }

        #fileInput {
            display: none;
        }

        #filePreview {
            display: none;
            margin-top: 14px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 12px 16px;
            align-items: center;
            gap: 12px;
        }

        #filePreview.show {
            display: flex;
        }

        .file-preview-name {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .file-preview-size {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            color: var(--muted);
            flex-shrink: 0;
        }

        .btn-clear-file {
            background: none;
            border: none;
            color: var(--muted);
            cursor: pointer;
            font-size: 16px;
            padding: 0;
            line-height: 1;
            transition: color .15s;
        }

        .btn-clear-file:hover {
            color: var(--danger);
        }

        #uploadProgress {
            display: none;
            margin-top: 12px;
        }

        #uploadProgress.show {
            display: block;
        }

        .progress {
            height: 2px;
            background: var(--border);
            border-radius: 0;
            overflow: hidden;
        }

        .progress-bar {
            background: var(--accent);
            transition: width .2s;
        }

        .progress-label {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 11px;
            color: var(--muted);
            margin-top: 6px;
            display: flex;
            justify-content: space-between;
        }

        .btn-upload {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 13px;
            font-weight: 500;
            background: transparent;
            color: var(--accent);
            border: 1.5px solid var(--accent);
            padding: 11px 28px;
            border-radius: 2px;
            cursor: pointer;
            transition: background .15s, color .15s;
            width: 100%;
            margin-top: 14px;
        }

        .btn-upload:hover {
            background: var(--accent);
            color: #0f0f0f;
        }

        .btn-upload:disabled {
            border-color: var(--border);
            color: var(--muted);
            cursor: not-allowed;
        }

        .btn-upload:disabled:hover {
            background: transparent;
            color: var(--muted);
        }

        #successState {
            display: none;
            text-align: center;
            padding: 32px 0 8px;
        }

        #successState.show {
            display: block;
        }

        .success-icon {
            font-size: 32px;
            color: var(--accent);
            display: block;
            margin-bottom: 12px;
        }

        .success-title {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .success-sub {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 20px;
        }

        .btn-upload-another {
            font-family: 'IBM Plex Mono', monospace;
            font-size: 12px;
            background: none;
            border: 1px solid var(--border);
            color: var(--muted);
            padding: 8px 18px;
            border-radius: 2px;
            cursor: pointer;
            transition: border-color .15s, color .15s;
        }

        .btn-upload-another:hover {
            border-color: var(--text);
            color: var(--text);
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
                    <a href="{{ route('home') }}" class="active">Upload</a>
                    <a href="{{ route('files.list') }}">Files</a>
                    <a href="{{ route('messages') }}">Messages</a>
                </nav>
            </div>
        </div>
    </header>

    <div class="page-wrap">
        <div class="container">
            <div class="upload-card mx-auto">

                <div class="section-label">file upload</div>

                <div class="upload-zone" id="dropZone">
                    <div class="upload-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <div class="upload-title">Drop file here</div>
                    <div class="upload-sub">PDF or DOCX · max 10 MB · stored for 24 hours</div>
                    <button class="btn-browse" id="browseBtn" type="button">Browse file</button>
                    <input type="file" id="fileInput"
                        accept=".pdf,.docx,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                </div>

                <div id="filePreview">
                    <span id="previewIcon" style="font-size:20px;flex-shrink:0">📄</span>
                    <span class="file-preview-name" id="previewName"></span>
                    <span class="file-preview-size" id="previewSize"></span>
                    <button class="btn-clear-file" id="clearFileBtn"><i class="bi bi-x"></i></button>
                </div>

                <div id="uploadProgress">
                    <div class="progress">
                        <div class="progress-bar" id="progressBar" style="width:0%"></div>
                    </div>
                    <div class="progress-label">
                        <span id="progressStatus">Uploading...</span>
                        <span id="progressPercent">0%</span>
                    </div>
                </div>

                <button class="btn-upload" id="uploadBtn" disabled>
                    <i class="bi bi-arrow-up-circle me-2"></i>Upload
                </button>

                <div id="successState">
                    <i class="bi bi-check-circle success-icon"></i>
                    <div class="success-title">File uploaded</div>
                    <div class="success-sub">Will be automatically deleted after 24 hours</div>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn-upload-another" id="uploadAnotherBtn">
                            Upload another
                        </button>
                        <a href="{{ route('home') }}" class="btn-upload-another" style="text-decoration:none">
                            Go to files →
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(function() {
            const MAX_SIZE = 10 * 1024 * 1024;
            const ALLOWED = [
                'application/pdf',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ];
            const CSRF = $('meta[name="csrf-token"]').attr('content');
            let selectedFile = null;

            // ── Helpers ───────────────────────────────────────────
            function formatBytes(b) {
                if (b < 1024) return b + ' B';
                if (b < 1048576) return (b / 1024).toFixed(1) + ' KB';
                return (b / 1048576).toFixed(2) + ' MB';
            }

            function toast(msg, type = 'success') {
                const icon = type === 'success' ?
                    '<i class="bi bi-check-circle"></i>' :
                    '<i class="bi bi-exclamation-triangle"></i>';
                const el = $(`<div class="toast-msg ${type}">${icon} ${msg}</div>`);
                $('#toastContainer').append(el);
                setTimeout(() => el.fadeOut(300, () => el.remove()), 3500);
            }

            // ── File select ───────────────────────────────────────
            function setFile(file) {
                if (!file) return;
                if (!ALLOWED.includes(file.type)) {
                    toast('Only PDF and DOCX are allowed', 'error');
                    return;
                }
                if (file.size > MAX_SIZE) {
                    toast('File exceeds 10 MB', 'error');
                    return;
                }
                selectedFile = file;
                $('#previewIcon').text(file.type === 'application/pdf' ? '🟥' : '🟦');
                $('#previewName').text(file.name);
                $('#previewSize').text(formatBytes(file.size));
                $('#filePreview').addClass('show');
                $('#uploadBtn').prop('disabled', false);
                $('#successState').removeClass('show');
            }

            function clearFile() {
                selectedFile = null;
                $('#fileInput').val('');
                $('#filePreview').removeClass('show');
                $('#uploadProgress').removeClass('show');
                $('#uploadBtn').prop('disabled', true);
            }

            // ── Drag & Drop ───────────────────────────────────────
            const zone = $('#dropZone')[0];
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                $('#dropZone').addClass('drag-over');
            });
            zone.addEventListener('dragleave', () => $('#dropZone').removeClass('drag-over'));
            zone.addEventListener('drop', e => {
                e.preventDefault();
                $('#dropZone').removeClass('drag-over');
                setFile(e.dataTransfer.files[0]);
            });

            $('#browseBtn').on('click', e => {
                e.stopPropagation();
                $('#fileInput').click();
            });
            $('#dropZone').on('click', function(e) {
                if ($(e.target).closest('button, input').length === 0) $('#fileInput').click();
            });
            $('#fileInput').on('change', function() {
                setFile(this.files[0]);
            });
            $('#clearFileBtn').on('click', clearFile);

            $('#uploadBtn').on('click', function() {
                if (!selectedFile) return;
                const fd = new FormData();
                fd.append('file', selectedFile);

                $('#uploadProgress').addClass('show');
                $('#progressBar').css('width', '0%');
                $('#progressPercent').text('0%');
                $('#progressStatus').text('Uploading...');
                $('#uploadBtn').prop('disabled', true);

                $.ajax({
                    url: '{{ route('files.upload') }}',
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    },
                    xhr: function() {
                        const xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const pct = Math.round(e.loaded / e.total * 100);
                                $('#progressBar').css('width', pct + '%');
                                $('#progressPercent').text(pct + '%');
                            }
                        });
                        return xhr;
                    },
                    success: function() {
                        $('#progressBar').css('width', '100%');
                        $('#progressStatus').text('Done!');
                        setTimeout(() => {
                            clearFile();
                            $('#uploadProgress').removeClass('show');
                            $('#successState').addClass('show');
                        }, 400);
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message ?? 'Upload error';
                        toast(msg, 'error');
                        $('#progressStatus').text('Error');
                        $('#uploadBtn').prop('disabled', false);
                    }
                });
            });

            // ── Upload another ────────────────────────────────────
            $('#uploadAnotherBtn').on('click', function() {
                $('#successState').removeClass('show');
            });
        });
    </script>
</body>

</html>
