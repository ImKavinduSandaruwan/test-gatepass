<?php
$folder = __DIR__ . '/uploads/items';

if (is_dir($folder)) {
    if (is_writable($folder)) {
        echo "The folder uploads/items exists and is writable.";
    } else {
        echo "The folder uploads/items exists but is NOT writable.";
    }
} else {
    echo "The folder uploads/items does NOT exist.";
}
?>
