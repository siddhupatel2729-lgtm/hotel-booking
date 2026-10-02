<?php $id = (int)($_GET["id"] ?? 0); if (!$id) { header("Location: index.php"); exit; } require __DIR__."/_form.php";
