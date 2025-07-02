<?php
$uploadDir = 'uploads/';

echo "This php script is only used for checking whether 'uploads/' directory is writable or not.<br><br>";

if (is_dir($uploadDir) && is_writable($uploadDir)) {
    echo "✅ 'uploads/' directory is writable.";
} else {
    echo "❌ 'uploads/' directory is NOT writable. pls allow write permission";
}
?>
