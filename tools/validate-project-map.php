<?php
/**
 * Project map validator.
 *
 * The single project-map artifact is docs/systematic-map.mmd. This
 * validator regenerates the mermaid in memory and compares it
 * byte-for-byte to the on-disk file. If they differ, the map is stale
 * and the change must be regenerated with
 * `php tools/generate-project-map.php` and committed.
 */
require __DIR__ . '/../app/bootstrap.php';

$root = dirname(__DIR__);
$path = $root . '/docs/systematic-map.mmd';
$expected = App\Services\ProjectMapService::renderSystematicMermaid();
if (!is_file($path) || trim(file_get_contents($path)) !== trim($expected)) {
    fwrite(STDERR, "Generated project map is stale. Run php tools/generate-project-map.php and commit docs/systematic-map.mmd.\n");
    exit(1);
}
echo "Project map valid\n";
