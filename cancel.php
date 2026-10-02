<?php require __DIR__.'/../config.php'; post_only();
$id = (int)($_POST['id'] ?? 0);
$s = $pdo->prepare("SELECT room_id,status FROM bookings WHERE booking_id=?"); $s->execute([$id]); $b = $s->fetch();
if (!$b) flash('Booking not found.', 'error');
elseif ($b['status'] !== 'Booked') flash('Only bookings with status Booked can be cancelled.', 'error');
else {
  $pdo->prepare("UPDATE bookings SET status='Cancelled' WHERE booking_id=?")->execute([$id]);
  sync_room($pdo, $b['room_id']); flash("Booking #$id cancelled.");
}
redirect('index.php');
