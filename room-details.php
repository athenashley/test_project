<?php
// listahan ng lahat ng available room types at kanilang details
$rooms = [
    'standard' => [
        'title' => 'Standard Room',
        'image' => 'pics/12.jpg',
        'description' => 'A cozy and budget-friendly sanctuary designed for travelers seeking comfort and convenience without breaking the bank. The Standard Room features tasteful decor, modern furnishings, and all the essential amenities to ensure a restful stay. Whether you’re here for business or leisure, enjoy complimentary high-speed Wi-Fi, a flat-screen TV, and a private bathroom equipped with fresh towels and toiletries. Perfect for solo adventurers or couples, this room offers a warm and welcoming atmosphere that makes every guest feel right at home.',
        'guests' => '2 Guests',
        'amenities' => ['Queen size Bed', 'Free Wi-Fi', 'TV', 'Air Conditioning', 'Private Bathroom'],
        'price' => '₱1,500 / night',
        'inclusions' => ['Free Breakfast', 'Welcome Drink', 'Free Cancellation'],
        'checkin' => '2:00 PM',
        'checkout' => '12:00 PM',
        'policies' => ['No Smoking', 'No Pets Allowed'],
        'gallery' => ['pics/8.jpg', 'pics/9.jpg', 'pics/10.jpg']
    ],
    'deluxe' => [
        'title' => 'Deluxe Room',
        'image' => 'pics/1.webp',
        'description' => 'Step into spacious elegance with our Deluxe Room, thoughtfully designed for travelers who appreciate both style and comfort. Featuring plush bedding, a well-appointed workspace, and refined decor, the Deluxe Room is ideal for couples, small families, or business travelers. Relax with modern conveniences such as a Smart TV, mini bar, and a luxurious bathtub for unwinding after a busy day. With ample space and premium amenities, this room strikes the perfect balance between functionality and indulgence, ensuring your stay is both productive and restful.',
        'guests' => '3-4 Guests',
        'amenities' => [
            '2 Queen size Bed','Free Wi-Fi', 'Smart TV', 'Mini Bar', 'Air Conditioning', 'Bathtub',
            'Sitting area', 'Bathroom amenities', 'Spacious bathroom with bath tub', 'Intelligent toilet bowl',
            'Hairdryer','100% cotton bed linens','Microfiber satin bathrobes', 'Walk-in closet', 'Automated private bar',
            'In-room safety deposit box', 'Weighing scale', 'Coffee and tea making facilities','Welcome and turndown amenities'
        ],
        'price' => '₱2,800 / night',
        'inclusions' => ['Free Breakfast', 'Complimentary Mini Bar', 'Late Checkout', 'Free Cancellation'],
        'checkin' => '2:00 PM',
        'checkout' => '12:00 PM',
        'policies' => ['No Smoking', 'No Pets Allowed', 'Quiet Hours after 10 PM'],
        'gallery' => ['pics/2.webp', 'pics/3.webp', 'pics/4.webp']
    ],
    'suite' => [
        'title' => 'Suite Room',
        'image' => 'pics/5.webp',
        'description' => 'Experience the ultimate in luxury with our Suite Room, where sophistication meets comfort in an expansive living space. This premium accommodation offers a separate living area, a private balcony with stunning views, and exclusive access to top-tier amenities such as a Jacuzzi and a private butler upon request. Designed for discerning guests and special occasions, the Suite boasts elegant furnishings, advanced entertainment systems, and meticulous attention to detail that guarantees a memorable stay. Whether for a romantic getaway or an important business visit, the Suite elevates your experience to unparalleled heights.',
        'guests' => '5-10 Guests',
        'amenities' => [
            'Masters Bedroom','Room with 2 Queen size Bed', 'Free Wi-Fi', 'Smart TV', 'Jacuzzi', 'Living Room', 'Balcony View', 'Air Conditioning',
            'Living room', 'Dining area (10-seater)', 'Three (3) spacious bathrooms', 'Powder room',
            'Massage room', 'Pantry (with microwave, refrigerator, induction cooker)',
            'Nanny room', 'Bar counter with stool','Walk-in closet', 'Jet tub', 'In-room safe', 'Feather bed components',
            'Motorized curtains','Intelligent toilet bowl',  'Coffee making facilities','Weighing scale', 'Hairdryer', 'Bathroom amenities', 'Welcome and turndown amenities'
        ],
        'price' => '₱5,500 / night',
        'inclusions' => ['Free Breakfast', 'Welcome Fruit Basket', 'Private Butler on Request', 'Free Cancellation'],
        'checkin' => '2:00 PM',
        'checkout' => '12:00 PM',
        'policies' => ['No Smoking', 'No Pets Allowed', 'Quiet Hours after 9 PM'],
        'gallery' => ['pics/6.webp', 'pics/7.webp', 'pics/8.webp', 'pics/9.webp', 'pics/12.webp', 'pics/13.webp']
    ]
];

