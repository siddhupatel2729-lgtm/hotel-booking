<?php require __DIR__.'/../config.php'; $root = '../'; $section = 'rooms'; $title = 'Rooms';
$rows = $pdo->query("SELECT * FROM rooms ORDER BY room_number")->fetchAll();
include __DIR__.'/../includes/header.php'; ?>
<section class="card"><div class="head"><h2>All Rooms (<?= count($rows) ?>)</h2><a class="btn" href="add.php">Add Room</a></div>
<?php if (!$rows): ?><p class="empty">No rooms yet.</p><?php else: ?><div class="scroll"><table>
<thead><tr><th>ID</th><th>Room No.</th><th>Type</th><th>Price / night</th><th>Status</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($rows as $r): ?><tr><td><?= $r['room_id'] ?></td><td><?= e($r['room_number']) ?></td><td><?= e($r['room_type']) ?></td><td>&#8377;<?= number_format($r['price'], 2) ?></td>
<td><span class="badge <?= strtolower(e($r['status'])) ?>"><?= e($r['status']) ?></span></td>
<td class="act"><a class="btn sm ghost" href="edit.php?id=<?= $r['room_id'] ?>">Edit</a>
<form method="post" action="delete.php" data-confirm="Delete room <?= e($r['room_number']) ?>?"><input type="hidden" name="id" value="<?= $r['room_id'] ?>"><button class="btn sm danger">Delete</button></form></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php include __DIR__.'/../includes/footer.php'; ?>
