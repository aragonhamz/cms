<?php
if (isset($_GET['v1'])) {
    $v1 = $_GET['v1'];
    echo "Value of v1 is: " . htmlspecialchars($v1);
} else {
    echo "No 'v1' parameter provided.";
}
?>