$roomKey = $_GET['room'] ?? '';
$room = $rooms[$roomKey] ?? null;

// kapag walang nahanap na room, mag-display ng error
if (!$room) {
    echo "<h2 class='text-center mt-5'>Room not found.</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($room['title']) ?> - Room Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    body {
        font-family: 'Montserrat', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9f5f0;
        color: #5a4a3a;
        line-height: 1.7;
    }
        .navbar {
      background-color: #7b5c38;
    }
    .navbar-brand, .nav-link, .btn-outline-light {
      color: white !important;
    }

    .btn-outline-light {
        border-width: 2px;
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .btn-outline-light:hover {
        background-color: white;
        color: #7b5c38 !important;
        box-shadow: 0 4px 15px rgba(123, 92, 56, 0.3);
        transform: translateY(-2px);
    }
    .room-banner {
        height: 70vh;
        min-height: 600px;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        position: relative;
        margin-top: 56px;
    }
    .room-banner::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100px;
        background: linear-gradient(to bottom, transparent, #f9f5f0);
        z-index: 1;
    }
    h2, h5 {
        color: #7b5c38;
        font-weight: 700;
    }
    h2 {
        font-size: 2.8rem;
        margin-bottom: 1.5rem;
        position: relative;
        display: inline-block;
    }
    h2::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 0;
        width: 80px;
        height: 4px;
        background: #7b5c38;
        border-radius: 2px;
    }
    .lead {
        font-size: 1.25rem;
        font-weight: 400;
        max-width: 900px;
        margin-bottom: 3rem;
        color: #5a4a3a;
    }
    .specs-box {
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(123, 92, 56, 0.08);
        margin-bottom: 50px;
        font-size: 1.05rem;
        border: 1px solid rgba(123, 92, 56, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .specs-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(123, 92, 56, 0.12);
    }
    .specs-box p {
        margin-bottom: 1rem;
        font-weight: 500;
    }
    .specs-box ul {
        margin-top: 0.8rem;
        margin-bottom: 2rem;
    }
    ul.two-columns {
        display: flex;
        flex-wrap: wrap;
        list-style: none;
        padding-left: 0;
        margin-bottom: 1.5rem;
    }
    ul.two-columns li {
        width: 50%;
        box-sizing: border-box;
        padding-left: 1.5rem;
        margin-bottom: 0.8rem;
        position: relative;
        font-weight: 500;
    }
    ul.two-columns li::before {
        content: '✓';
        color: #7b5c38;
        position: absolute;
        left: 0;
        font-weight: bold;
    }
    footer {
        background-color: #7b5c38;
        color: white;
        text-align: center;
        padding: 30px 0 20px;
        position: relative;
        margin-top: 80px;
    }
    footer::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 10px;
        background: linear-gradient(to right, #c8b6a6, #7b5c38, #c8b6a6);
    }
    .gallery-img {
        height: 250px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 8px rgba(123, 92, 56, 0.1);
    }
    .gallery-img:hover {
        transform: scale(1.03);
        box-shadow: 0 8px 20px rgba(123, 92, 56, 0.15);
    }
    .specs-box h5 {
        font-size: 1.3rem;
        margin-bottom: 1.2rem;
        position: relative;
        padding-bottom: 8px;
    }
    .specs-box h5::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 50px;
        height: 2px;
        background: #7b5c38;
    }
    .price-highlight {
        font-size: 2.5rem;
        font-weight: 800;
        color: #7b5c38;
        display: inline-block;
        margin-bottom: 1.5rem;
        letter-spacing: 0.5px;
    }
    .modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
    .btn-close {
        filter: invert(1);
        opacity: 0.8;
    }
    @media (max-width: 992px) {
        .room-banner {
            height: 50vh;
            min-height: 400px;
            background-attachment: scroll;
        }
        h2 {
            font-size: 2.2rem;
        }
        .lead {
            font-size: 1.1rem;
        }
    }
    @media (max-width: 768px) {
        .specs-box {
            padding: 30px;
        }
        ul.two-columns li {
            width: 100%;
        }
        .price-highlight {
            font-size: 2rem;
        }
    }
    @media (max-width: 576px) {
        .room-banner {
            height: 40vh;
            min-height: 300px;
        }
        h2 {
            font-size: 1.8rem;
        }
        .specs-box {
            padding: 20px 15px;
        }
    }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">MAISON VALENTE</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDetails" aria-controls="navbarNavDetails" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDetails">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#rooms">Rooms</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#reservation">Reservation</a></li>
        <a class="btn" style="color: white;" href="logout.php">Logout</a>
      </ul>
    </div>
  </div>
