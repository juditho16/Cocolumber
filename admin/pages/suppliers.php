<?php
include(__DIR__ . "/../config/db_connection.php");
include(__DIR__ . "/../functions/session_verifier.php");
include_once(__DIR__ . "/../partials/header.php");
?>

<style>
  .section-title { font-size: 1.25rem; font-weight: 600; }
  .suppliers-wrapper { display: flex; flex-wrap: nowrap; gap: 1.25rem; height: 75vh; }
  .panel-left, .panel-right { display: flex; flex-direction: column; background: transparent; border: none; height: 100%; }
  .panel-left { width: 40%; } .panel-right { width: 60%; }
  .panel-header { display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: 0.9rem;
    padding: 0.75rem; border-bottom: 1px solid rgba(0,0,0,0.05); height: 55px; background-color: transparent; position: sticky; top: 0; z-index: 10; }
  .search-box { position: sticky; top: 55px; z-index: 9; background-color: #fff; padding: 0.5rem 0.75rem; border-bottom: 1px solid rgba(0,0,0,0.05); }
  .search-box input { font-size: 0.85rem; height: 38px; border-radius: 6px; border: 1px solid #ced4da; transition: all 0.2s ease; }
  .search-box input:focus { border-color: #28a745; box-shadow: 0 0 0 0.2rem rgba(40,167,69,0.2); }
  .panel-body { flex-grow: 1; overflow-y: auto; padding: 0.75rem; }
  .supplier-card, .log-card { border: 1px solid rgba(0,0,0,0.07); border-radius: 6px; padding: 1rem 1.25rem; margin-bottom: 10px;
    background-color: rgba(255,255,255,0.4); transition: all 0.2s ease-in-out; }
  .supplier-card:hover, .log-card:hover { background-color: rgba(245,245,245,0.6); }
  .action-btn { border: none; background: transparent; font-size: 1rem; padding: 0.25rem 0.4rem; border-radius: 4px; transition: background-color 0.2s; }
  .action-btn:hover { background-color: rgba(0,0,0,0.08); }
  .btn-edit i { color: #0d6efd; } .btn-delete i { color: #dc3545; }
  .supplier-details small, .log-details small { display: block; color: #6b7280; }
</style>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 px-2">
    <h4 class="section-title mb-0"><i class="bi bi-truck me-2"></i>Suppliers & Deliveries</h4>
  </div>

  <div class="suppliers-wrapper">
    <!-- LEFT PANEL: Delivery Logs -->
    <div class="panel-left">
      <div class="panel-header">
        <h6 class="fw-semibold text-dark mb-0"><i class="bi bi-box-seam text-success me-2"></i>Delivery Logs</h6>
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addDeliveryModal">
          <i class="bi bi-plus-lg me-1"></i>Add Delivery Log
        </button>
      </div>

      <div class="search-box">
        <input type="text" id="searchLogs" class="form-control form-control-sm" placeholder="🔍 Search by supplier or wood type...">
      </div>

      <div class="panel-body" id="logsList">
        <?php
        $logs = $conn->query("
          SELECT d.*, s.name AS supplier_name, i.name AS wood_type
          FROM deliveries d
          JOIN suppliers s ON s.supplier_id = d.supplier_id
          JOIN inventory_items i ON i.inventory_id = d.inventory_id
          ORDER BY d.delivery_date DESC
        ");
        if ($logs && $logs->num_rows > 0):
          while ($log = $logs->fetch_assoc()): ?>
            <div class="log-card" data-supplier="<?= strtolower($log['supplier_name']) ?>" data-wood="<?= strtolower($log['wood_type']) ?>">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <h6 class="fw-semibold mb-0 text-dark"><?= htmlspecialchars($log['wood_type']) ?></h6>
                <span class="badge bg-secondary"><?= htmlspecialchars($log['quantity']) . ' ' . htmlspecialchars($log['unit']) ?></span>
              </div>
              <div class="log-details small">
                <small><i class="bi bi-person-badge me-1"></i><?= htmlspecialchars($log['supplier_name']) ?></small>
                <small><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($log['delivery_date']) ?></small>
              </div>
            </div>
        <?php endwhile; else: ?>
          <p class="text-muted text-center mt-3">No deliveries found.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- RIGHT PANEL: Supplier Directory -->
    <div class="panel-right">
      <div class="panel-header">
        <h6 class="fw-semibold text-dark mb-0"><i class="bi bi-building text-primary me-2"></i>Supplier Directory</h6>
        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
          <i class="bi bi-plus-lg me-1"></i>Add Supplier
        </button>
      </div>

      <div class="search-box">
        <input type="text" id="searchSupplier" class="form-control form-control-sm" placeholder="🔍 Search supplier name or contact...">
      </div>

      <div class="panel-body" id="supplierList">
        <?php
        $suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");
        if ($suppliers && $suppliers->num_rows > 0):
          while ($supplier = $suppliers->fetch_assoc()): ?>
            <div class="supplier-card d-flex justify-content-between align-items-start flex-wrap"
                 data-id="<?= $supplier['supplier_id'] ?>"
                 data-name="<?= strtolower($supplier['name']) ?>"
                 data-contact="<?= strtolower($supplier['contact_person']) ?>">
              <div class="supplier-details">
                <h6 class="fw-semibold mb-1 text-dark"><?= htmlspecialchars($supplier['name']) ?></h6>
                <small><i class="bi bi-person-lines-fill me-1"></i><?= htmlspecialchars($supplier['contact_person']) ?></small>
                <small><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($supplier['address']) ?></small>
                <small><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($supplier['email_or_phone']) ?></small>
              </div>
              <div class="d-flex align-items-center gap-1">
                <button class="action-btn btn-edit" title="Edit" data-bs-toggle="modal"
                  data-bs-target="#editSupplierModal"
                  data-id="<?= $supplier['supplier_id'] ?>"
                  data-name="<?= htmlspecialchars($supplier['name']) ?>"
                  data-contact="<?= htmlspecialchars($supplier['contact_person']) ?>"
                  data-address="<?= htmlspecialchars($supplier['address']) ?>"
                  data-email="<?= htmlspecialchars($supplier['email_or_phone']) ?>">
                  <i class="bi bi-pencil-square"></i>
                </button>
                <button class="action-btn btn-delete" title="Delete" data-id="<?= $supplier['supplier_id'] ?>" data-bs-toggle="modal" data-bs-target="#deleteSupplierModal">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
        <?php endwhile; else: ?>
          <p class="text-muted text-center mt-3">No suppliers found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- 📦 Include Modals -->
<?php include(__DIR__ . '/../partials/suppliers_modal.php'); ?>

<!-- ✅ SweetAlert + Functions -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const logs = [...document.querySelectorAll('.log-card')];
document.getElementById('searchLogs').addEventListener('input', e => {
  const q = e.target.value.toLowerCase();
  logs.forEach(c => {
    const s = c.dataset.supplier, w = c.dataset.wood;
    c.style.display = (s.includes(q) || w.includes(q)) ? '' : 'none';
  });
});

const suppliers = [...document.querySelectorAll('.supplier-card')];
document.getElementById('searchSupplier').addEventListener('input', e => {
  const q = e.target.value.toLowerCase();
  suppliers.forEach(c => {
    const n = c.dataset.name, t = c.dataset.contact;
    c.style.display = (n.includes(q) || t.includes(q)) ? '' : 'none';
  });
});

// 🟢 Add Supplier
document.getElementById('addSupplierForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch('../admin/functions/supplier_add.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      Swal.fire(d.status === 'success' ? 'Added!' : 'Error', d.message, d.status);
      if (d.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById('addSupplierModal')).hide();
        setTimeout(() => location.reload(), 1000);
      }
    })
    .catch(() => Swal.fire('Error', 'Unable to add supplier.', 'error'));
});

// 🟦 Update Supplier
document.getElementById('editSupplierForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch('../admin/functions/supplier_update.php', { method: 'POST', body: fd })
    .then(r => r.json()).then(d => {
      Swal.fire(d.status === 'success' ? 'Updated!' : 'Error', d.message, d.status);
      if (d.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById('editSupplierModal')).hide();
        setTimeout(() => location.reload(), 1000);
      }
    }).catch(() => Swal.fire('Error', 'Unable to update supplier.', 'error'));
});

