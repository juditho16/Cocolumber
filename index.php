<?php
// Connect to database
include_once(__DIR__ . "/admin/config/db_connection.php");

// Fetch active products / inventory with quantity > 0
$inventory = $conn->query("
  SELECT inventory_id, name, type, size, quantity, unit, status 
  FROM inventory_items 
  WHERE quantity > 0 
  ORDER BY name ASC
");

// Fetch top suppliers for display
$suppliers = $conn->query("
  SELECT name, address, contact_person 
  FROM suppliers 
  ORDER BY created_at DESC 
  LIMIT 4
");

// Fetch latest deliveries
$deliveries = $conn->query("
  SELECT d.quantity, d.unit, d.delivery_date, s.name AS supplier_name, i.name AS item_name
  FROM deliveries d
  JOIN suppliers s ON s.supplier_id = d.supplier_id
  JOIN inventory_items i ON i.inventory_id = d.inventory_id
  ORDER BY d.delivery_date DESC
  LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mahayag Lumberworks | Premium Cut Logs & Lumber</title>

  <!-- Bootstrap / Fonts / Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --primary: #28a745;
      --dark: #1f1f1f;
      --light-bg: #f8f9fa;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff;
      overflow-x: hidden;
    }

    /* 🌲 Hero Section */
    .hero {
      position: relative;
      background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.6)),
                  url('image/bannerwood.jpg') center/cover no-repeat;
      color: white;
      min-height: 90vh;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
      flex-direction: column;
      padding: 1rem;
    }

    .hero h1 {
      font-weight: 700;
      font-size: 3rem;
    }

    .hero p {
      font-size: 1.1rem;
      max-width: 700px;
      margin: 1rem auto;
    }

    .hero .btn {
      background-color: var(--primary);
      color: #fff;
      border-radius: 50px;
      padding: 0.75rem 2rem;
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .hero .btn:hover {
      background-color: #1f7a38;
      transform: translateY(-3px);
    }

    /* 📦 Product Showcase */
    .product-card {
      border: none;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 3px 15px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
      background-color: #fff;
    }

    .product-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .product-card img {
      height: 230px;
      width: 100%;
      object-fit: cover;
      border-bottom: 1px solid #eee;
    }

    .price-tag {
      color: var(--primary);
      font-weight: 600;
    }

    /* Mobile adjustments */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2rem;
      }
      .hero p {
        font-size: 1rem;
      }
      .product-card img {
        height: 180px;
      }
    }

    /* Sections */
    .about, .suppliers, .deliveries, .contact {
      padding: 4rem 1rem;
    }

    .section-title {
      font-weight: 700;
      margin-bottom: 1rem;
    }

    .supplier-card {
      border: none;
      border-radius: 10px;
      background: #ffffff;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
      transition: 0.3s ease;
    }

    .supplier-card:hover {
      transform: translateY(-5px);
    }

    .delivery-card {
      background: white;
      border-radius: 8px;
      padding: 1rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      margin-bottom: 1rem;
    }

    .contact {
      background: linear-gradient(135deg, var(--primary), #1b662e);
      color: white;
      text-align: center;
    }

    footer {
      background: var(--dark);
      color: #fff;
      text-align: center;
      padding: 1.5rem 0;
      font-size: 0.9rem;
    }

    footer a {
      color: var(--primary);
      text-decoration: none;
    }

    footer a:hover {
      text-decoration: underline;
    }

    .status-badge {
      padding: 0.25rem 0.6rem;
      border-radius: 5px;
      font-size: 0.8rem;
      font-weight: 600;
    }

    .status-in-stock { background: #d4edda; color: #155724; }
    .status-low-stock { background: #fff3cd; color: #856404; }
    .status-out-stock { background: #f8d7da; color: #721c24; }
  </style>
</head>

<body>

<!-- 🌲 HERO -->
<section class="hero">
  <h1>Welcome to Mahayag Lumberworks</h1>
  <p>Your trusted source for high-quality lumber and cut logs. Locally sourced, precisely cut, sustainably delivered.</p>
  <a href="#products" class="btn mt-3"><i class="bi bi-box-seam me-2"></i>Browse Products</a>
</section>

<!-- 🪵 PRODUCTS -->
<section id="products" class="container py-5">
  <div class="text-center mb-5">
    <h2 class="section-title">Our Available Lumber</h2>
    <p class="text-muted">Explore a range of premium-grade logs, planks, and lumber ready for purchase or bulk order.</p>
  </div>

  <div class="row g-4">
    <?php if ($inventory->num_rows > 0): ?>
      <?php while($item = $inventory->fetch_assoc()):
        $randomImage = "image/wood" . rand(1, 5) . ".jpg";
        $statusClass = match($item['status']) {
          'In Stock' => 'status-in-stock',
          'Low Stock' => 'status-low-stock',
          'Out of Stock' => 'status-out-stock',
          default => 'status-in-stock'
        };
      ?>
        <div class="col-md-4 col-sm-6">
          <div class="card product-card">
            <img src="<?= $randomImage ?>" alt="<?= htmlspecialchars($item['name']) ?>">
            <div class="card-body">
              <h5><?= htmlspecialchars($item['name']) ?></h5>
              <p class="text-muted small mb-1"><?= htmlspecialchars($item['type']) ?> • <?= htmlspecialchars($item['size']) ?></p>
              <span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($item['status']) ?></span>
              <div class="d-flex justify-content-between align-items-center mt-2">
                <span class="price-tag">₱<?= number_format(rand(800, 2500), 2) ?> / <?= $item['unit'] ?? 'pcs' ?></span>
                <button class="btn btn-sm btn-success rounded-pill px-3"
                  data-bs-toggle="modal"
                  data-bs-target="#viewModal"
                  data-name="<?= htmlspecialchars($item['name']) ?>"
                  data-type="<?= htmlspecialchars($item['type']) ?>"
                  data-size="<?= htmlspecialchars($item['size']) ?>"
                  data-qty="<?= htmlspecialchars($item['quantity']) ?>"
                  data-unit="<?= htmlspecialchars($item['unit']) ?>"
                  data-status="<?= htmlspecialchars($item['status']) ?>"
                  data-price="₱<?= number_format(rand(800, 2500), 2) ?>"
                  data-img="<?= $randomImage ?>">
                  <i class="bi bi-eye me-1"></i>View
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-muted">No products available at the moment.</p>
    <?php endif; ?>
  </div>
</section>

<!-- 🪚 SUPPLIERS -->
<section class="suppliers">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Our Trusted Suppliers</h2>
      <p class="text-muted">We partner only with reliable, sustainable wood providers from across the region.</p>
    </div>
    <div class="row g-4">
      <?php while($s = $suppliers->fetch_assoc()): ?>
        <div class="col-md-3 col-sm-6">
          <div class="card supplier-card text-center p-3">
            <i class="bi bi-truck fs-2 text-success mb-2"></i>
            <h6 class="fw-semibold"><?= htmlspecialchars($s['name']) ?></h6>
            <small class="text-muted"><?= htmlspecialchars($s['contact_person']) ?></small><br>
            <small><?= htmlspecialchars($s['address']) ?></small>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- 🚚 RECENT DELIVERIES -->
<section class="deliveries">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="section-title">Recent Deliveries</h2>
      <p class="text-muted">Freshly delivered logs and lumber from our partner suppliers.</p>
    </div>

    <?php if ($deliveries->num_rows > 0): ?>
      <?php while($d = $deliveries->fetch_assoc()): ?>
        <div class="delivery-card">
          <div class="d-flex justify-content-between flex-wrap align-items-center">
            <div>
              <strong><?= htmlspecialchars($d['item_name']) ?></strong><br>
              <small class="text-muted"><i class="bi bi-truck me-1"></i><?= htmlspecialchars($d['supplier_name']) ?></small>
            </div>
            <div class="text-end">
              <span class="badge bg-success"><?= htmlspecialchars($d['quantity'] . ' ' . $d['unit']) ?></span><br>
              <small><i class="bi bi-calendar-event me-1"></i><?= date("F j, Y", strtotime($d['delivery_date'])) ?></small>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-muted text-center">No deliveries recorded yet.</p>
    <?php endif; ?>
  </div>
</section>

<!-- 🌿 ABOUT -->
<section class="about">
  <div class="container">
    <h2>About Mahayag Lumberworks</h2>
    <p>We are a sustainable lumber provider based in Mahayag, Zamboanga del Sur. Our mission is to provide high-quality, locally sourced lumber while promoting responsible forestry and community livelihood. Every piece of wood is crafted with care, ensuring durability and environmental respect.</p>
  </div>
</section>

<!-- 📞 CONTACT -->
<section class="contact">
  <div class="container">
    <h2><i class="bi bi-envelope-paper me-2"></i>Get In Touch</h2>
    <p class="lead mt-3">We’d love to hear from you! Contact us for quotations, orders, or partnerships.</p>
    <p class="mt-4"><i class="bi bi-telephone me-2"></i> +63 912 345 6789</p>
    <p><i class="bi bi-envelope me-2"></i> mahayaglumberworks@gmail.com</p>
    <p><i class="bi bi-geo-alt me-2"></i> Purok 4, Mahayag, Zamboanga del Sur</p>
  </div>
</section>

<!-- 🌳 FOOTER -->
<footer>
  &copy; <?= date('Y') ?> Mahayag Lumberworks — All Rights Reserved.
</footer>

<!-- 🪵 VIEW DETAILS MODAL -->
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h6 class="modal-title" id="viewModalLabel"><i class="bi bi-tree me-2"></i>Wood Product Details</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body row g-3 align-items-center">
        <div class="col-md-5">
          <img id="modalImage" src="" alt="Wood" class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-md-7">
          <h5 id="modalName" class="fw-bold mb-2"></h5>
          <p id="modalType" class="text-muted mb-1"></p>
          <p id="modalSize" class="text-muted mb-1"></p>
          <p id="modalQty" class="text-muted mb-1"></p>
          <p id="modalPrice" class="fw-semibold text-success fs-5 mb-2"></p>
          <p id="modalStatus" class="small fw-semibold"></p>
          <hr>
          <p class="small text-secondary">This lumber is carefully cut and treated to ensure long-term durability and premium finish. Ideal for furniture, construction, and craftwork. Stock levels are regularly updated to maintain supply accuracy.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
const viewModal = document.getElementById('viewModal');
viewModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  document.getElementById('modalName').textContent = button.getAttribute('data-name');
  document.getElementById('modalType').textContent = "Type: " + button.getAttribute('data-type');
  document.getElementById('modalSize').textContent = "Dimension: " + button.getAttribute('data-size');
  document.getElementById('modalQty').textContent = "Available Stock: " + button.getAttribute('data-qty') + " " + button.getAttribute('data-unit');
  document.getElementById('modalPrice').textContent = button.getAttribute('data-price');
  document.getElementById('modalImage').src = button.getAttribute('data-img');
  document.getElementById('modalStatus').textContent = "Status: " + button.getAttribute('data-status');
});
</script>
</body>
</html>