</nav>

<div class="room-banner" style="background-image: url('<?= htmlspecialchars($room['image']) ?>');"></div>

<div class="container my-5">
  <h2><?= htmlspecialchars($room['title']) ?></h2>
  <p class="lead"><?= htmlspecialchars($room['description']) ?></p>

  <div class="specs-box">
    <p><strong>Price:</strong> <span class="price-highlight"><?= htmlspecialchars($room['price']) ?></span></p>
    <p><strong>Guests:</strong> <?= htmlspecialchars($room['guests']) ?></p>

    <h5>Amenities:</h5>
    <ul class="two-columns">
      <?php foreach ($room['amenities'] as $amenity): ?>
        <li><?= htmlspecialchars($amenity) ?></li>
      <?php endforeach; ?>
    </ul>

    <h5>Inclusions:</h5>
    <ul class="two-columns">
      <?php foreach ($room['inclusions'] as $inclusion): ?>
        <li><?= htmlspecialchars($inclusion) ?></li>
      <?php endforeach; ?>
    </ul>

    <h5>Check-in & Check-out:</h5>
    <ul>
      <li><strong>Check-in:</strong> <?= htmlspecialchars($room['checkin']) ?></li>
      <li><strong>Check-out:</strong> <?= htmlspecialchars($room['checkout']) ?></li>
    </ul>

    <h5>Room Policies:</h5>
    <ul class="two-columns">
      <?php foreach ($room['policies'] as $policy): ?>
        <li><?= htmlspecialchars($policy) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>

  <h4 class="mb-3">Room Gallery</h4>
  <div class="row g-3 mb-4">
    <?php foreach ($room['gallery'] as $index => $img): ?>
      <div class="col-md-4">
        <img
          src="<?= htmlspecialchars($img) ?>"
          alt="Gallery Image"
          class="img-fluid gallery-img"
          onclick="openModal(<?= $index ?>)"
          style="cursor:pointer;"
        />
      </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center">
    <a href="index.php?room=<?= urlencode($roomKey) ?>#reservation" class="btn btn-outline-light" style="background-color: #7b5c38;">Book Now</a>
  </div>
</div>

<!-- Modal for gallery images -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-light">
      <div class="modal-body p-0 position-relative">
        <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>

        <button id="prevImage" class="position-absolute top-50 start-0 translate-middle-y ms-2 btn btn-secondary" onclick="prevImage()"><</button>

        <img id="modalImage" src="" alt="Room Gallery" class="w-100 rounded" style="max-height: 600px; object-fit: contain;">

        <button id="nextImage" class="position-absolute top-50 end-0 translate-middle-y me-2 btn btn-secondary" onclick="nextImage()">></button>
      </div>
    </div>
  </div>
</div>

<footer class="mt-5 py-3">
  <p class="mb-0">© 2025 Maison Valente Hotel. All rights reserved.</p>
</footer>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const galleryImages = <?= json_encode($room['gallery']) ?>;
  let currentIndex = 0;

  const modalElement = document.getElementById('galleryModal');
  const modal = new bootstrap.Modal(modalElement);
  const modalImage = document.getElementById('modalImage');

  function openModal(index) {
    currentIndex = index;
    modalImage.src = galleryImages[currentIndex];
    modal.show();
  }

  function prevImage() {
    currentIndex = (currentIndex === 0) ? galleryImages.length - 1 : currentIndex - 1;
    modalImage.src = galleryImages[currentIndex];
  }

  function nextImage() {
    currentIndex = (currentIndex === galleryImages.length - 1) ? 0 : currentIndex + 1;
    modalImage.src = galleryImages[currentIndex];
  }

  //pagsa keyboard arrow
  document.addEventListener('keydown', function(event) {
    if (!modalElement.classList.contains('show')) return;

    if (event.key === 'ArrowLeft') {
      event.preventDefault();
      prevImage();
    } else if (event.key === 'ArrowRight') {
      event.preventDefault();
      nextImage();
    }
  });
</script>

</body>
</html>