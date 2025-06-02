<?php
header('Content-Type: application/json');

// database connection details
$host = "localhost";
$user = "root";
$pass = "";
$db = "hotel_db";

// gumagawa ng connection sa database
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$bookedDates = []; // eto yung array para sa lahat ng naka-reserve na dates

// kukunin ang lahat ng check-in at check-out dates mula sa reservations table
$sql = "SELECT check_in, check_out FROM reservations";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $start = new DateTime($row['check_in']);
        $end = (new DateTime($row['check_out']))->modify('+1 day'); // Include checkout day

        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);

        // I-add lahat ng dates sa bookedDates array
        foreach ($period as $date) {
            $bookedDates[] = $date->format('Y-m-d');
        }
    }
}

$conn->close();

echo json_encode(array_values(array_unique($bookedDates)));
?>