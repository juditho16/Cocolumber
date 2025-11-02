<?php
/**
 * workers_modal.php
 * Contains modals for Add and Edit Worker.
 */
?>

<!-- 🟢 Add Worker Modal -->
<div class="modal fade" id="addWorkerModal" tabindex="-1" aria-labelledby="addWorkerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Add Worker</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="addWorkerForm" method="POST">
        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g., Juan Dela Cruz" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Age</label>
            <input type="number" name="age" class="form-control" min="18" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Address</label>
            <input type="text" name="address" class="form-control" placeholder="Purok 4, Mahayag, Zamboanga del Sur" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Contract No.</label>
            <input type="text" name="contract_no" class="form-control" placeholder="CNT-2025-001" required>
          </div>

        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save2 me-1"></i>Save Worker
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🟦 Edit Worker Modal -->
<div class="modal fade" id="editWorkerModal" tabindex="-1" aria-labelledby="editWorkerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Worker</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="editWorkerForm" method="POST">
        <div class="modal-body">
          <input type="hidden" id="edit_worker_id" name="worker_id">

          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name</label>
            <input type="text" id="edit_worker_name" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Age</label>
            <input type="number" id="edit_worker_age" name="age" class="form-control" min="18" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Address</label>
            <input type="text" id="edit_worker_address" name="address" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Contract No.</label>
            <input type="text" id="edit_worker_contract" name="contract_no" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select id="edit_worker_status" name="status" class="form-select">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save2 me-1"></i>Update Worker
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// 🧹 Reset Forms when Modals Close
['addWorkerModal', 'editWorkerModal'].forEach(id => {
  const modalEl = document.getElementById(id);
  modalEl?.addEventListener('hidden.bs.modal', () => {
    const form = modalEl.querySelector('form');
    if (form) form.reset();
  });
});
</script>
