<?php

/**
 * Quality gate de cobertura: falla (exit 1) si la cobertura de líneas del reporte Clover
 * es menor que el umbral. Uso: php scripts/check-coverage.php coverage.xml 60
 */
$archivo = $argv[1] ?? 'coverage.xml';
$umbral = (float) ($argv[2] ?? 60);

if (! is_file($archivo)) {
    fwrite(STDERR, "No existe el reporte de cobertura: {$archivo}\n");
    exit(2);
}

$xml = simplexml_load_file($archivo);
$metricas = $xml->project->metrics;
$total = (int) $metricas['statements'];
$cubiertas = (int) $metricas['coveredstatements'];
$porcentaje = $total > 0 ? $cubiertas * 100 / $total : 0.0;

printf("Cobertura de líneas: %.2f%% (%d/%d). Umbral mínimo: %.0f%%\n", $porcentaje, $cubiertas, $total, $umbral);

if ($porcentaje < $umbral) {
    fwrite(STDERR, "QUALITY GATE FALLIDO: la cobertura está por debajo del umbral.\n");
    exit(1);
}

echo "Quality gate de cobertura APROBADO.\n";
