<?php
$replacements = [
    '[#003049]' => 'brand-dark',
    '[#669BBC]' => 'brand-light',
    '[#FDF0D5]' => 'brand-cream',
    '[#C1121F]' => 'brand-red',
    '[#780000]' => 'brand-red-dark',
    '[#F5F1E8]' => 'background',
    '[#1F3A5F]' => 'foreground',
    'bg-[#003049] hover:bg-[#002538]' => 'btn-primary',
    'bg-[#C1121F] hover:bg-[#780000]' => 'btn-danger'
];

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$iterator = new RecursiveIteratorIterator($dir);

$filesModified = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getRealPath());
        $modified = false;
        
        foreach ($replacements as $search => $replace) {
            if (strpos($content, $search) !== false) {
                $content = str_replace($search, $replace, $content);
                $modified = true;
            }
        }
        
        if ($modified) {
            file_put_contents($file->getRealPath(), $content);
            $filesModified++;
            echo "Updated: " . $file->getFilename() . "\n";
        }
    }
}
echo "Total files updated: $filesModified\n";
