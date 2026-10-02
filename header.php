<?php
$root = $root ?? ''; $section = $section ?? ''; $f = flash();
$nav = ['dash'=>['index.php','Dashboard'],'rooms'=>['rooms/index.php','Rooms'],'customers'=>['customers/index.php','Customers'],'bookings'=>['bookings/index.php','Bookings']];
?><!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title ?? 'Hotel') ?> | Grand Stay</title>
<link rel="stylesheet" href="<?= $root ?>assets/css/style.css"></head><body>
<aside class="side" id="side">
  <div class="logo">Grand Stay<small>Booking manager</small></div>
  <nav><?php foreach ($nav as $k => [$u, $l]): ?><a href="<?= $root.$u ?>" class="<?= $section===$k?'on':'' ?>"><?= $l ?></a><?php endforeach; ?></nav>
</aside>
<div class="main">
<header class="topbar"><button id="toggle" aria-label="Menu">&#9776;</button><strong><?= e($title ?? '') ?></strong><span><?= date('D, d M Y') ?></span></header>
<div class="content">
<?php if ($f): ?><div class="alert <?= e($f[1]) ?>"><?= e($f[0]) ?></div><?php endif; ?>
