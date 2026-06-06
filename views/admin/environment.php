<div class="admin-card">
    <h2>Environment Policy</h2>
    <p>Project env files are disabled. Configure secrets in <a href="/admin/integrations">Integrations</a>; server-level environment variables may be provided outside this repository only when the host requires them.</p>
</div>

<div class="admin-card">
    <h2>Google AI Studio Diagnostics</h2>
    <p>Gemini API config is read from encrypted integrations first, with server-level environment variables as an external fallback. Use separate model lists for vision/language, audio, and TTS routing.</p>
    <div class="admin-form__row">
        <label>Configured
            <input readonly value="<?= !empty($geminiDiagnostics['configured']) ? 'Yes' : 'No API key configured' ?>">
        </label>
        <label>Models Supporting generateContent
            <input readonly value="<?= e((string)count(array_filter($geminiDiagnostics['models'] ?? [], fn($model) => !empty($model['supports_generate_content'])))) ?>">
        </label>
    </div>
    <?php if(!empty($geminiDiagnostics['failures'])): ?>
        <div class="table-wrap" style="margin-top:14px;">
            <table>
                <thead><tr><th>Category</th><th>Model</th><th>Failures</th><th>Last Error</th></tr></thead>
                <tbody>
                    <?php foreach($geminiDiagnostics['failures'] as $failure): ?>
                        <tr><td><?= e($failure['category'] ?? '') ?></td><td><?= e($failure['model'] ?? '') ?></td><td><?= e((string)($failure['failure_count'] ?? 0)) ?></td><td><?= e($failure['last_error'] ?? '') ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="admin-card">
    <div style="display:flex; justify-content:space-between; align-items:center; gap:var(--space-md); flex-wrap:wrap; margin-bottom:var(--space-md);">
        <div>
            <h2 style="margin:0 0 var(--space-xs);">Storage Permissions</h2>
            <p style="margin:0;">JSON data, backups, and media uploads must be writable by PHP on shared hosting. Project env files are not used.</p>
        </div>
        <form method="post" action="/admin/environment/fix-permissions">
            <button class="btn btn-sm btn-ghost">Fix Writable Paths</button>
        </form>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Area</th><th>Path</th><th>Permission</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach($permissions as $item): ?>
                    <tr>
                        <td><?= e($item['label']) ?></td>
                        <td><code><?= e($item['path']) ?></code></td>
                        <td><?= e($item['permission']) ?></td>
                        <td>
                            <?php if(!$item['exists']): ?>
                                <span class="badge badge--error">Missing</span>
                            <?php elseif($item['writable']): ?>
                                <span class="badge badge--success">Writable</span>
                            <?php else: ?>
                                <span class="badge badge--warning">Not writable</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
