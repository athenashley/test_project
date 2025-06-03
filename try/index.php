<?php
session_start();
include 'connect.php';

// para i-standardize ang pag-display ng room names (or any input) sa tamang format, kahit ano pa ang case na ibinigay sa URL.
$selectedRoom = $_GET['room'] ?? '';
$selectedRoomFormatted = ucfirst(strtolower($selectedRoom));

// if walang naka-login na user, ire-redirect ka sa login page
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hotel Booking</title>

   <!-- Bootstrap CSS para sa design -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="stylein.css" />
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" /><!--nagbibigay ng style at niloload  pra sa datepicker-->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
</head>
<body class="dashboard-page">
  
<?php if (isset($_SESSION['message'])): ?>
  <!-- eto is para ipakita yung mga flash message gaya ng success o error -->
  <div class="alert alert-<?= $_SESSION['type'] ?? 'info' ?> alert-dismissible fade show mb-0 text-center" role="alert">
    <?= $_SESSION['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['message'], $_SESSION['type']); ?>
<?php endif; ?>

<!-- Navbar para sa navigation ng site -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <a class="navbar-brand" href="#">MAISON VALENTE</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#rooms">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="#reservation">Reservation</a></li>
        <li class="nav-item">
          <a class="btn" style="color: white;" href="logout.php">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="hero d-flex flex-column justify-content-center align-items-center text-white text-center">
  <h1 class="display-4 fw-bold">WELCOME TO MAISON VALENTE</h1>
  <p class="lead">Experience comfort and luxury</p>
</div>  

<section id="rooms" class="container my-5">
  <h2 class="text-center mb-4">Our Rooms</h2>
  <div class="row">
    <!-- Standard Room -->
    <div class="col-md-4 mb-4">
      <div class="card text-white bg-dark room-card" style="background-image: url('pics/4.jpg');">
        <div class="card-body bg-dark bg-opacity-75">
          <h5 class="card-title">Standard Room</h5>
          <p class="card-text">Comfortable and affordable for short stays.</p>
          <a href="room-details.php?room=standard" class="btn btn-light mt-3">View Details</a>
        </div>
      </div>
    </div>
    <!-- Deluxe Room -->
    <div class="col-md-4 mb-4">
      <div class="card text-white bg-dark room-card" style="background-image: url('pics/5.jpg');">
        <div class="card-body bg-dark bg-opacity-75">
          <h5 class="card-title">Deluxe Room</h5>
          <p class="card-text">A perfect blend of luxury and elegance.</p>
          <a href="room-details.php?room=deluxe" class="btn btn-light mt-3">View Details</a>
        </div>
      </div>
    </div>
    <!-- Suite Room -->
    <div class="col-md-4 mb-4">
      <div class="card text-white bg-dark room-card" style="background-image: url('pics/6.jpg');">
        <div class="card-body bg-dark bg-opacity-75">
          <h5 class="card-title">Suite Room</h5>
          <p class="card-text">Spacious room with premium amenities for a luxurious stay.</p>
          <a href="room-details.php?room=suite" class="btn btn-light mt-3">View Details</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!--Reservation-->
<section id="reservation" class="bg-light py-5">
  <div class="container">
    <h2 class="text-center mb-4">Book Your Stay</h2>
    <form action="reservation.php" method="POST">
      <div class="row g-3">
        <div class="col-md-6">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" id="name" name="name" class="form-control" required />
        </div>
        <div class="col-md-6">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" id="email" name="email" class="form-control" required />
        </div>
        <div class="col-md-6">
          <label for="checkin" class="form-label">Check-in Date</label>
          <input type="text" id="checkin" name="checkin" class="form-control" required />
        </div>
        <div class="col-md-6">
          <label for="checkout" class="form-label">Check-out Date</label>
          <input type="text" id="checkout" name="checkout" class="form-control" required />
        </div>
        <div class="col-md-6">
          <label for="room-type" class="form-label">Room Type</label>
          <select id="room-type" name="room-type" class="form-select"></select>
          <small id="room-availability" class="text-muted ms-1"></small>
        </div>
        <div class="col-md-6">
          <label for="guests" class="form-label">Number of Guests</label>
          <input type="number" id="guests" name="guests" class="form-control" min="1" max="10" required />
        </div>
      </div>
      <div class="text-center mt-3">
        <h5>Total Price: ₱<span id="total-price">0.00</span></h5>
      </div>

      <div class="text-center mt-4">
        <button type="submit" class="btn" style="background-color: #7b5c38; color: white;">Reserve Now</button>
      </div>
    </form>
  </div>
</section>

<script>
  let roomData = {};
  let bookedDates = [];

  // para hindi na mapili yung date na meron nng nag booked
  function disableBookedDates(date) {
    const formatted = $.datepicker.formatDate("yy-mm-dd", date);
    return [!bookedDates.includes(formatted)];
  }

  // eto yung naglo-load ng mga date na fully booked mula sa server
  function loadBookedDates() {
    $.ajax({
      url: "get-booked-dates.php",//icacall para maghingi ng request pero no need magreload ng page
      method: "GET",
      dataType: "json",
      success: function (dates) {
        bookedDates = dates;

        $("#checkin, #checkout").datepicker("destroy").datepicker({//madidisable nya padn napili na ung date
          dateFormat: "yy-mm-dd",
          minDate: 0,
          beforeShowDay: disableBookedDates
        });
      },
      error: function () {
        console.error("Failed to load booked dates.");
      }
    });
  }

  // dito nmn ung ilan ung guest na pede para sa mga room
  $(document).ready(function () {
    const roomSelect = document.getElementById("room-type");
    const guestsInput = document.getElementById("guests");

    const roomLimits = {
      "Standard": { min: 1, max: 2 },
      "Deluxe": { min: 1, max: 4 },
      "Suite": { min: 1, max: 10 }
    };

    // function para i-update ang guest input limit base sa napiling room type
    function updateGuestLimit() {
      const selectedRoom = roomSelect.value;
      const limits = roomLimits[selectedRoom];

      guestsInput.min = limits.min; //min guests
      guestsInput.max = limits.max; //max guests

      if (guestsInput.value < limits.min || guestsInput.value > limits.max) {//automatic magdidisplay ung browser ng alert pg mali e2
        guestsInput.value = limits.min;
      }
    }

    // kapag may binagong room type, eto yung naga-update ng availability display at guest limits
    roomSelect.addEventListener("change", function () {
      const selectedRoom = this.value;
      const availability = roomData[selectedRoom];
      const roomAvailability = document.getElementById("room-availability");
      if (availability !== undefined) {
        roomAvailability.textContent = `${selectedRoom} room(s) available: ${availability}`;
      } else {
        roomAvailability.textContent = "Please select a room type.";
      }
      updateGuestLimit();
    });

    // function para kunin ang availability ng mga room mula sa server
    function loadRoomAvailability() {
      $.ajax({
        url: "get-room-availability.php",//icacall para maghingi ng request pero no need magreload ng page
        method: "GET",
        dataType: "json",
        success: function (response) {
          console.log("Availability data received:", response);
          roomData = response;
          roomSelect.innerHTML = "";

          const roomOrder = ["Standard", "Deluxe", "Suite"];
          roomOrder.forEach(room => {
            if (roomData[room] !== undefined) {
              const option = document.createElement("option");
              option.value = room;

              if (roomData[room] === 0) {//madidisable ung room pag puno na
                option.disabled = true;
                option.textContent = room + " (Fully Booked)";
              } else {
                option.textContent = room;
              }

              roomSelect.appendChild(option);
            }
          });

          // auto select ung first available room
          let selected = "<?php echo addslashes($selectedRoomFormatted); ?>";
          if (selected && roomData[selected] !== undefined && roomData[selected] !== 0) {
            roomSelect.value = selected;
          } else {
            roomSelect.selectedIndex = 0;
          }

          roomSelect.dispatchEvent(new Event("change"));
        },
        error: function (xhr, status, error) {
          console.error("Failed to fetch room availability:", error);
          roomSelect.innerHTML = "<option disabled>Failed to load rooms</option>";
        }
      });
    }

    loadRoomAvailability();
    loadBookedDates(); // Load disabled dates
  });

  // kapag na-submit ang form, iche-check kung valid ang dates
  //bawal magcheck in ng 5 tas 4 check out baliktad
  //hindi rin magcocompute ng total price pag mali ung date
  $("form").on("submit", function (e) {
    const checkin = new Date($("#checkin").val());
    const checkout = new Date($("#checkout").val());

    if (checkout <= checkin) {
      e.preventDefault();
      alert("Check-out date must be after Check-in date.");
      return false;
    }
  });

  const roomPrices = {
    "Standard": 1500,
    "Deluxe": 2800,
    "Suite": 5500
  };

  function calculateTotalPrice() {
    const roomType = $("#room-type").val();
    const checkinStr = $("#checkin").val();
    const checkoutStr = $("#checkout").val();

    if (!roomType || !checkinStr || !checkoutStr) {
      $("#total-price").text("0.00");
      return;
    }

    const checkinDate = new Date(checkinStr);
    const checkoutDate = new Date(checkoutStr);

    if (checkoutDate <= checkinDate) {
      $("#total-price").text("0.00");
      return;
    }

    const msPerDay = 1000 * 60 * 60 * 24;
    const nights = Math.round((checkoutDate - checkinDate) / msPerDay);

    const pricePerNight = roomPrices[roomType] || 0;
    const totalPrice = pricePerNight * nights;

    // fixed: show with comma
    $("#total-price").text(totalPrice.toLocaleString("en-PH", { minimumFractionDigits: 2 }));
  }

  // Recalculate price on change of room type or dates
  $("#room-type, #checkin, #checkout").on("change", calculateTotalPrice);

  // Also call once on page load (in case of pre-selected values)
  $(document).ready(() => {
    calculateTotalPrice();
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<footer class="mt-5 py-3">
  <p class="mb-0">© 2025 Maison Valente Hotel. All rights reserved.</p>
</footer>
</body>
</html>