// 🔴 Delete Supplier
// 🔴 Delete Supplier (SweetAlert only, no modal)
document.querySelectorAll('.btn-delete').forEach(btn => {
  btn.addEventListener('click', () => {
    const id = btn.getAttribute('data-id');

    Swal.fire({
      title: 'Delete this supplier?',
      text: 'This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it',
      cancelButtonText: 'Cancel',
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d'
    }).then(result => {
      if (result.isConfirmed) {
        fetch('../admin/functions/supplier_delete.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'id=' + encodeURIComponent(id)
        })
        .then(r => r.json())
        .then(d => {
          Swal.fire(d.status === 'success' ? 'Deleted!' : 'Error', d.message, d.status);
          if (d.status === 'success') {
            document.querySelector(`.supplier-card[data-id="${id}"]`)?.remove();
          }
        })
        .catch(() => Swal.fire('Error', 'Unable to delete supplier.', 'error'));
      }
    });
  });
});



// 🚚 Add Delivery
document.getElementById('addDeliveryForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch('../admin/functions/delivery_add.php', { method: 'POST', body: fd })
    .then(r => r.json()).then(d => {
      Swal.fire(d.status === 'success' ? 'Added!' : 'Error', d.message, d.status);
      if (d.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById('addDeliveryModal')).hide();
        setTimeout(() => location.reload(), 1000);
      }
    }).catch(() => Swal.fire('Error', 'Unable to add delivery log.', 'error'));
});

// 🟦 Prefill Edit Modal
const editModal = document.getElementById('editSupplierModal');
editModal?.addEventListener('show.bs.modal', e => {
  const b = e.relatedTarget;
  document.getElementById('edit_supplier_id').value = b.getAttribute('data-id');
  document.getElementById('edit_supplier_name').value = b.getAttribute('data-name');
  document.getElementById('edit_supplier_contact').value = b.getAttribute('data-contact');
  document.getElementById('edit_supplier_address').value = b.getAttribute('data-address');
  document.getElementById('edit_supplier_email').value = b.getAttribute('data-email');
});
</script>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
