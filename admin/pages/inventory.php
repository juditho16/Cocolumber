<?php
include(__DIR__ . "/../config/db_connection.php");
include(__DIR__ . "/../functions/session_verifier.php");
include_once(__DIR__ . "/../partials/header.php");
?>

<style>
  :root {
    --scrollbar-track: #e9f3e8;
    --scrollbar-thumb: #28a745;
  }

  /* Prevent main page scroll */
  body {
    overflow: hidden !important;
  }

  .inventory-header {
    font-size: 1.25rem;
    font-weight: 600;
  }

  .summary-row {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
  }

  .summary-card {
    flex: 1;
    min-width: 200px;
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.2s ease-in-out;
    cursor: pointer;
  }

  .summary-card:hover {
    background-color: #f8f9fa;
  }

  .summary-icon {
    font-size: 1.75rem;
  }

  /* 🔹 Perfect viewport fit without clipping */
  .layout-wrapper {
    display: flex;
    gap: 1rem;
    height: calc(90vh - 190px); /* ✅ Adjusted to fit below navbar and padding */
    overflow: hidden;
  }

  /* 🔹 Scrollable internal sections */
  .inventory-section,
  .delivery-sidebar {
    overflow-y: auto;
    height: 100%;
    padding-top: 0.25rem;  /* small breathing space top */
    padding-bottom: 0.75rem; /* small space bottom */
    scrollbar-width: thin;
    scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
  }

  /* ✅ Custom green scrollbars */
  .inventory-section::-webkit-scrollbar,
  .delivery-sidebar::-webkit-scrollbar {
    width: 10px;
  }

  .inventory-section::-webkit-scrollbar-track,
  .delivery-sidebar::-webkit-scrollbar-track {
    background: var(--scrollbar-track);
    border-radius: 6px;
  }

  .inventory-section::-webkit-scrollbar-thumb,
  .delivery-sidebar::-webkit-scrollbar-thumb {
    background-color: var(--scrollbar-thumb);
    border-radius: 6px;
  }

  .inventory-section::-webkit-scrollbar-thumb:hover,
  .delivery-sidebar::-webkit-scrollbar-thumb:hover {
    background-color: #1e7a35;
  }

  /* Panels width ratio */
  .inventory-section {
    width: 80%;
    padding-right: 0.5rem;
  }

  .delivery-sidebar {
    width: 20%;
    min-width: 260px;
    max-width: 280px;
    border-left: 1px solid #dee2e6;
    background-color: transparent;
    border-radius: 6px;
    padding: 0.75rem;
    display: flex;
    flex-direction: column;
  }

  /* Delivery logs */
  .delivery-sidebar h6 {
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 0.75rem;
  }

  .delivery-log {
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 0.6rem;
    background-color: rgba(255, 255, 255, 0.5);
    transition: 0.2s;
  }

  .delivery-log:hover {
    background-color: rgba(245, 245, 245, 0.7);
  }

  /* Inventory items */
  .inventory-card {
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    padding: 1rem 1.25rem;
    margin-bottom: 10px;
    transition: all 0.2s ease-in-out;
    background-color: rgba(255, 255, 255, 0.5);
  }

  .inventory-card:hover {
    background-color: #f8f9fa;
  }

  .stock-badge {
    font-size: 0.7rem;
    font-weight: 600;
    border-radius: 4px;
    padding: 0.25rem 0.5rem;
  }

  .action-btn {
    border: none;
    background: transparent;
    font-size: 1rem;
    padding: 0.25rem 0.4rem;
    border-radius: 4px;
    transition: 0.2s;
  }

  .action-btn:hover {
    background-color: rgba(0, 0, 0, 0.08);
  }

  .btn-edit i {
    color: #0d6efd;
  }

  .btn-delete i {
    color: #dc3545;
  }

  @media (max-width: 992px) {
    body {
      overflow-y: auto !important;
    }
    .layout-wrapper {
      flex-direction: column;
      height: auto;
    }
    .inventory-section,
    .delivery-sidebar {
      width: 100%;
      height: auto;
      overflow-y: visible;
    }
  }
