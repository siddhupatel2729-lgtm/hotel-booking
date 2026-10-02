<?php require __DIR__.'/../config.php'; post_only();
try {
  $s = $pdo->prepare("DELETE FROM rooms WHERE room_id=?"); $s->execute([(int)($_POST['id'] ?? 0)]);
  $s->rowCount() ? flash('Room deleted.') : flash('Room not found.', 'error');
} catch (PDOException $e) { flash('This room has bookings, so it cannot be deleted. Set its status to Maintenance instead.', 'error'); }
redirect('index.php');
