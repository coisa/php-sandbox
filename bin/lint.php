<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$files = [$root . '/bin/app'];
foreach (['bin', 'config', 'examples', 'public', 'src', 'tests'] as $directory) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root . '/' . $directory, FilesystemIterator::SKIP_DOTS),
    );
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }
}
sort($files);

foreach ($files as $file) {
    $process = proc_open([PHP_BINARY, '-l', $file], [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ], $pipes);
    if (!is_resource($process)) {
        throw new RuntimeException('Could not start PHP syntax validation.');
    }
    fclose($pipes[0]);
    $output = stream_get_contents($pipes[1]);
    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    if (proc_close($process) !== 0) {
        fwrite(STDERR, $output . $errors);
        exit(1);
    }
}

echo 'Syntax OK: ' . count($files) . ' active PHP files.' . PHP_EOL;
