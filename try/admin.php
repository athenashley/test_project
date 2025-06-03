<?php
error_reporting(E_ALL);//snasabi nia ung lahat ng error sa php
ini_set('display_errors', 1);//pag 1 papakita ung error pag 0 nde
session_start();
include('connect.php'); 

if (!isset($_SESSION['userid'])) {//ireredirect ka sa login pag di ka admin
    header("Location: login.php");
    exit();
}

//dito magaalert pag di ka admin na di mo sya maacess lol
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<h2>Access Denied</h2><p>You are not authorized to view this page.</p>";
    exit();
}

// inuupdate ung user
if (isset($_POST['update_user'])) {
    $id = (int)$_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET username=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $email, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "User updated successfully!";
    header("Location: admin.php?tab=users");
    exit();
}

// dito pag magbubura ng user
if (isset($_GET['delete_user'])) {
    $id = (int)$_GET['delete_user'];
    if (!$conn->query("DELETE FROM users WHERE id=$id")) {
        die("Delete user failed: " . $conn->error);
    }
    $_SESSION['success'] = "User deleted successfully!";
    header("Location: admin.php?tab=users");
    exit();
}

// update naman ng reservation
if (isset($_POST['update_reservation'])) {
    $id = (int)$_POST['reservation_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $room_type = $_POST['room_type'];
    $guests = (int)$_POST['guests'];
    $total_price = floatval($_POST['total_price']); 

    $stmt = $conn->prepare("UPDATE reservations SET full_name=?, email=?, check_in=?, check_out=?, room_type=?, guests=?, total_price=? WHERE id=?");
    $stmt->bind_param("sssssdii", $full_name, $email, $check_in, $check_out, $room_type, $guests, $total_price, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "Reservation updated successfully!";
    header("Location: admin.php?tab=reservations");
    exit();
}

// magdedelete ng reservation pag tapos na silang magstay ganon
if (isset($_GET['delete_reservation'])) {
    $id = (int)$_GET['delete_reservation'];
    if (!$conn->query("DELETE FROM reservations WHERE id=$id")) {
        die("Delete reservation failed: " . $conn->error);
    }
    $_SESSION['success'] = "Reservation deleted successfully!";
    header("Location: admin.php?tab=reservations");
    exit();
}

// eto sa room inventory,kung ilang room available natin ganon
if (isset($_POST['update_inventory'])) {
    $room_type = $_POST['room_type'];
    $total_rooms = (int)$_POST['total_rooms'];

    $stmt = $conn->prepare("UPDATE rooms_inventory SET total_rooms=? WHERE room_type=?");
    $stmt->bind_param("is", $total_rooms, $room_type);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = "Inventory updated successfully!";
    header("Location: admin.php?tab=inventory");
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-end mb-4">
        <a href="?logout=1" class="btn btn-warning">Logout</a>
    </div>

<!--Nagpapakita ng success message kapag may nangyaring successful na action, tulad ng pag-save, pag-update, o pag-delete ng data.-->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

<!--e2 ung nav tabs kung san makikita ung user,reservation and room number-->
    <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">Users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reservations-tab" data-bs-toggle="tab" data-bs-target="#reservations" type="button" role="tab">Reservations</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="inventory-tab" data-bs-toggle="tab" data-bs-target="#inventory" type="button" role="tab">Room Inventory</button>
        </li>
    </ul>

    <div class="tab-content" id="adminTabsContent">
        <!-- Users-->
        <div class="tab-pane fade" id="users" role="tabpanel">
            <h2 class="mb-4">User Accounts</h2>
            <table class="table table-bordered bg-white shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = $conn->query("SELECT * FROM users ORDER BY id DESC");//latest to oldest
                    if (!$users) {
                        echo "<tr><td colspan='4' class='text-danger'>User query error: " . $conn->error . "</td></tr>";
                    } elseif ($users->num_rows == 0) {
                        echo "<tr><td colspan='4' class='text-center'>No users found.</td></tr>";
                    } else {
                        while ($row = $users->fetch_assoc()):
                    ?>
                    <tr>
                        <form method="POST">
                            <td><input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>" class="form-control"></td>
                            <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" class="form-control"></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <input type="hidden" name="user_id" value="<?= (int)$row['id'] ?>">
                                <button type="submit" name="update_user" class="btn btn-success btn-sm">Save</button>
                                <a href="?delete_user=<?= (int)$row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this user?')">Delete</a>
                            </td>
                        </form>
                    </tr>
                    <?php endwhile; } ?>
                </tbody>
            </table>
        </div>

        <!-- Reservations-->
        <div class="tab-pane fade" id="reservations" role="tabpanel">
            <h2 class="mb-4">Reservations</h2>
            <table class="table table-bordered bg-white shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Room Type</th>
                        <th>Guests</th>
                        <th>Total Price</th> 
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $reservations = $conn->query("SELECT * FROM reservations ORDER BY id DESC");//latest to oldest
                    if (!$reservations) {
                        echo "<tr><td colspan='8' class='text-danger'>Reservation query error: " . $conn->error . "</td></tr>";
                    } elseif ($reservations->num_rows == 0) {
                        echo "<tr><td colspan='8' class='text-center'>No reservations found.</td></tr>";
                    } else {
                        while ($row = $reservations->fetch_assoc()):
                    ?>
                    <tr>
                        <form method="POST">
                            <td><input type="text" name="full_name" value="<?= htmlspecialchars($row['full_name']) ?>" class="form-control" required></td>
                            <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" class="form-control" required></td>
                            <td><input type="date" name="check_in" value="<?= htmlspecialchars($row['check_in']) ?>" class="form-control" required></td>
                            <td><input type="date" name="check_out" value="<?= htmlspecialchars($row['check_out']) ?>" class="form-control" required></td>
                            <td>
                                <select name="room_type" class="form-select" required>
                                    <option <?= ($row['room_type'] == 'Standard') ? 'selected' : '' ?>>Standard</option>
                                    <option <?= ($row['room_type'] == 'Deluxe') ? 'selected' : '' ?>>Deluxe</option>
                                    <option <?= ($row['room_type'] == 'Suite') ? 'selected' : '' ?>>Suite</option>
                                </select>
                            </td>
                            <td><input type="number" name="guests" value="<?= (int)$row['guests'] ?>" class="form-control" min="1" required></td>
                            <td><input type="number" step="0.01" name="total_price" value="<?= htmlspecialchars($row['total_price']) ?>" class="form-control" min="0" required></td>
                            <td>
                                <input type="hidden" name="reservation_id" value="<?= (int)$row['id'] ?>">
                                <button type="submit" name="update_reservation" class="btn btn-success btn-sm">Save</button>
                                <a href="?delete_reservation=<?= (int)$row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this reservation?')">Delete</a>
                            </td>
                        </form>
                    </tr>
                    <?php endwhile; } ?>
                </tbody>
            </table>
        </div>

        <!-- Room Inventory-->
        <div class="tab-pane fade" id="inventory" role="tabpanel">
            <h2 class="mb-4">Room Inventory</h2>
            <table class="table table-bordered bg-white shadow w-50">
                <thead class="table-dark">
                    <tr>
                        <th>Room Type</th>
                        <th>Total Rooms</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $inventory = $conn->query("SELECT * FROM rooms_inventory");
                    if (!$inventory) {
                        echo "<tr><td colspan='3' class='text-danger'>Inventory query error: " . $conn->error . "</td></tr>";
                    } elseif ($inventory->num_rows == 0) {
                        echo "<tr><td colspan='3' class='text-center'>No inventory data found.</td></tr>";
                    } else {
                        while ($row = $inventory->fetch_assoc()):
                    ?>
                    <tr>
                        <form method="POST">
                            <td><?= htmlspecialchars($row['room_type']) ?></td>
                            <td>
                                <input type="number" name="total_rooms" value="<?= (int)$row['total_rooms'] ?>" min="0" class="form-control" required>
                            </td>
                            <td>
                                <input type="hidden" name="room_type" value="<?= htmlspecialchars($row['room_type']) ?>">
                                <button type="submit" name="update_inventory" class="btn btn-success btn-sm">Save</button>
                            </td>
                        </form>
                    </tr>
                    <?php endwhile; } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const urlParams = new URLSearchParams(window.location.search);//Kinukuha nito ang query parameters mula sa URL 
    const tab = urlParams.get('tab');//Kinukuha ang value ng tab parameter
 //kapag na-reload ang page, automatic na naka-open ulit ang Reservations tab imbes na bumalik sa Users tab.
    if (tab) {
        const trigger = document.querySelector(`button[data-bs-target="#${tab}"]`);
        if (trigger) new bootstrap.Tab(trigger).show();
    } else {
        new bootstrap.Tab(document.querySelector('#users-tab')).show();
    }
</script>
</body>
</html>