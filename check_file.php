<?php
$lines = file('resources/views/products/show.blade.php');
foreach ($lines as $i => $line) {
    if (strpos($line, 'max=') !== false && strpos($line, 'quantity') !== false) {
        echo "Line " . ($i+1) . ": " . $line;
    }
}
