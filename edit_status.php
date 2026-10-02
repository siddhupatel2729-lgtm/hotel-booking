<?php require __DIR__.'/../config.php'; $root = '../'; $section = 'bookings'; $title = 'Update Booking Status';
$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$s = $pdo->prepare("SELECT b.*, c.name, r.room_number, r.room_type FROM bookings b JOIN customers c ON c.customer_id=b.customer_id JOIN rooms r ON r.room_id=b.room_id WHERE b.booking_id=?");
$s->execute([$id]); $b = $s->fetch();
if (!$b) { flash('Booking not found.', 'error'); redirect('index.php'); }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $new = $_POST['status'] ?? '';
  if (!in_array($new, BOOKING_STATUSES, true)) $errors[] = 'Choose a valid status.';
  elseif ($new !== 'Cancelled' && $b['status'] === 'Cancelled' && room_overlap($pdo, $b['room_id'], $b['check_in'], $b['check_out'], $id))
    $errors[] = 'Cannot re-activate: the room has been booked by someone else for these dates.';
  if (!$errors) {
    try {
      $pdo->prepare("UPDATE bookings SET status=? WHERE booking_id=?")->execute([$new, $id]);
      sync_room($pdo, $b['room_id']); flash("Booking #$id marked as $new."); redirect('index.php');
    } catch (PDOException $e) { $errors[] = 'Could not update the booking.'; }
  }
}
include __DIR__.'/../includes/header.php'; ?>
<section class="card narrow"><h2>Booking #<?= $id ?></h2>
<p class="empty"><?= e($b['name']) ?> &middot; Room <?= e($b['room_number']) ?> (<?= e($b['room_type']) ?>)<br><?= e($b['check_in']) ?> to <?= e($b['check_out']) ?></p>
<?php foreach ($errors as $er): ?><div class="alert error"><?= e($er) ?></div><?php endforeach; ?>
<form method="post"><input type="hidden" name="id" value="<?= $id ?>">
  <label>Status<select name="status"><?php foreach (BOOKING_STATUSES as $st): ?><option <?= $b['status']===$st?'selected':'' ?>><?= $st ?></option><?php endforeach; ?></select></label>
  <div class="row"><button class="btn">Update</button><a class="btn ghost" href="index.php">Back</a></div>
</form></section>
<?php include __DIR__.'/../includes/footer.php'; ?>
