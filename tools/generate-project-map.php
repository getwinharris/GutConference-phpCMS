<?php
/**
 * Project map generator.
 *
 * Writes the single project-map artifact:
 *   docs/systematic-map.mmd
 *
 * The artifact is a comprehensive mermaid flowchart. Subgraphs:
 *   PUBLIC / AUTH / PAYMENT / SUPPORT / ADMIN routes
 *   Controllers (app/Controllers/)
 *   Services (app/Services/)
 *   Views (views/)
 *   Integrations (integrations/)
 *   Schema collections (storage/schema/collections.json)
 *   Storage data files (storage/data/)
 *   Tools (tools/)
 *   Gaps & missing links (red)
 *
 * Edges:
 *   - solid: route -> controller -> service, service -> schema collection,
 *     controller -.renders.-> view, payment/google integrations,
 *     tools regenerate / validate / smoke the map
 *   - dashed (red): missing routes, unwired services, unwired views,
 *     storage files with no schema, schema collections with no file
 *
 * Tokens: this is the only map file. AI agents and humans read it once.
 */
require __DIR__ . '/../app/bootstrap.php';

$root = dirname(__DIR__);
$path = $root . '/docs/systematic-map.mmd';
$mmd = App\Services\ProjectMapService::renderSystematicMermaid();
file_put_contents($path, $mmd);

$scan = App\Services\ProjectMapService::scan();
echo json_encode($scan['summary'], JSON_PRETTY_PRINT) . "\n";
echo "Wrote docs/systematic-map.mmd (" . strlen($mmd) . " bytes)\n";
