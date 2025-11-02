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
  body { overflow: hidden !important; background: var(--bg); }
  .dashboard-container { height: calc(100vh - 70px); display: flex; flex-direction: column; gap: 1rem; overflow: hidden; }
  .top-metrics { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; height: 17%; }
  .metric-card {
    background: #fff; border-radius: 8px; padding: 1rem 1.25rem; text-align: center;
    transition: 0.25s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    display: flex; flex-direction: column; justify-content: center;
  }
  .metric-card:hover { transform: scale(1.03); }
  .metric-value { font-size: 1.6rem; font-weight: 700; }
  .middle-analytics { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; height: 48%; }
  .analytics-card { background: #fff; border-radius: 8px; padding: 1rem; display: flex; flex-direction: column; }
  .analytics-card h6 { font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem; }
  .chart-container { flex: 1; min-height: 0; }
  .lumber-summary { overflow-y: auto; scrollbar-width: thin; scrollbar-color: var(--green) #e9f3e8; }
  .lumber-summary::-webkit-scrollbar { width: 8px; }
  .lumber-summary::-webkit-scrollbar-thumb { background: var(--green); border-radius: 6px; }
  .lumber-summary::-webkit-scrollbar-track { background: #e9f3e8; }
  .table-sm td { font-size: 0.85rem; }
  .bottom-row { height: 33%; }
  .full-card {
    background: #fff; border-radius: 8px; padding: 1rem; height: 100%;
    display: flex; flex-direction: column;
  }
  .scrollable-card-content {
    overflow-y: auto; flex: 1; scrollbar-width: thin;
    scrollbar-color: var(--green) #e9f3e8;
  }
  .scrollable-card-content::-webkit-scrollbar { width: 8px; }
  .scrollable-card-content::-webkit-scrollbar-thumb { background: var(--green); border-radius: 6px; }
  .scrollable-card-content::-webkit-scrollbar-track { background: #e9f3e8; }
</style>

<div class="container-fluid py-3 dashboard-container">
  <h4 class="fw-semibold mb-2"><i class="bi bi-speedometer2 me-2"></i>Dashboard Overview</h4>

  <?php
  // 📊 Query totals directly
  $totalStock = $conn->query("SELECT SUM(quantity) AS total FROM inventory_items")->fetch_assoc()['total'] ?? 0;
  $totalTypes = $conn->query("SELECT COUNT(*) AS total FROM inventory_items")->fetch_assoc()['total'] ?? 0;
  $lowStock   = $conn->query("SELECT COUNT(*) AS total FROM inventory_items WHERE status='Low Stock'")->fetch_assoc()['total'] ?? 0;
  $suppliers  = $conn->query("SELECT COUNT(*) AS total FROM suppliers")->fetch_assoc()['total'] ?? 0;
  $cutting    = $conn->query("SELECT COUNT(*) AS total FROM cutting_jobs WHERE status IN ('Pending','In Progress')")->fetch_assoc()['total'] ?? 0;
  ?>

  <!-- 🟩 Row 1: Top Metrics -->
  <div class="top-metrics">
    <div class="metric-card">
      <h6 class="text-muted">Total Lumber Stock</h6>
      <div class="metric-value text-success"><?= number_format($totalStock) ?> pcs</div>
      <small class="text-muted"><?= $totalTypes ?> lumber types</small>
    </div>
    <div class="metric-card">
      <h6 class="text-muted">Active Cutting Jobs</h6>
      <div class="metric-value text-primary"><?= $cutting ?></div>
      <small class="text-muted">Pending or ongoing</small>
    </div>
    <div class="metric-card">
      <h6 class="text-muted">Low Stock Alerts</h6>
      <div class="metric-value text-danger"><?= $lowStock ?></div>
      <small class="text-muted">Low inventory items</small>
    </div>
    <div class="metric-card">
      <h6 class="text-muted">Registered Suppliers</h6>
      <div class="metric-value text-warning"><?= $suppliers ?></div>
      <small class="text-muted">Total in system</small>
    </div>
  </div>

  <!-- 🟨 Row 2 -->
  <div class="middle-analytics">
    <!-- 📈 Stock Movement -->
    <div class="analytics-card">
      <h6><i class="bi bi-bar-chart-fill me-2 text-success"></i>Stock Movement (Monthly)</h6>
      <div class="chart-container"><canvas id="stockMovement"></canvas></div>
    </div>

    <!-- 🥧 Inventory Distribution -->
    <div class="analytics-card">
      <h6><i class="bi bi-pie-chart-fill me-2 text-warning"></i>Inventory Distribution</h6>
      <div class="chart-container"><canvas id="inventoryDistribution"></canvas></div>
    </div>

    <!-- 🌲 Lumber Summary -->
    <div class="analytics-card">
      <h6><i class="bi bi-tree-fill text-success me-2"></i>Lumber Summary</h6>
      <div class="lumber-summary">
        <table class="table table-sm align-middle mb-0">
          <thead>
            <tr class="text-muted small">
              <th>Type</th><th>Stock</th><th>Status</th><th>Size</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $items = $conn->query("SELECT name, quantity, status, size FROM inventory_items ORDER BY name ASC");
            if ($items->num_rows > 0):
              while ($r = $items->fetch_assoc()):
            ?>
              <tr>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td class="<?= $r['quantity'] < 50 ? 'text-danger' : 'text-success' ?>"><?= $r['quantity'] ?></td>
                <td><?= htmlspecialchars($r['status']) ?></td>
                <td><?= htmlspecialchars($r['size']) ?></td>
              </tr>
            <?php endwhile; else: ?>
              <tr><td colspan="4" class="text-center text-muted">No inventory data</td></tr>
            <?php endif; ?>
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
          <tbody id="deliveryTable"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 📦 Fetch recent deliveries from global_get
fetch('../admin/functions/global_get.php?type=deliveries')
  .then(r => r.json())
  .then(data => {
    const tbody = document.getElementById('deliveryTable');
    tbody.innerHTML = '';

    if (!Array.isArray(data) || data.length === 0) {
      tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted">No recent deliveries</td></tr>`;
      return;
    }

    data.slice(0, 8).forEach(row => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${row.supplier_name || 'N/A'}</td>
        <td>${row.item_name || 'Unknown'}</td>
        <td>${row.quantity} ${row.unit}</td>
        <td>${row.delivery_date}</td>
      `;
      tbody.appendChild(tr);
    });
  });

// 📊 Fetch inventory for charts
fetch('../admin/functions/global_get.php?type=inventory')
  .then(r => r.json())
  .then(items => {
    const labels = items.map(i => i.name);
    const stockData = items.map(i => i.quantity);
    const colors = ['#198754','#0dcaf0','#ffc107','#20c997','#dc3545','#6f42c1','#fd7e14','#0d6efd','#6c757d','#198754'];

    new Chart(document.getElementById('inventoryDistribution'), {
      type: 'doughnut',
      data: {
        labels, datasets: [{ data: stockData, backgroundColor: colors }]
      },
      options: { plugins: { legend: { position: 'bottom' } }, maintainAspectRatio: false }
    });
  });

// 🔄 Stock movement over time (simplified)
fetch('../admin/functions/global_get.php?type=stock_movements')
  .then(r => r.json())
  .then(moves => {
    const months = Array(12).fill(0).map((_, i) => new Date(2025, i).toLocaleString('default', { month: 'short' }));
    const stockIn = Array(12).fill(0);
    const stockOut = Array(12).fill(0);

    moves.forEach(m => {
      const monthIdx = new Date(m.created_at).getMonth();
      if (m.movement_type === 'IN') stockIn[monthIdx] += parseInt(m.quantity);
      else stockOut[monthIdx] += parseInt(m.quantity);
    });

    new Chart(document.getElementById('stockMovement'), {
      type: 'bar',
      data: {
        labels: months,
        datasets: [
          { label: 'Stock In', data: stockIn, backgroundColor: 'rgba(25,135,84,0.8)' },
          { label: 'Stock Out', data: stockOut, backgroundColor: 'rgba(220,53,69,0.8)' }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true } }
      }
    });
  });
</script>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
