<?php
$f = 'resources/views/products/show.blade.php';
$c = file_get_contents($f);

// Add onclick to decrement button
$c = str_replace(
    'id="decrementQty"><i class="fas fa-minus">',
    'id="decrementQty" onclick="changeQty(-1)"><i class="fas fa-minus">',
    $c
);

// Add onclick to increment button
$c = str_replace(
    'id="incrementQty"><i class="fas fa-plus">',
    'id="incrementQty" onclick="changeQty(1)"><i class="fas fa-plus">',
    $c
);

// Fix max attribute for null-safety
$c = str_replace(
    'max="{{ $product->stock }}" readonly',
    'max="{{ (int)($product->stock ?? 9999) }}" readonly',
    $c
);

file_put_contents($f, $c);
echo "Done\n";
