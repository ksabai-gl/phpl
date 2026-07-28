<?php

declare(strict_types=1);

$roots = ['app', 'Modules', 'tests', 'database', 'routes', 'config', 'bootstrap', 'resources', 'lang', 'openapi', 'elastic'];
$extMap = [
    'PHP' => ['php'],
    'TS/TSX' => ['ts', 'tsx'],
    'JS/JSX/Vue' => ['js', 'jsx', 'vue'],
    'CSS/SCSS' => ['css', 'scss'],
    'Blade/HTML' => ['blade.php', 'html'],
    'JSON/YAML/SQL' => ['json', 'yml', 'yaml', 'sql'],
];
$skip = ['node_modules', 'vendor', 'dist', '.git', 'storage' . DIRECTORY_SEPARATOR . 'framework'];

$by = [];
$totalFiles = 0;
$totalLines = 0;
$newFiles = 0;
$newLines = 0;

foreach ($roots as $root) {
    if (!is_dir($root)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file->isFile()) {
            continue;
        }

        $path = str_replace('\\', '/', $file->getPathname());
        foreach ($skip as $s) {
            if (str_contains($path, str_replace('\\', '/', $s))) {
                continue 2;
            }
        }

        $name = $file->getFilename();
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (str_ends_with(strtolower($name), '.blade.php')) {
            $ext = 'blade.php';
        }

        $group = null;
        foreach ($extMap as $g => $exts) {
            if (in_array($ext, $exts, true)) {
                $group = $g;
                break;
            }
        }
        if ($group === null) {
            continue;
        }

        $content = file_get_contents($file->getPathname());
        if ($content === false) {
            continue;
        }
        $lines = substr_count($content, "\n");
        if ($content !== '' && !str_ends_with($content, "\n")) {
            $lines++;
        }

        $by[$group]['files'] = ($by[$group]['files'] ?? 0) + 1;
        $by[$group]['lines'] = ($by[$group]['lines'] ?? 0) + $lines;
        $totalFiles++;
        $totalLines += $lines;

        if (
            str_starts_with($path, 'Modules/') ||
            str_starts_with($path, 'resources/js/enterprise/') ||
            str_starts_with($path, 'tests/Unit/Modules/')
        ) {
            $newFiles++;
            $newLines += $lines;
        }
    }
}

foreach ($by as $g => $v) {
    echo "{$g} files={$v['files']} lines={$v['lines']}\n";
}
echo "TOTAL files={$totalFiles} lines={$totalLines}\n";
echo "NEW_ENTERPRISE files={$newFiles} lines={$newLines}\n";
echo 'MODULES_DIR=' . (is_dir('Modules') ? count(glob('Modules/*', GLOB_ONLYDIR)) : 0) . "\n";
