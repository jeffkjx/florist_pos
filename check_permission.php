<?php
$uploadDir = 'uploads/';
$receiptDir = 'receipts/';

echo "This php script is only used for checking whether 'uploads/' and 'receipts/' directory is writable or not.<br><br>";

if (is_dir($uploadDir) && is_writable($uploadDir)) {
    echo "✅ $uploadDir directory is writable.";
} else {
    echo "❌ $uploadDir directory is NOT writable. pls allow write permission";
}

echo "<br><br>";

if (is_dir($receiptDir) && is_writable($receiptDir)) {
    echo "✅ $receiptDir directory is writable.";
} else {
    echo "❌ $receiptDir directory is NOT writable. pls allow write permission";
}
?>
