<?php
include(__DIR__ . "/../config/db_connection.php");
include(__DIR__ . "/../functions/session_verifier.php");
include_once(__DIR__ . "/../partials/header.php");
?>

<style>
  .section-title {
    font-size: 1.25rem;
    font-weight: 600;
  }

  .suppliers-wrapper {
    display: flex;
    flex-wrap: nowrap;
    gap: 1.25rem;
    height: 75vh;
  }

  .panel-left, .panel-right {
    display: flex;
    flex-direction: column;
    background: transparent;
    border: none;
    height: 100%;
  }

  .panel-left { width: 40%; }
  .panel-right { width: 60%; }

  .panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.75rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    height: 50px;
    background-color: transparent;
  }

  .panel-body {
    flex-grow: 1;
    overflow-y: auto;
    padding: 0.75rem;
  }

  .supplier-card, .log-card {
    border: 1px solid rgba(0, 0, 0, 0.07);
    border-radius: 6px;
    padding: 1rem 1.25rem;
    margin-bottom: 10px;
    background-color: rgba(255, 255, 255, 0.4);
    transition: all 0.2s ease-in-out;
  }

  .supplier-card:hover, .log-card:hover {
    background-color: rgba(245, 245, 245, 0.6);
  }

  .action-btn {
    border: none;
    background: transparent;
    font-size: 1rem;
    padding: 0.25rem 0.4rem;
    border-radius: 4px;
    transition: background-color 0.2s;
  }

  .action-btn:hover { background-color: rgba(0, 0, 0, 0.08); }
  .btn-edit i { color: #0d6efd; }
  .btn-delete i { color: #dc3545; }
  .btn-edit:hover i { color: #0a58ca; }
  .btn-delete:hover i { color: #bb2d3b; }

  .supplier-details small, .log-details small {
    display: block;
    color: #6b7280;
  }

  .search-bar {
    width: 250px;
  }

  .form-label.small { font-size: 0.8rem; }
</style>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 px-2">
    <h4 class="section-title mb-0"><i class="bi bi-truck me-2"></i>Suppliers & Deliveries</h4>
  </div>

  <div class="suppliers-wrapper">
    <!-- LEFT PANEL: Supplier Delivery Logs -->
    <div class="panel-left">
      <div class="panel-header">
        <h6 class="fw-semibold text-dark mb-0"><i class="bi bi-box-seam text-success me-2"></i>Delivery Logs</h6>
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
          <i class="bi bi-plus-lg me-1"></i>Add Delivery Log
        </button>
      </div>

      <div class="panel-body">
        <?php
        $logs = [
          ["id" => 1, "supplier" => "Mahayag Timber Corp.", "wood_type" => "Gemelina Logs", "quantity" => "250 pcs", "date" => "2025-11-01"],
          ["id" => 2, "supplier" => "Zambo Wood Supplies", "wood_type" => "Mahogany Lumber", "quantity" => "180 pcs", "date" => "2025-10-30"],
          ["id" => 3, "supplier" => "GreenWood Distributors", "wood_type" => "Coconut Lumber", "quantity" => "300 pcs", "date" => "2025-10-28"],
          ["id" => 4, "supplier" => "EcoLumber Trading", "wood_type" => "Gemelina Poles", "quantity" => "120 pcs", "date" => "2025-10-26"],
        ];
        foreach ($logs as $log): ?>
          <div class="log-card">
            <div class="d-flex justify-content-between align-items-center mb-1">
              <h6 class="fw-semibold mb-0 text-dark"><?= htmlspecialchars($log['wood_type']) ?></h6>
              <span class="badge bg-secondary"><?= htmlspecialchars($log['quantity']) ?></span>
            </div>
            <div class="log-details small">
              <small><i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($log['supplier']) ?></small>
              <small><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($log['date']) ?></small>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RIGHT PANEL: Supplier Directory -->
    <div class="panel-right">
      <div class="panel-header">
        <h6 class="fw-semibold text-dark mb-0"><i class="bi bi-building text-primary me-2"></i>Supplier Directory</h6>
        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
            <i class="bi bi-plus-lg me-1"></i>Add Supplier
          </button>
          <input type="text" id="searchSupplier" class="form-control form-control-sm search-bar" placeholder="Search supplier...">
          <button class="btn btn-sm btn-outline-secondary" onclick="filterSuppliers()"><i class="bi bi-search"></i></button>
        </div>
      </div>

      <div class="panel-body" id="supplierList">
        <?php
        $suppliers = [
          ["id" => 1, "name" => "Mahayag Timber Corp.", "contact" => "John Dela Cruz", "address" => "Poblacion, Mahayag", "email" => "timbercorp@gmail.com"],
          ["id" => 2, "name" => "Zambo Wood Supplies", "contact" => "Maria Lopez", "address" => "Molave, Zamboanga del Sur", "email" => "zambowood@gmail.com"],
          ["id" => 3, "name" => "EcoLumber Trading", "contact" => "Ramon Bautista", "address" => "Pagadian City", "email" => "eco.lumbertrading@yahoo.com"],
          ["id" => 4, "name" => "GreenWood Distributors", "contact" => "Erika Villanueva", "address" => "Aurora, ZDS", "email" => "greenwood@gmail.com"],
        ];
        foreach ($suppliers as $supplier): ?>
          <div class="supplier-card d-flex justify-content-between align-items-start flex-wrap">
            <div class="supplier-details">
              <h6 class="fw-semibold mb-1 text-dark"><?= htmlspecialchars($supplier['name']) ?></h6>
              <small><i class="bi bi-person-lines-fill me-1"></i><?= htmlspecialchars($supplier['contact']) ?></small>
              <small><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($supplier['address']) ?></small>
              <small><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($supplier['email']) ?></small>
            </div>
            <div class="d-flex align-items-center gap-1">
              <button class="action-btn btn-edit" title="Edit" data-bs-toggle="modal"
                data-bs-target="#editSupplierModal"
                data-id="<?= $supplier['id'] ?>"
                data-name="<?= htmlspecialchars($supplier['name']) ?>"
                data-contact="<?= htmlspecialchars($supplier['contact']) ?>"
                data-address="<?= htmlspecialchars($supplier['address']) ?>"
                data-email="<?= htmlspecialchars($supplier['email']) ?>">
                <i class="bi bi-pencil-square"></i>
              </button>
              <button class="action-btn btn-delete" title="Delete" onclick="confirmDelete(<?= $supplier['id'] ?>)">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="../functions/manage_suppliers.php">
      <div class="modal-header">
        <h6 class="modal-title" id="addSupplierModalLabel"><i class="bi bi-person-plus me-2"></i>Add Supplier</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-2">
          <label class="form-label small mb-1">Supplier Name</label>
          <input name="name" required class="form-control form-control-sm" placeholder="e.g., Mahayag Timber Corp.">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Contact Person</label>
          <input name="contact" class="form-control form-control-sm" placeholder="e.g., John Dela Cruz">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Address</label>
          <textarea name="address" class="form-control form-control-sm" rows="2" placeholder="Barangay or City Address"></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Email / Phone</label>
          <input name="email" class="form-control form-control-sm" placeholder="e.g., mahayagtimber@gmail.com / 0912-345-6789">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-sm"><i class="bi bi-check-circle me-1"></i>Save Supplier</button>
      </div>
    </form>
  </div>
</div>

<!-- Add Delivery Modal -->
<div class="modal fade" id="addDeliveryModal" tabindex="-1" aria-labelledby="addDeliveryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="../functions/manage_deliveries.php">
      <div class="modal-header">
        <h6 class="modal-title" id="addDeliveryModalLabel"><i class="bi bi-truck me-2"></i>Add Delivery Log</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-2">
          <label class="form-label small mb-1">Supplier</label>
          <select name="supplier" class="form-select form-select-sm" required>
            <option>Select Supplier</option>
            <option>Mahayag Timber Corp.</option>
            <option>Zambo Wood Supplies</option>
            <option>EcoLumber Trading</option>
            <option>GreenWood Distributors</option>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Wood Type</label>
          <input type="text" name="wood_type" class="form-control form-control-sm" placeholder="e.g., Gemelina Logs" required>
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Quantity</label>
          <input type="text" name="quantity" class="form-control form-control-sm" placeholder="e.g., 250 pcs / 50 cu.ft">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Delivery Date</label>
          <input type="date" name="delivery_date" class="form-control form-control-sm">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-sm"><i class="bi bi-plus-circle me-1"></i>Save Delivery Log</button>
      </div>
    </form>
  </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="../functions/manage_suppliers.php">
      <div class="modal-header">
        <h6 class="modal-title" id="editSupplierModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Supplier</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="update">
        <input type="hidden" id="edit_supplier_id" name="supplier_id">

        <div class="mb-2">
          <label class="form-label small mb-1">Supplier Name</label>
          <input type="text" id="edit_supplier_name" name="name" class="form-control form-control-sm" required>
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Contact Person</label>
          <input type="text" id="edit_supplier_contact" name="contact" class="form-control form-control-sm">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Address</label>
          <textarea id="edit_supplier_address" name="address" class="form-control form-control-sm" rows="2"></textarea>
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Email / Phone</label>
          <input type="text" id="edit_supplier_email" name="email" class="form-control form-control-sm">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm"><i class="bi bi-save me-1"></i>Save Changes</button>
      </div>
    </form>
  </div>
</div>

<script>
function confirmDelete(id) {
  if (confirm("Are you sure you want to delete supplier #" + id + "?")) {
    alert("Supplier #" + id + " deleted successfully (placeholder)");
  }
}

const editModal = document.getElementById('editSupplierModal');
editModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  document.getElementById('edit_supplier_id').value = button.getAttribute('data-id');
  document.getElementById('edit_supplier_name').value = button.getAttribute('data-name');
  document.getElementById('edit_supplier_contact').value = button.getAttribute('data-contact');
  document.getElementById('edit_supplier_address').value = button.getAttribute('data-address');
  document.getElementById('edit_supplier_email').value = button.getAttribute('data-email');
});

function filterSuppliers() {
  const input = document.getElementById('searchSupplier').value.toLowerCase();
  const cards = document.querySelectorAll('.supplier-card');
  cards.forEach(card => {
    const name = card.querySelector('.supplier-details h6').textContent.toLowerCase();
    card.style.display = name.includes(input) ? 'flex' : 'none';
  });
}
</script>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
