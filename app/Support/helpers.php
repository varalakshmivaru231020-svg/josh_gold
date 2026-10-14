<?php

// asset() plus "?v=<file modified time>", so a replaced image with the same name is
// fetched again (the server tells browsers to cache images for years).
if (! function_exists('versioned_asset')) {
    function versioned_asset(string $path): string
    {
        $file = public_path($path);

        return asset($path) . (is_file($file) ? '?v=' . filemtime($file) : '');
    }
}