</style>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 px-2">
    <h4 class="inventory-header mb-0"><i class="bi bi-box-seam me-2"></i>Inventory Management</h4>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
      <i class="bi bi-plus-lg me-1"></i>Add Item
    </button>
  </div>

  <!-- Summary -->
  <div class="summary-row">
    <div class="summary-card">
      <i class="bi bi-boxes summary-icon text-primary"></i>
      <div>
        <div class="fw-semibold text-dark">Total Items</div>
        <div class="text-muted small">10 Inventory Entries</div>
      </div>
    </div>
    <div class="summary-card">
      <i class="bi bi-truck summary-icon text-success"></i>
      <div>
        <div class="fw-semibold text-dark">Suppliers</div>
        <div class="text-muted small">4 Registered</div>
      </div>
    </div>
    <div class="summary-card" data-bs-toggle="modal" data-bs-target="#lumberStockModal">
      <i class="bi bi-tree summary-icon text-warning"></i>
      <div>
        <div class="fw-semibold text-dark">Lumber Stocks</div>
        <div class="text-muted small">View Lumber Quantities</div>
      </div>
    </div>
  </div>

  <!-- Layout -->
  <div class="layout-wrapper">
    <!-- Left: Inventory -->
    <div class="inventory-section">
      <?php
      $inventory = [
        ["id" => 1, "name" => "Gemelina", "type" => "Lumber", "size" => "2x2x8", "quantity" => 365, "status" => "In Stock"],
        ["id" => 2, "name" => "Mahogany", "type" => "Lumber", "size" => "2x3x10", "quantity" => 40, "status" => "Low Stock"],
        ["id" => 3, "name" => "Coconut Lumber", "type" => "Wood", "size" => "2x4x12", "quantity" => 120, "status" => "In Stock"],
        ["id" => 4, "name" => "Plywood", "type" => "Panel", "size" => "1/4x4x8", "quantity" => 0, "status" => "Out of Stock"],
        ["id" => 5, "name" => "Bamboo", "type" => "Pole", "size" => "3x3x12", "quantity" => 95, "status" => "In Stock"],
        ["id" => 6, "name" => "Fruit Tree Logs", "type" => "Lumber", "size" => "2x2x6", "quantity" => 75, "status" => "In Stock"],
        ["id" => 7, "name" => "Teak Wood", "type" => "Premium", "size" => "2x2x10", "quantity" => 35, "status" => "Low Stock"],
        ["id" => 8, "name" => "Acacia", "type" => "Lumber", "size" => "2x3x8", "quantity" => 250, "status" => "In Stock"],
        ["id" => 9, "name" => "Narra", "type" => "Lumber", "size" => "2x4x10", "quantity" => 10, "status" => "Low Stock"],
        ["id" => 10, "name" => "Eucalyptus", "type" => "Lumber", "size" => "2x2x12", "quantity" => 210, "status" => "In Stock"],
      ];

      foreach ($inventory as $item):
        $badgeClass = match($item['status']) {
          "In Stock" => "bg-success text-white",
          "Low Stock" => "bg-warning text-dark",
          "Out of Stock" => "bg-danger text-white",
          default => "bg-secondary text-white"
        };
        $stockIn = rand(10, 35);
        $stockOut = rand(3, 20);
      ?>
      <div class="inventory-card d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h6 class="mb-1 fw-semibold text-dark"><?= htmlspecialchars($item['name']) ?> <span class="text-muted">(#<?= $item['id'] ?>)</span></h6>
          <div class="small text-muted"><?= htmlspecialchars($item['type']) ?> • <?= htmlspecialchars($item['size']) ?></div>
          <div class="mt-1">
            <span class="stock-badge <?= $badgeClass ?>"><?= strtoupper($item['status']) ?></span>
            <small class="text-muted ms-2"><?= $item['quantity'] ?> Available</small>
          </div>
        </div>

        <div class="d-flex align-items-center gap-4">
          <div class="text-end small text-muted">
            <div><i class="bi bi-arrow-down-circle text-success me-1"></i><strong>Stock In:</strong> <?= $stockIn ?></div>
            <div><i class="bi bi-arrow-up-circle text-danger me-1"></i><strong>Stock Out:</strong> <?= $stockOut ?></div>
          </div>
          <div class="d-flex gap-2">
            <button class="action-btn btn-edit" title="Edit"><i class="bi bi-pencil-square"></i></button>
            <button class="action-btn btn-delete" title="Delete"><i class="bi bi-trash"></i></button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Right: Delivery Logs -->
    <div class="delivery-sidebar">
      <h6><i class="bi bi-truck text-success me-2"></i>Delivery Logs</h6>
      <?php
      $logs = [
        ["supplier" => "Mahayag Timber Corp.", "wood" => "Gemelina Logs", "qty" => "250 pcs", "date" => "2025-11-01"],
        ["supplier" => "Zambo Wood Supplies", "wood" => "Mahogany Lumber", "qty" => "180 pcs", "date" => "2025-10-30"],
        ["supplier" => "EcoLumber Trading", "wood" => "Gemelina Poles", "qty" => "120 pcs", "date" => "2025-10-26"],
        ["supplier" => "GreenWood Distributors", "wood" => "Coconut Lumber", "qty" => "300 pcs", "date" => "2025-10-28"],
        ["supplier" => "Southern Woodlink", "wood" => "Bamboo Poles", "qty" => "200 pcs", "date" => "2025-10-22"],
        ["supplier" => "Lumber City Depot", "wood" => "Acacia Lumber", "qty" => "90 pcs", "date" => "2025-10-20"],
        ["supplier" => "Mindanao Forest Supply", "wood" => "Teak Wood", "qty" => "150 pcs", "date" => "2025-10-18"],
        ["supplier" => "TreeLife Lumber", "wood" => "Fruit Tree Logs", "qty" => "75 pcs", "date" => "2025-10-15"],
        ["supplier" => "Narra Wood Traders", "wood" => "Narra Logs", "qty" => "50 pcs", "date" => "2025-10-12"],
        ["supplier" => "Woodgrow Resources", "wood" => "Eucalyptus Logs", "qty" => "230 pcs", "date" => "2025-10-10"],
      ];

      foreach ($logs as $log): ?>
        <div class="delivery-log">
          <strong class="text-dark small"><?= htmlspecialchars($log['wood']) ?></strong>
          <small><i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($log['supplier']) ?></small>
          <small><i class="bi bi-box2 me-1"></i><?= htmlspecialchars($log['qty']) ?></small>
          <small><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($log['date']) ?></small>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Lumber Stock Modal -->
<div class="modal fade" id="lumberStockModal" tabindex="-1" aria-labelledby="lumberStockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="lumberStockModalLabel"><i class="bi bi-tree me-2"></i>Lumber Stock Overview</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <?php
          $lumber = [
            ["name" => "Gemelina", "qty" => 365],
            ["name" => "Mahogany", "qty" => 120],
            ["name" => "Fruit Trees", "qty" => 90],
          ];
          foreach ($lumber as $wood): ?>
            <div class="col-md-4">
              <div class="border rounded p-3 text-center">
                <h6 class="fw-semibold mb-1"><?= htmlspecialchars($wood['name']) ?></h6>
                <div class="text-muted small"><?= $wood['qty'] ?> pcs available</div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
