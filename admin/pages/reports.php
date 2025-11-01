<?php
include(__DIR__ . "/../config/db_connection.php");
include(__DIR__ . "/../functions/session_verifier.php");
include_once(__DIR__ . "/../partials/header.php");
?>

<div class="container py-4">
  <h4 class="fw-semibold mb-4"><i class="bi bi-graph-up me-2"></i>Generate Reports</h4>

  <form method="POST" action="../functions/generate_reports.php" class="row g-3">
    <div class="col-md-4">
      <label class="form-label">Report Type</label>
      <select name="type" class="form-select">
        <option value="inventory">Inventory</option>
        <option value="cutting">Cutting Jobs</option>
        <option value="suppliers">Suppliers</option>
      </select>
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button class="btn btn-primary w-100"><i class="bi bi-download me-1"></i> Export CSV</button>
    </div>
  </form>
</div>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
``
