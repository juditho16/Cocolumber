<?php
include(__DIR__ . "/../config/db_connection.php");
include(__DIR__ . "/../functions/session_verifier.php");
include_once(__DIR__ . "/../partials/header.php");
?>

<style>
  :root {
    --green: #28a745;
    --blue: #0d6efd;
    --yellow: #ffc107;
    --red: #dc3545;
    --bg: #f8fafb;
  }

  body {
    overflow: hidden !important;
    background: var(--bg);
  }

  .dashboard-container {
    height: calc(100vh - 70px);
    display: flex;
    flex-direction: column;
    gap: 1rem;
    overflow: hidden;
  }

  /* ✅ Row 1 - Metric Cards */
  .top-metrics {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    height: 17%;
  }

  .metric-card {
    background: #fff;
    border-radius: 8px;
    padding: 1rem 1.25rem;
    text-align: center;
    transition: 0.25s ease;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .metric-card:hover {
    transform: scale(1.03);
  }

  .metric-value {
    font-size: 1.6rem;
    font-weight: 700;
  }

  /* ✅ Row 2 - Analytics & Lumber Summary */
  .middle-analytics {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1rem;
    height: 48%;
  }

  .analytics-card {
    background: #fff;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
  }

  .analytics-card h6 {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 0.5rem;
  }

  .chart-container {
    flex: 1;
    min-height: 0;
  }

  /* ✅ Lumber Summary */
  .lumber-summary {
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--green) #e9f3e8;
  }

  .lumber-summary::-webkit-scrollbar {
    width: 8px;
  }

  .lumber-summary::-webkit-scrollbar-thumb {
    background: var(--green);
    border-radius: 6px;
  }

  .lumber-summary::-webkit-scrollbar-track {
    background: #e9f3e8;
  }

  .table-sm td {
    font-size: 0.85rem;
  }

  /* ✅ Row 3 - Full-width Recent Deliveries */
  .bottom-row {
    height: 33%;
  }

  .full-card {
    background: #fff;
    border-radius: 8px;
    padding: 1rem;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .scrollable-card-content {
    overflow-y: auto;
    flex: 1;
    scrollbar-width: thin;
    scrollbar-color: var(--green) #e9f3e8;
  }

  .scrollable-card-content::-webkit-scrollbar {
    width: 8px;
  }

  .scrollable-card-content::-webkit-scrollbar-thumb {
    background: var(--green);
    border-radius: 6px;
  }

  .scrollable-card-content::-webkit-scrollbar-track {
    background: #e9f3e8;
  }
</style>

<div class="container-fluid py-3 dashboard-container">
  <h4 class="fw-semibold mb-2"><i class="bi bi-speedometer2 me-2"></i>Dashboard Overview</h4>

  <!-- 🟩 Row 1: Top Metrics -->
  <div class="top-metrics">
    <div class="metric-card">
      <h6 class="text-muted">Total Lumber Stock</h6>
      <div class="metric-value text-success">425 pcs</div>
      <small class="text-muted">Across 10 categories</small>
    </div>
    <div class="metric-card">
      <h6 class="text-muted">Active Cutting Jobs</h6>
      <div class="metric-value text-primary">7</div>
      <small class="text-muted">3 unassigned, 4 ongoing</small>
    </div>
    <div class="metric-card">
      <h6 class="text-muted">Low Stock Alerts</h6>
      <div class="metric-value text-danger">3</div>
      <small class="text-muted">Gemelina, Narra, Teak</small>
    </div>
    <div class="metric-card">
      <h6 class="text-muted">Registered Suppliers</h6>
      <div class="metric-value text-warning">5</div>
      <small class="text-muted">Last added: GreenWood</small>
    </div>
  </div>

  <!-- 🟨 Row 2: Charts + Lumber Summary -->
  <div class="middle-analytics">
    <!-- Stock Movement -->
    <div class="analytics-card">
      <h6><i class="bi bi-bar-chart-fill me-2 text-success"></i>Stock Movement (Monthly)</h6>
      <div class="chart-container">
        <canvas id="stockMovement"></canvas>
      </div>
    </div>

    <!-- Inventory Distribution -->
    <div class="analytics-card">
      <h6><i class="bi bi-pie-chart-fill me-2 text-warning"></i>Inventory Distribution</h6>
      <div class="chart-container">
        <canvas id="inventoryDistribution"></canvas>
      </div>
    </div>

    <!-- Lumber Summary -->
    <div class="analytics-card">
      <h6><i class="bi bi-tree-fill text-success me-2"></i>Lumber Summary</h6>
      <div class="lumber-summary">
        <table class="table table-sm align-middle mb-0">
          <thead>
            <tr class="text-muted small">
              <th>Type</th><th>In Stock</th><th>Cutting</th><th>Outgoing</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>Gemelina</td><td class="text-success">120</td><td>20</td><td>5</td></tr>
            <tr><td>Mahogany</td><td class="text-success">85</td><td>15</td><td>8</td></tr>
            <tr><td>Acacia</td><td class="text-success">140</td><td>12</td><td>6</td></tr>
            <tr><td>Bamboo</td><td class="text-success">80</td><td>10</td><td>5</td></tr>
            <tr><td>Teak Wood</td><td class="text-success">60</td><td>5</td><td>3</td></tr>
            <tr><td>Narra</td><td class="text-success">50</td><td>8</td><td>2</td></tr>
            <tr><td>Eucalyptus</td><td class="text-success">75</td><td>10</td><td>6</td></tr>
            <tr><td>Fruit Trees</td><td class="text-success">30</td><td>4</td><td>1</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- 🟦 Row 3: Recent Deliveries -->
  <div class="bottom-row">
    <div class="full-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-semibold mb-0"><i class="bi bi-truck text-success me-2"></i>Recent Deliveries</h6>
        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#deliveriesModal">View All</button>
      </div>
      <div class="scrollable-card-content">
        <table class="table table-hover align-middle">
          <thead class="table-success">
            <tr><th>Supplier</th><th>Item</th><th>Qty</th><th>Date</th></tr>
          </thead>
          <tbody>
            <tr><td>Mahayag Timber Corp.</td><td>Gemelina Logs</td><td>250 pcs</td><td>2025-11-01</td></tr>
            <tr><td>EcoLumber Trading</td><td>Mahogany Lumber</td><td>180 pcs</td><td>2025-10-29</td></tr>
            <tr><td>GreenWood</td><td>Coconut Lumber</td><td>300 pcs</td><td>2025-10-27</td></tr>
            <tr><td>Zambo Wood Supply</td><td>Fruit Tree Logs</td><td>150 pcs</td><td>2025-10-24</td></tr>
            <tr><td>Lumber City Depot</td><td>Acacia Lumber</td><td>200 pcs</td><td>2025-10-20</td></tr>
            <tr><td>Southern Woodlink</td><td>Bamboo Poles</td><td>175 pcs</td><td>2025-10-18</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const stockCtx = document.getElementById('stockMovement');
  const distCtx = document.getElementById('inventoryDistribution');

  // Stock Movement Chart
  new Chart(stockCtx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
      datasets: [
        { label: 'Stock In', data: [200, 230, 210, 280, 240, 300, 290, 310, 330, 350], backgroundColor: 'rgba(25,135,84,0.8)' },
        { label: 'Stock Out', data: [100, 120, 130, 150, 180, 200, 190, 220, 210, 250], backgroundColor: 'rgba(220,53,69,0.8)' }
      ]
    },
    options: {
      plugins: { legend: { position: 'bottom' } },
      responsive: true,
      maintainAspectRatio: false,
      scales: { y: { beginAtZero: true } }
    }
  });

  // Inventory Distribution Chart
  new Chart(distCtx, {
    type: 'doughnut',
    data: {
      labels: ['Gemelina', 'Mahogany', 'Acacia', 'Bamboo', 'Teak Wood'],
      datasets: [{
        data: [120, 85, 140, 80, 35],
        backgroundColor: ['#198754','#0dcaf0','#ffc107','#20c997','#dc3545']
      }]
    },
    options: {
      plugins: { legend: { position: 'bottom' } },
      responsive: true,
      maintainAspectRatio: false
    }
  });
</script>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
