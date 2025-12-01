<?php // Ødelegger sesjonen og sender de til index asap
session_start();
session_destroy();
header("Location: ../public/index.php");
exit();
?>