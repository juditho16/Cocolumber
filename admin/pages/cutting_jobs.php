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

  .cutting-jobs-wrapper {
    display: flex;
    flex-wrap: nowrap;
    height: 75vh;
    gap: 1rem;
  }

  .panel-left,
  .panel-right {
    display: flex;
    flex-direction: column;
    background: transparent;
    border: none;
    height: 100%;
  }

  .panel-left {
    width: 30%;
  }

  .panel-right {
    width: 70%;
  }

  .panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 0.75rem 0.75rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    height: 50px;
    background-color: transparent;
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .panel-header h6 {
    margin: 0;
    font-size: 0.9rem;
  }

  .panel-body {
    overflow-y: auto;
    padding: 0.75rem;
    flex-grow: 1;
    position: relative;
  }

  .unassigned-item {
    border: 1px solid rgba(0, 0, 0, 0.07);
    border-radius: 5px;
    padding: 0.75rem;
    margin-bottom: 0.6rem;
    background: rgba(255, 255, 255, 0.4);
    transition: all 0.2s ease;
  }

  .unassigned-item:hover {
    background: rgba(240, 240, 240, 0.4);
  }

  .job-card {
    border: 1px solid rgba(0, 0, 0, 0.07);
    border-radius: 6px;
    padding: 1rem 1.25rem;
    margin-bottom: 10px;
    background-color: rgba(255, 255, 255, 0.4);
    transition: all 0.2s ease-in-out;
    position: relative;
  }

  .job-card:hover {
    background-color: rgba(245, 245, 245, 0.6);
  }

  .badge-status {
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
    transition: background-color 0.2s;
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

  .btn-edit:hover i {
    color: #0a58ca;
  }

  .btn-delete:hover i {
    color: #bb2d3b;
  }
</style>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 px-2">
    <h4 class="section-title mb-0"><i class="bi bi-scissors me-2"></i>Cutting Jobs</h4>
  </div>

  <div class="cutting-jobs-wrapper">
    <!-- LEFT SIDE: Unassigned -->
    <div class="panel-left">
      <div class="panel-header">
        <h6 class="fw-semibold text-dark mb-0">
          <i class="bi bi-exclamation-circle text-danger me-2"></i>Unassigned Jobs
        </h6>
      </div>

      <div class="panel-body">
        <?php
        $unassigned_jobs = [
          ["id" => 201, "job_name" => "Gemelina Poles", "created_at" => "2025-11-01"],
          ["id" => 202, "job_name" => "Mahogany Strips", "created_at" => "2025-10-31"],
          ["id" => 203, "job_name" => "Coconut Lumber Batch 2", "created_at" => "2025-10-29"],
          ["id" => 204, "job_name" => "Akamiza Logs", "created_at" => "2025-10-28"],
        ];
        foreach ($unassigned_jobs as $job): ?>
          <div class="unassigned-item d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold small"><?= htmlspecialchars($job['job_name']) ?></div>
              <small class="text-muted">Created on <?= htmlspecialchars($job['created_at']) ?></small>
            </div>
            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignJobModal">
              <i class="bi bi-person-plus me-1"></i>Assign
            </button>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RIGHT SIDE: Assigned -->
    <div class="panel-right">
      <div class="panel-header">
        <h6 class="fw-semibold text-dark mb-0">
          <i class="bi bi-clipboard-check text-primary me-2"></i>Assigned Cutting Jobs
        </h6>
        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addJobModal">
          <i class="bi bi-plus-lg me-1"></i>Add Cutting Job
        </button>
      </div>

      <div class="panel-body">
        <?php
        $assigned_jobs = [
          ["id" => 101, "job_name" => "Gemelina Planks", "assigned_to" => "Worker A", "status" => "Pending", "due" => "2025-11-03"],
          ["id" => 102, "job_name" => "Mahogany Boards", "assigned_to" => "Worker B", "status" => "In Progress", "due" => "2025-11-04"],
          ["id" => 103, "job_name" => "Coconut Lumber Batch", "assigned_to" => "Worker C", "status" => "Completed", "due" => "2025-10-30"],
        ];
        foreach ($assigned_jobs as $job):
          $badgeClass = match($job['status']) {
            "Pending" => "bg-warning text-dark",
            "In Progress" => "bg-primary text-white",
            "Completed" => "bg-success text-white",
            default => "bg-secondary text-white"
          };
        ?>
          <div class="job-card d-flex justify-content-between align-items-center flex-wrap">
            <div>
              <h6 class="mb-1 fw-semibold text-dark small">
                <?= htmlspecialchars($job['job_name']) ?> <span class="text-muted">(#<?= $job['id'] ?>)</span>
              </h6>
              <div class="small text-muted">
                Assigned to: <strong><?= htmlspecialchars($job['assigned_to']) ?></strong> • 
                Due: <strong><?= htmlspecialchars($job['due']) ?></strong>
              </div>
              <span class="badge-status <?= $badgeClass ?>"><?= strtoupper($job['status']) ?></span>
            </div>

            <!-- ✅ Edit & Delete Buttons -->
            <div class="d-flex align-items-center gap-1">
              <button class="action-btn btn-edit"
                data-bs-toggle="modal"
                data-bs-target="#editJobModal"
                data-id="<?= $job['id'] ?>"
                data-name="<?= htmlspecialchars($job['job_name']) ?>"
                data-assigned="<?= htmlspecialchars($job['assigned_to']) ?>"
                data-due="<?= htmlspecialchars($job['due']) ?>"
                data-status="<?= htmlspecialchars($job['status']) ?>"
                title="Edit">
                <i class="bi bi-pencil-square"></i>
              </button>
              <button class="action-btn btn-delete" title="Delete" onclick="confirmDelete(<?= $job['id'] ?>)">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Edit Job Modal -->
<div class="modal fade" id="editJobModal" tabindex="-1" aria-labelledby="editJobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="../functions/manage_cutting_jobs.php">
      <div class="modal-header">
        <h6 class="modal-title" id="editJobModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Cutting Job</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="update">
        <input type="hidden" id="edit_job_id" name="job_id">

        <div class="mb-2">
          <label class="form-label small mb-1">Job Name</label>
          <input type="text" id="edit_job_name" name="job_name" class="form-control form-control-sm" required>
        </div>

        <div class="mb-2">
          <label class="form-label small mb-1">Assigned To</label>
          <input type="text" id="edit_assigned_to" name="assigned_to" class="form-control form-control-sm">
        </div>

        <div class="mb-2">
          <label class="form-label small mb-1">Due Date</label>
          <input type="date" id="edit_due_date" name="due_date" class="form-control form-control-sm">
        </div>

        <div class="mb-2">
          <label class="form-label small mb-1">Status</label>
          <select id="edit_status" name="status" class="form-select form-select-sm">
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm">Save Changes</button>
      </div>
    </form>
  </div>
</div>

<!-- Add Cutting Job Modal -->
<div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="../functions/manage_cutting_jobs.php">
      <div class="modal-header">
        <h6 class="modal-title" id="addJobModalLabel"><i class="bi bi-plus-circle me-2"></i>Add Cutting Job</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="create">
        <div class="mb-2">
          <label class="form-label small mb-1">Job Name</label>
          <input type="text" name="job_name" class="form-control form-control-sm" required>
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Material Type</label>
          <input type="text" name="type" class="form-control form-control-sm">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Size</label>
          <input type="text" name="size" class="form-control form-control-sm">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Quantity</label>
          <input type="number" name="quantity" class="form-control form-control-sm">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Due Date</label>
          <input type="date" name="due_date" class="form-control form-control-sm">
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success btn-sm">Save Job</button>
      </div>
    </form>
  </div>
</div>

<!-- Assign Modal -->
<div class="modal fade" id="assignJobModal" tabindex="-1" aria-labelledby="assignJobModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content" method="POST" action="../functions/manage_cutting_jobs.php">
      <div class="modal-header">
        <h6 class="modal-title" id="assignJobModalLabel"><i class="bi bi-person-plus me-2"></i>Assign Cutting Job</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="assign">
        <div class="mb-2">
          <label class="form-label small mb-1">Select Job</label>
          <select name="job_id" class="form-select form-select-sm">
            <option>Select a Job</option>
            <?php foreach ($unassigned_jobs as $job): ?>
              <option value="<?= $job['id'] ?>"><?= htmlspecialchars($job['job_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Assign To</label>
          <input type="text" name="assigned_to" class="form-control form-control-sm" placeholder="Worker or Machine">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1">Remarks</label>
          <textarea name="remarks" class="form-control form-control-sm" rows="2"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary btn-sm">Assign</button>
      </div>
    </form>
  </div>
</div>

<script>
function confirmDelete(id) {
  if (confirm("Are you sure you want to delete job #" + id + "?")) {
    alert("Job #" + id + " deleted successfully (placeholder)");
  }
}

// Fill edit modal dynamically
const editModal = document.getElementById('editJobModal');
editModal.addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  document.getElementById('edit_job_id').value = button.getAttribute('data-id');
  document.getElementById('edit_job_name').value = button.getAttribute('data-name');
  document.getElementById('edit_assigned_to').value = button.getAttribute('data-assigned');
  document.getElementById('edit_due_date').value = button.getAttribute('data-due');
  document.getElementById('edit_status').value = button.getAttribute('data-status');
});
</script>

<?php include(__DIR__ . "/../partials/footer.php"); ?>
