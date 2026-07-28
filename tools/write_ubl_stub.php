<?php

declare(strict_types=1);

$path = dirname(__DIR__) . '/packages/ubl_invoice/composer.json';
$data = [
    'name' => 'invoiceninja/ubl_invoice',
    'version' => '3.0.2',
    'description' => 'Windows-compatible stub for local runtime',
    'type' => 'library',
    'license' => 'MIT',
    'autoload' => [
        'psr-4' => [
            'InvoiceNinja\\Ubl\\' => 'src/',
        ],
    ],
];

$json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
file_put_contents($path, $json);

$raw = file_get_contents($path);
echo 'bytes=' . strlen($raw) . PHP_EOL;
echo 'bom=' . (str_starts_with($raw, "\xEF\xBB\xBF") ? 'yes' : 'no') . PHP_EOL;
json_decode($raw);
echo 'json=' . json_last_error_msg() . PHP_EOL;
