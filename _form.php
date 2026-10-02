<?php require __DIR__.'/../config.php'; $root = '../'; $section = 'rooms';
$r = ['room_number'=>'','room_type'=>'','price'=>'','status'=>'Available']; $errors = [];
if ($id) {
  $s = $pdo->prepare("SELECT * FROM rooms WHERE room_id=?"); $s->execute([$id]); $r = $s->fetch();
  if (!$r) { flash('Room not found.', 'error'); redirect('index.php'); }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $r = array_map('trim', array_intersect_key($_POST, $r)) + $r;
  if (!preg_match('/^[A-Za-z0-9\-]{1,10}$/', $r['room_number'])) $errors[] = 'Room number is required (letters, numbers, dash; max 10 characters).';
  if ($r['room_type'] === '' || strlen($r['room_type']) > 50) $errors[] = 'Room type is required (max 50 characters).';
  if (!is_numeric($r['price']) || $r['price'] <= 0 || $r['price'] > 99999999) $errors[] = 'Price must be a number greater than 0.';
  if (!in_array($r['status'], ROOM_STATUSES, true)) $errors[] = 'Choose a valid status.';
  if (!$errors) {
    $s = $pdo->prepare("SELECT COUNT(*) FROM rooms WHERE room_number=? AND room_id<>?"); $s->execute([$r['room_number'], $id]);
    if ($s->fetchColumn()) $errors[] = 'Room number "'.$r['room_number'].'" already exists.';
  }
  if (!$errors) {
    try {
      $v = [$r['room_number'], $r['room_type'], $r['price'], $r['status']];
      if ($id) { $pdo->prepare("UPDATE rooms SET room_number=?,room_type=?,price=?,status=? WHERE room_id=?")->execute([...$v, $id]); flash('Room updated.'); }
      else { $pdo->prepare("INSERT INTO rooms (room_number,room_type,price,status) VALUES (?,?,?,?)")->execute($v); flash('Room added.'); }
      redirect('index.php');
    } catch (PDOException $e) { $errors[] = 'Could not save the room. Please try again.'; }
  }
}
$title = $id ? 'Edit Room' : 'Add Room';
include __DIR__.'/../includes/header.php'; ?>
<section class="card narrow"><h2><?= $title ?></h2>
<?php foreach ($errors as $er): ?><div class="alert error"><?= e($er) ?></div><?php endforeach; ?>
<form method="post" novalidate data-validate>
  <label>Room number<input name="room_number" required maxlength="10" value="<?= e($r['room_number']) ?>"></label>
  <label>Room type<input name="room_type" required maxlength="50" list="types" value="<?= e($r['room_type']) ?>"><datalist id="types"><option>Single<option>Double<option>Deluxe<option>Suite</datalist></label>
  <label>Price per night<input name="price" type="number" step="0.01" min="0.01" required value="<?= e($r['price']) ?>"></label>
  <label>Status<select name="status"><?php foreach (ROOM_STATUSES as $s): ?><option <?= $r['status']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach; ?></select></label>
  <div class="row"><button class="btn"><?= $id ? 'Update Room' : 'Add Room' ?></button><a class="btn ghost" href="index.php">Back</a></div>
</form></section>
<?php include __DIR__.'/../includes/footer.php'; ?>
