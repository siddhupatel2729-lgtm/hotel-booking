<?php
// ---- Change these only if your MySQL setup is different (XAMPP default shown) ----
const DB_HOST = 'localhost';
const DB_NAME = 'hotel_booking';
const DB_USER = 'root';
const DB_PASS = '';

session_start();
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Cannot connect to the database. Start MySQL in XAMPP, import database.sql and check config.php.");
}

const ROOM_STATUSES = ['Available', 'Booked', 'Maintenance'];
const BOOKING_STATUSES = ['Booked', 'Completed', 'Cancelled'];

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function redirect($u) { header("Location: $u"); exit; }
function flash($msg = null, $type = 'success') {
    if ($msg) { $_SESSION['flash'] = [$msg, $type]; return null; }
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}
function valid_phone($p) { return (bool)preg_match('/^[0-9+\-\s]{7,15}$/', $p); }
function valid_date($d) { $x = DateTime::createFromFormat('Y-m-d', $d); return $x && $x->format('Y-m-d') === $d; }
function post_only() { if ($_SERVER['REQUEST_METHOD'] !== 'POST') { flash('Invalid request.', 'error'); redirect('index.php'); } }

// True if the room already has an active booking that overlaps these dates
function room_overlap($pdo, $room, $in, $out, $exclude = 0) {
    $s = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE room_id=? AND status<>'Cancelled'
        AND booking_id<>? AND check_in<? AND check_out>?");
    $s->execute([$room, $exclude, $out, $in]);
    return $s->fetchColumn() > 0;
}
// Room is 'Booked' while it has a current/upcoming active booking, else 'Available' (Maintenance is never changed)
function sync_room($pdo, $room) {
    $s = $pdo->prepare("SELECT status FROM rooms WHERE room_id=?"); $s->execute([$room]);
    $st = $s->fetchColumn();
    if ($st === false || $st === 'Maintenance') return;
    $c = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE room_id=? AND status='Booked' AND check_out>CURDATE()");
    $c->execute([$room]);
    $pdo->prepare("UPDATE rooms SET status=? WHERE room_id=?")->execute([$c->fetchColumn() ? 'Booked' : 'Available', $room]);
}
