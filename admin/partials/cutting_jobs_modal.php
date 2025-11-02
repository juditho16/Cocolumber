<?php
/**
 * cutting_jobs_modal.php
 * ✅ Enhanced modals for Adding, Editing/Reassigning, and Assigning Cutting Jobs.
 * Features:
 *  - Dynamic Bootstrap modals
 *  - Auto-prefill for reassign/edit modal
 *  - Form reset on close
 */
?>

<!-- 🟢 Add Cutting Job Modal -->
<div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Cutting Job</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="addJobForm" method="POST">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label fw-semibold">Job Name</label>
              <input type="text" name="job_name" class="form-control" placeholder="e.g., Gemelina Planks" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Select Inventory Item</label>
              <select name="inventory_id" class="form-select" required>
                <option value="">Select Lumber</option>
                <?php
                $items = $conn->query("SELECT inventory_id, name FROM inventory_items ORDER BY name ASC");
                while ($i = $items->fetch_assoc()):
                  echo "<option value='{$i['inventory_id']}'>{$i['name']}</option>";
                endwhile;
                ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Target Quantity</label>
              <input type="number" name="target_quantity" class="form-control" min="1" required>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Due Date</label>
              <input type="date" name="due_date" class="form-control" required>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-save2 me-1"></i>Save Job
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- 🟦 Edit / Reassign Job Modal -->
<div class="modal fade" id="editJobModal" tabindex="-1" aria-labelledby="editJobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Reassign / Update Job</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="editJobForm" method="POST">
        <div class="modal-body">
          <input type="hidden" name="job_id" id="edit_job_id">

          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label fw-semibold">Job Name</label>
              <input type="text" name="job_name" id="edit_job_name" class="form-control" required>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Assign Worker</label>
              <select name="worker_id" id="edit_worker_id" class="form-select" required>
                <option value="">Select Worker</option>
                <?php
                $workers = $conn->query("SELECT worker_id, full_name FROM workers ORDER BY full_name ASC");
                while ($w = $workers->fetch_assoc()):
                  echo "<option value='{$w['worker_id']}'>{$w['full_name']}</option>";
                endwhile;
                ?>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" id="edit_job_status" class="form-select" required>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Completed">Completed</option>
              </select>
            </div>

            <div class="col-md-3">
              <label class="form-label fw-semibold">Due Date</label>
              <input type="date" name="due_date" id="edit_job_due" class="form-control" required>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save2 me-1"></i>Update Job
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- 🧑‍🏭 Assign Job Modal -->
<div class="modal fade" id="assignJobModal" tabindex="-1" aria-labelledby="assignJobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Assign Job to Worker</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="assignJobForm" method="POST">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Select Unassigned Job</label>
              <select name="job_id" id="assign_job_id" class="form-select" required>
                <option value="">Select Job</option>
                <?php
                $jobs = $conn->query("SELECT job_id, job_name FROM cutting_jobs WHERE job_id NOT IN (SELECT job_id FROM job_assignments) ORDER BY job_name ASC");
                while ($j = $jobs->fetch_assoc()):
                  echo "<option value='{$j['job_id']}'>{$j['job_name']}</option>";
                endwhile;
                ?>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label fw-semibold">Select Worker</label>
              <select name="worker_id" class="form-select" required>
                <option value="">Select Worker</option>
                <?php
                $workers = $conn->query("SELECT worker_id, full_name FROM workers ORDER BY full_name ASC");
                while ($w = $workers->fetch_assoc()):
                  echo "<option value='{$w['worker_id']}'>{$w['full_name']}</option>";
                endwhile;
                ?>
              </select>
            </div>

            <div class="col-md-12">
              <label class="form-label fw-semibold">Assign Date</label>
              <input type="date" name="assigned_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>Cancel
          </button>
          <button type="submit" class="btn btn-warning">
            <i class="bi bi-check2-circle me-1"></i>Assign Job
          </button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- 🔄 Modal Behavior Scripts -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  // 🧼 Reset Modals on Close
  ['addJobModal', 'editJobModal', 'assignJobModal'].forEach(id => {
    const modalEl = document.getElementById(id);
    modalEl?.addEventListener('hidden.bs.modal', () => {
      const form = modalEl.querySelector('form');
      form?.reset();
    });
  });

  // 🔵 Handle Reassign Button Click (Auto Prefill)
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', () => {
      const jobId = btn.getAttribute('data-id');
      if (!jobId) return;

      fetch('<?php echo FUNCTIONS_URL; ?>global_get.php?type=job_assignments&id=' + jobId)
        .then(r => r.json())
        .then(data => {
          if (!data || data.error || data.length === 0) {
            Swal.fire('Error', 'Failed to load job details.', 'error');
            return;
          }

          const job = Array.isArray(data) ? data[0] : data;

          // ✅ Populate Modal Fields
          document.getElementById('edit_job_id').value = job.job_id ?? '';
          document.getElementById('edit_job_name').value = job.job_name ?? '';
          document.getElementById('edit_job_due').value = job.due_date ?? '';
          document.getElementById('edit_job_status').value = job.status ?? 'Pending';

          const workerSelect = document.getElementById('edit_worker_id');
          if (job.worker_id) workerSelect.value = job.worker_id;

          // ✅ Open Modal
          new bootstrap.Modal(document.getElementById('editJobModal')).show();
        })
        .catch(() => Swal.fire('Error', 'Unable to load job data.', 'error'));
    });
  });
});
</script>
