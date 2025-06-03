<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'connect.php';

header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$inventory = [];
$booked = [];
$available = [];

// Query total rooms per type
$inventoryQuery = $conn->query("SELECT room_type, total_rooms FROM rooms_inventory");
if (!$inventoryQuery) {
    echo json_encode(['error' => 'Failed to fetch room inventory']);
    exit;
}

while ($row = $inventoryQuery->fetch_assoc()) {
    $inventory[$row['room_type']] = (int)$row['total_rooms'];
}

// nagququery ng booked count
$bookedQuery = $conn->query("SELECT room_type, COUNT(*) as booked FROM reservations GROUP BY room_type");
if (!$bookedQuery) {
    echo json_encode(['error' => 'Failed to fetch booked rooms']);
    exit;
}

while ($row = $bookedQuery->fetch_assoc()) {
    $booked[$row['room_type']] = (int)$row['booked'];
}

// nicacalculate available rooms
foreach ($inventory as $type => $total) {
    $bookedCount = $booked[$type] ?? 0;
    $available[$type] = max(0, $total - $bookedCount);
}

echo json_encode($available);