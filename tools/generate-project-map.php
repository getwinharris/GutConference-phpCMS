<?php
/**
 * Project map generator.
 *
 * Writes:
 *   docs/project-map.json     (route registry; validator compares to this)
 *   docs/PROJECT_MAP.md       (route list, unchanged)
 *   docs/project-map.mmd      (route graph, unchanged)
 *   docs/systematic-map.json  (full codebase scan: controllers, services,
 *                              integrations, views, schema, storage, gaps)
 *   docs/systematic-map.md    (human-readable systematic map with gaps)
 *
 * The systematic scan surfaces:
 *   - controllers and services declared on disk but not wired to any route
 *   - views on disk but not bound to any route
 *   - storage JSON files with no schema entry, and schema collections with
 *     no file on disk
 *   - integrations on disk but not listed in the registry
 *   - which services read which collections (static search)
 */
require __DIR__ . '/../app/bootstrap.php';

$root = dirname(__DIR__);
$map = App\Services\ProjectMapService::registry();
$scan = App\Services\ProjectMapService::scan();

file_put_contents($root . '/docs/project-map.json',
    json_encode($map, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
file_put_contents($root . '/docs/systematic-map.json',
    json_encode($scan, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
file_put_contents($root . '/docs/systematic-map.md', render_systematic_md($scan));

$md = "# Project Map\n\n";
foreach ($map['routes'] as $route) {
    $services = $route['services'] ? implode(', ', $route['services']) : 'none';
    $md .= "- `{$route['path']}` → `{$route['controller']}` → {$services}\n";
}
file_put_contents($root . '/docs/PROJECT_MAP.md', $md);

$mmd = render_mermaid($map, $scan);
file_put_contents($root . '/docs/project-map.mmd', $mmd);
file_put_contents($root . '/docs/systematic-map.mmd', render_systematic_mermaid($map, $scan));

echo json_encode($scan['summary'], JSON_PRETTY_PRINT) . "\n";

function render_mermaid(array $map, array $scan): string {
    $out = "flowchart LR\n";
    $out .= "  classDef route fill:#e3f2fd,stroke:#1976d2,color:#0d47a1\n";
    $out .= "  classDef controller fill:#fff3e0,stroke:#f57c00,color:#e65100\n";
    $out .= "  classDef service fill:#e8f5e9,stroke:#388e3c,color:#1b5e20\n";
    foreach ($map['routes'] as $i => $route) {
        $routeNode = 'r'.$i;
        $ctrlNode = 'c_'.preg_replace('/[^A-Za-z0-9]/', '_', $route['controller']);
        $out .= "  {$routeNode}[\"" . $route['method'] . ' ' . $route['path'] . "\"]:::route --> {$ctrlNode}[\"" . $route['controller'] . "\"]:::controller\n";
        foreach ($route['services'] as $service) {
            $svcNode = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $service);
            $out .= "  {$ctrlNode} --> {$svcNode}[\"" . $service . "\"]:::service\n";
        }
    }
    return $out;
}

function render_systematic_mermaid(array $map, array $scan): string {
    $out = "flowchart LR\n";
    $out .= "  classDef route fill:#e3f2fd,stroke:#1976d2,color:#0d47a1\n";
    $out .= "  classDef controller fill:#fff3e0,stroke:#f57c00,color:#e65100\n";
    $out .= "  classDef service fill:#e8f5e9,stroke:#388e3c,color:#1b5e20\n";
    $out .= "  classDef view fill:#f3e5f5,stroke:#7b1fa2,color:#4a148c\n";
    $out .= "  classDef integration fill:#fce4ec,stroke:#c2185b,color:#880e4f\n";
    $out .= "  classDef schema fill:#e0f7fa,stroke:#00838f,color:#006064\n";
    $out .= "  classDef storage fill:#fff8e1,stroke:#ff8f00,color:#e65100\n";
    $out .= "  classDef tool fill:#ede7f6,stroke:#5e35b1,color:#311b92\n";
    $out .= "  classDef gap fill:#ffebee,stroke:#c62828,color:#b71c1c\n";

    $bucket = function (string $path): string {
        if (str_starts_with($path, '/admin')) return 'ADMIN';
        if (str_starts_with($path, '/auth') || $path === '/login' || $path === '/signup' || str_starts_with($path, '/forgot') || str_starts_with($path, '/reset') || $path === '/logout') return 'AUTH';
        if (str_starts_with($path, '/events') && str_contains($path, 'payment') || str_contains($path, 'checkout')) return 'PAYMENT';
        if (str_starts_with($path, '/support')) return 'SUPPORT';
        if (str_starts_with($path, '/payment')) return 'PAYMENT';
        return 'PUBLIC';
    };
    $routesByBucket = ['PUBLIC' => [], 'AUTH' => [], 'PAYMENT' => [], 'SUPPORT' => [], 'ADMIN' => []];
    foreach ($map['routes'] as $i => $route) {
        $routesByBucket[$bucket($route['path'])][] = [$i, $route];
    }
    foreach ($routesByBucket as $name => $routes) {
        if (empty($routes)) continue;
        $out .= "  subgraph B{$name}[\"$name routes\"]\n";
        foreach ($routes as [$i, $route]) {
            $routeNode = 'r'.$i;
            $out .= "    {$routeNode}[\"" . $route['method'] . ' ' . $route['path'] . "\"]:::route\n";
        }
        $out .= "  end\n";
    }

    $out .= "  subgraph CTRL[\"Controllers\"]\n";
    foreach (array_keys($scan['controllers']) as $cls) {
        $node = 'c_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
        $out .= "    {$node}[\"$cls\"]:::controller\n";
    }
    $out .= "  end\n";

    $out .= "  subgraph SVC[\"Services\"]\n";
    foreach (array_keys($scan['services']) as $cls) {
        $node = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
        $out .= "    {$node}[\"$cls\"]:::service\n";
    }
    $out .= "  end\n";

    $out .= "  subgraph VIEW[\"Views\"]\n";
    foreach (array_keys($scan['views']) as $path) {
        $node = 'v_'.md5($path);
        $label = str_replace(['/', '.'], ['_', '_'], $path);
        $out .= "    {$node}[\"$label\"]:::view\n";
    }
    $out .= "  end\n";

    $out .= "  subgraph INT[\"Integrations\"]\n";
    foreach (array_keys($scan['integrations']) as $cls) {
        $node = 'i_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
        $out .= "    {$node}[\"$cls\"]:::integration\n";
    }
    $out .= "  end\n";

    $out .= "  subgraph SCH[\"Schema collections (storage/schema/collections.json)\"]\n";
    foreach (array_keys($scan['schema_collections']) as $name) {
        $node = 'sc_'.preg_replace('/[^A-Za-z0-9]/', '_', $name);
        $out .= "    {$node}[\"$name\"]:::schema\n";
    }
    $out .= "  end\n";

    $out .= "  subgraph DAT[\"Storage data files (storage/data/)\"]\n";
    foreach (array_keys($scan['storage_collections']) as $name) {
        $node = 'd_'.preg_replace('/[^A-Za-z0-9]/', '_', $name);
        $out .= "    {$node}[\"$name.json\"]:::storage\n";
    }
    $out .= "  end\n";

    $out .= "  subgraph TOL[\"Tools (tools/)\"]\n";
    foreach (['generate-project-map.php', 'validate-project-map.php', 'smoke-local.php'] as $tool) {
        $node = 't_'.preg_replace('/[^A-Za-z0-9]/', '_', $tool);
        $out .= "    {$node}[\"$tool\"]:::tool\n";
    }
    $out .= "  end\n";

    $out .= "  subgraph GAP[\"Gaps & missing links\"]\n";
    $gi = 0;
    foreach ($scan['gaps'] as $g) {
        $node = 'g'.$gi++;
        $out .= "    {$node}[\"[" . $g['severity'] . "] " . $g['type'] . "\"]:::gap\n";
    }
    $out .= "  end\n";

    foreach ($map['routes'] as $i => $route) {
        $routeNode = 'r'.$i;
        $ctrlNode = 'c_'.preg_replace('/[^A-Za-z0-9]/', '_', $route['controller']);
        $out .= "  {$routeNode} --> {$ctrlNode}\n";
        foreach ($route['services'] as $service) {
            $svcNode = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $service);
            $out .= "  {$ctrlNode} --> {$svcNode}\n";
        }
        $viewKey = $route['page'];
        if (isset($scan['views']['views/' . $viewKey . '.php'])) {
            $viewNode = 'v_'.md5('views/' . $viewKey . '.php');
            $ctrlNode = 'c_'.preg_replace('/[^A-Za-z0-9]/', '_', $route['controller']);
            $out .= "  {$ctrlNode} -.renders.-> {$viewNode}\n";
        }
    }

    foreach ($scan['collection_usage'] as $col => $svcs) {
        $svcNode = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $svcs[0] ?? '');
        $colNode = 'sc_'.preg_replace('/[^A-Za-z0-9]/', '_', $col);
        $datNode = 'd_'.preg_replace('/[^A-Za-z0-9]/', '_', $col);
        if (isset($scan['schema_collections'][$col])) {
            $out .= "  {$svcNode} --> {$colNode}\n";
        }
        if (isset($scan['storage_collections'][$col])) {
            $out .= "  {$colNode} -.file.-> {$datNode}\n";
        }
    }

    if (isset($scan['integrations']['RazorpayClient'])) {
        $out .= "  s_PaymentService --> i_RazorpayClient\n";
    }
    if (isset($scan['integrations']['GoogleOAuthClient'])) {
        $out .= "  s_SecretService --> i_GoogleOAuthClient\n";
    }

    $out .= "  t_generate_project_map_php -.regenerates.-> TOL\n";
    $out .= "  t_validate_project_map_php -.checks.-> TOL\n";
    $out .= "  t_smoke_local_php -.smoke.-> TOL\n";

    $gi = 0;
    $unwiredControllers = [];
    foreach ($scan['controllers'] as $cls => $info) {
        if (!isset($scan['routes_by_controller'][$cls])) $unwiredControllers[] = $cls;
    }
    foreach ($unwiredControllers as $cls) {
        $ctrlNode = 'c_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
        $gapNode = 'g'.$gi++;
        $out .= "  {$ctrlNode} -.missing-route.-> {$gapNode}\n";
    }
    $unwiredServices = [];
    foreach ($scan['services'] as $cls => $info) {
        if (!isset($scan['routes_by_service'][$cls])) $unwiredServices[] = $cls;
    }
    foreach ($unwiredServices as $cls) {
        $svcNode = 's_'.preg_replace('/[^A-Za-z0-9]/', '_', $cls);
        $gapNode = 'g'.$gi++;
        $out .= "  {$svcNode} -.no-route.-> {$gapNode}\n";
    }
    $unwiredViews = [];
    foreach ($scan['views'] as $path => $info) {
        $logicalKey = preg_replace('/\.php$/', '', $path);
        $logicalKey = str_replace('views/', '', $logicalKey);
        if (!isset($scan['routes_by_view'][$logicalKey]) && !str_starts_with($path, 'views/layouts/') && !str_starts_with($path, 'views/partials/')) {
            $unwiredViews[] = $path;
        }
    }
    foreach ($unwiredViews as $path) {
        $viewNode = 'v_'.md5($path);
        $gapNode = 'g'.$gi++;
        $out .= "  {$viewNode} -.no-route.-> {$gapNode}\n";
    }
    foreach ($scan['storage_collections'] as $name => $info) {
        if (!isset($scan['schema_collections'][$name])) {
            $datNode = 'd_'.preg_replace('/[^A-Za-z0-9]/', '_', $name);
            $gapNode = 'g'.$gi++;
            $out .= "  {$datNode} -.no-schema.-> {$gapNode}\n";
        }
    }
    foreach ($scan['schema_collections'] as $name => $info) {
        if (!isset($scan['storage_collections'][$name])) {
            $colNode = 'sc_'.preg_replace('/[^A-Za-z0-9]/', '_', $name);
            $gapNode = 'g'.$gi++;
            $out .= "  {$colNode} -.no-file.-> {$gapNode}\n";
        }
    }

    return $out;
}

function render_systematic_md(array $r): string {
    $md = "# Systematic Project Map\n\n";
    $md .= "_Generated: " . $r['generated_at'] . "_\n\n";
    $md .= "## Summary\n\n";
    foreach ($r['summary'] as $k => $v) $md .= "- **$k**: $v\n";
    $md .= "\n## Gaps (sorted by severity)\n\n";
    if (empty($r['gaps'])) {
        $md .= "_No gaps detected._\n\n";
    } else {
        foreach ($r['gaps'] as $g) {
            $md .= "- **[{$g['severity']}] {$g['type']}** — {$g['detail']}\n";
        }
        $md .= "\n";
    }
    $md .= "## Controllers\n\n";
    foreach ($r['controllers'] as $cls => $info) {
        $used = isset($r['routes_by_controller'][$cls]);
        $md .= "- `$cls` ({$info['method_count']} methods) — " . ($used ? 'wired' : 'NOT in route registry') . "\n";
    }
    $md .= "\n## Services\n\n";
    foreach ($r['services'] as $cls => $info) {
        $used = isset($r['routes_by_service'][$cls]);
        $md .= "- `$cls` ({$info['method_count']} methods) — " . ($used ? 'wired' : 'NOT in route registry') . "\n";
    }
    $md .= "\n## Integrations\n\n";
    foreach ($r['integrations'] as $cls => $info) {
        $md .= "- `$cls` ({$info['method_count']} methods) at `{$info['file']}`\n";
    }
    $md .= "\n## Views\n\n";
    foreach ($r['views'] as $path => $info) {
        $md .= "- `$path` ({$info['size']} bytes)\n";
    }
    $md .= "\n## Schema collections\n\n";
    foreach ($r['schema_collections'] as $name => $info) {
        $md .= "- `$name` — " . count($info['fields']) . " fields, file `" . ($info['file'] ?? '?') . "`\n";
    }
    $md .= "\n## Storage data files\n\n";
    foreach ($r['storage_collections'] as $name => $info) {
        $md .= "- `$name` — {$info['record_count']} records, " . $info['size'] . " bytes\n";
    }
    $md .= "\n## Collection usage (static search)\n\n";
    foreach ($r['collection_usage'] as $col => $svcs) {
        $md .= "- `$col` ← " . implode(', ', $svcs) . "\n";
    }
    return $md;
}
