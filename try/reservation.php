<?php

include 'connect.php';

$room_capacity = [//eto ung number ng room natin tig 5 ah
    'Standard' => 5,
    'Deluxe' => 5,
    'Suite' => 5
];

// room prices per night
$room_prices = [
    'Standard' => 1500,
    'Deluxe' => 2800,
    'Suite' => 5500
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = $_POST['email'] ?? '';
    $checkin = $_POST['checkin'] ?? '';
    $checkout = $_POST['checkout'] ?? '';
    $room_type = $_POST['room-type'] ?? '';
    $guests = intval($_POST['guests'] ?? 1);

    // validate full name (dapat 2 words or more, nickname not allowed)
    if (str_word_count($name) < 2) {
        session_start();
        $_SESSION['message'] = "Please enter your full name. Nicknames are not allowed.";
        $_SESSION['type'] = "warning";
        header("Location: index.php");
        exit();
    }

    // icalculate number per night
    $checkinDate = new DateTime($checkin);
    $checkoutDate = new DateTime($checkout);
    $interval = $checkinDate->diff($checkoutDate);
    $nights = $interval->days;

    // icalculate total price depende the room and price
    $price_per_night = $room_prices[$room_type] ?? 0;
    $total_price = $price_per_night * $nights;

    // bibilangin ung current na reservation sa room na yon at ung date
    $sql = "SELECT COUNT(*) as count FROM reservations 
            WHERE room_type = ? 
            AND (check_in < ? AND check_out > ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $room_type, $checkout, $checkin); // pag may nagoverlap na date ichecheck niya
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $current_reservations = $result['count'] ?? 0;
    $stmt->close();

    $max_capacity = $room_capacity[$room_type] ?? 0;

    if ($current_reservations >= $max_capacity) {//dito magsasabi pag fully booked na halata naman gege
        session_start();
        $_SESSION['message'] = "Sorry, all $room_type rooms are fully booked for the selected dates.";
        $_SESSION['type'] = "danger";
        header("Location: index.php");
        exit();
    }

    // dito na magproproceed ng pagrereserve
    $sql = "INSERT INTO reservations (full_name, email, check_in, check_out, room_type, guests, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssid', $name, $email, $checkin, $checkout, $room_type, $guests, $total_price);

    if ($stmt->execute()) {//eto basic na toh pag successful ung reserve mag aalert ganon na successful chuchu
        echo "<!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8' />
            <meta http-equiv='refresh' content='10;url=index.php' />
            <title>Reservation Successful</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css' rel='stylesheet'>
        </head>
        <body>
        <div class='container d-flex justify-content-center align-items-center vh-100'>
            <div class='alert text-center' style='background-color: #7b5c38; color: white;'>
                <h4 class='alert-heading'>Reservation Successful!</h4>
                <p>Thank you, <strong>" . htmlspecialchars($name) . "</strong>. Your reservation has been booked.</p>
                <p><strong>Total Price: ₱" . number_format($total_price, 2) . "</strong> for {$nights} night(s).</p>
                <hr>
                <p>You will be redirected to the homepage in 10 seconds.</p>
                <a href='index.php' class='btn btn-light'>Go Now</a>
            </div>
        </div>
        </body>
        </html>";
    } else {
        echo "Reservation failed: " . $stmt->error;//syempre pag failed
    }

    $stmt->close();
}
$conn->close();
?>