<?php
include_once(__DIR__ . "/../config/db_connection.php");
include_once(ROOT_PATH . "functions/session_verifier.php");
include_once(ROOT_PATH . "partials/header.php");
?>

<style>
  .section-title { font-size: 1.25rem; font-weight: 600; }
  .cutting-jobs-wrapper { display: flex; flex-wrap: nowrap; height: 75vh; gap: 1rem; overflow:hidden; }
  .panel-left, .panel-right {
    display: flex; flex-direction: column; background: transparent; border: none; height: 100%;
  }
  .panel-left { width: 30%; overflow-y:auto; }
  .panel-right { width: 70%; overflow-y:auto; }

  .panel-header {
    display: flex; justify-content: space-between; align-items: center;
    font-weight: 600; font-size: 0.9rem; padding: 0.75rem;
    border-bottom: 1px solid rgba(0,0,0,0.05); height: 50px;
    background-color: white; position: sticky; top: 0; z-index: 10;
  }

  .panel-body { padding: 0.75rem; flex-grow: 1; }
  .search-box {
    position: sticky; top: 50px; background: #fff; z-index: 9;
    padding: 0.4rem 0.75rem; border-bottom: 1px solid rgba(0,0,0,0.05);
  }
  .unassigned-item, .job-card {
    border: 1px solid rgba(0,0,0,0.07);
    border-radius: 6px;
    padding: 0.75rem;
    margin-bottom: 0.6rem;
    background: rgba(255,255,255,0.6);
    transition: all 0.2s ease;
  }
  .unassigned-item:hover, .job-card:hover { background: rgba(245,245,245,0.7); }

  .badge-status {
    font-size: 0.7rem; font-weight: 600; border-radius: 4px; padding: 0.25rem 0.5rem;
  }

  .btn-green {
    background-color: #28a745; color: #fff; border: 1px solid #28a745; transition: all 0.2s ease;
  }
  .btn-green:hover { background-color: transparent; color: #28a745; }

  .btn-danger-custom {
    background-color: #dc3545; color: #fff; border: 1px solid #dc3545; transition: all 0.2s ease;
  }
  .btn-danger-custom:hover { background-color: transparent; color: #dc3545; }
</style>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 px-2">
    <h4 class="section-title mb-0"><i class="bi bi-scissors me-2"></i>Cutting Jobs</h4>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addJobModal">
      <i class="bi bi-plus-lg me-1"></i>Add Cutting Job
    </button>
  </div>

  <div class="cutting-jobs-wrapper">
    <!-- LEFT: Unassigned -->
    <div class="panel-left">
      <div class="panel-header">
        <h6 class="fw-semibold text-dark mb-0"><i class="bi bi-exclamation-circle text-danger me-2"></i>Unassigned Jobs</h6>
      </div>
      <div class="search-box">
        <input type="text" id="searchUnassigned" class="form-control form-control-sm" placeholder="🔍 Search by job name...">
      </div>
      <div class="panel-body" id="unassignedList">
        <?php
        $unassigned = $conn->query("
          SELECT cj.*, i.name AS material_name
          FROM cutting_jobs cj
          LEFT JOIN inventory_items i ON i.inventory_id = cj.inventory_id
          LEFT JOIN job_assignments ja ON ja.job_id = cj.job_id
          WHERE ja.job_id IS NULL
          ORDER BY cj.created_at DESC
        ");
        if ($unassigned->num_rows > 0):
          while ($job = $unassigned->fetch_assoc()):
        ?>
        <div class="unassigned-item d-flex justify-content-between align-items-center" data-name="<?= strtolower($job['job_name']) ?>">
          <div>
            <div class="fw-semibold small"><?= htmlspecialchars($job['job_name']) ?></div>
            <small class="text-muted">Material: <?= htmlspecialchars($job['material_name'] ?? 'N/A') ?></small><br>
            <small class="text-muted">Created: <?= htmlspecialchars($job['created_at']) ?></small>
          </div>
          <div class="d-flex align-items-center gap-1">
            <button class="btn btn-green btn-sm btn-assign" data-id="<?= $job['job_id'] ?>">
              <i class="bi bi-person-plus me-1"></i>Assign
            </button>
            <button class="btn btn-danger-custom btn-sm" onclick="deleteJob(<?= $job['job_id'] ?>,'<?= htmlspecialchars($job['job_name']) ?>')"><i class="bi bi-trash"></i></button>
          </div>
        </div>
        <?php endwhile; else: ?>
        <p class="text-muted text-center mt-3">No unassigned jobs found.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- RIGHT: Assigned -->
    <div class="panel-right">
      <div class="panel-header">
        <h6 class="fw-semibold text-dark mb-0"><i class="bi bi-clipboard-check text-primary me-2"></i>Assigned Cutting Jobs</h6>
      </div>
      <div class="search-box">
        <input type="text" id="searchAssigned" class="form-control form-control-sm" placeholder="🔍 Search by worker or job...">
      </div>
      <div class="panel-body" id="assignedList">
        <?php
        $assigned = $conn->query("
          SELECT cj.*, ja.assigned_date, ja.status AS job_status, w.full_name AS worker_name
          FROM cutting_jobs cj
          JOIN job_assignments ja ON ja.job_id = cj.job_id
          LEFT JOIN workers w ON w.worker_id = ja.worker_id
          ORDER BY ja.assigned_date DESC
        ");
        if ($assigned->num_rows > 0):
          while ($job = $assigned->fetch_assoc()):
            $badgeClass = match($job['job_status']) {
              'Completed' => 'bg-success text-white',
              'In Progress' => 'bg-primary text-white',
              default => 'bg-warning text-dark'
            };
        ?>
        <div class="job-card d-flex justify-content-between align-items-center flex-wrap" data-name="<?= strtolower($job['job_name']) ?>" data-worker="<?= strtolower($job['worker_name'] ?? '') ?>">
          <div>
            <h6 class="mb-1 fw-semibold text-dark small"><?= htmlspecialchars($job['job_name']) ?> <span class="text-muted">(#<?= $job['job_id'] ?>)</span></h6>
            <div class="small text-muted">Worker: <strong><?= htmlspecialchars($job['worker_name']) ?></strong> • Assigned: <strong><?= htmlspecialchars($job['assigned_date']) ?></strong></div>
            <span class="badge-status <?= $badgeClass ?>"><?= strtoupper($job['job_status'] ?? 'Pending') ?></span>
          </div>
          <div class="d-flex align-items-center gap-1">
            <button class="btn btn-green btn-sm btn-edit" data-id="<?= $job['job_id'] ?>">
              <i class="bi bi-arrow-repeat me-1"></i>Reassign
            </button>
            <button class="btn btn-danger-custom btn-sm" onclick="deleteJob(<?= $job['job_id'] ?>,'<?= htmlspecialchars($job['job_name']) ?>')"><i class="bi bi-trash"></i></button>
          </div>
        </div>
        <?php endwhile; else: ?>
        <p class="text-muted text-center mt-3">No assigned jobs found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Place modals OUTSIDE wrapper -->
<?php include(ROOT_PATH . "partials/cutting_jobs_modal.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// 🟢 ADD JOB
document.getElementById('addJobForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch('<?php echo FUNCTIONS_URL; ?>cutting_job_add.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      Swal.fire(d.status === 'success' ? 'Added!' : 'Error', d.message, d.status);
      if (d.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById('addJobModal')).hide();
        setTimeout(() => location.reload(), 1000);
      }
    })
    .catch(() => Swal.fire('Error', 'Unable to add job.', 'error'));
});


// 🧑‍🏭 ASSIGN BUTTON — OPEN MODAL AND PREFILL JOB
document.querySelectorAll('.btn-assign').forEach(btn => {
  btn.addEventListener('click', () => {
    const jobId = btn.getAttribute('data-id');
    const modal = new bootstrap.Modal(document.getElementById('assignJobModal'));
    document.querySelector('#assignJobModal select[name="job_id"]').value = jobId;
    modal.show();
  });
});


// 🧑‍🏭 ASSIGN JOB — SUBMIT
document.getElementById('assignJobForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch('<?php echo FUNCTIONS_URL; ?>cutting_job_assign.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      Swal.fire(d.status === 'success' ? 'Assigned!' : 'Error', d.message, d.status);
      if (d.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById('assignJobModal')).hide();
        setTimeout(() => location.reload(), 1000);
      }
    })
    .catch(() => Swal.fire('Error', 'Unable to assign job.', 'error'));
});


// 🔵 REASSIGN BUTTON — OPEN MODAL AND PREFILL DATA (✅ FIXED VERSION)
document.querySelectorAll('.btn-edit').forEach(btn => {
  btn.addEventListener('click', async () => {
    const jobId = btn.getAttribute('data-id');
    if (!jobId) return;

    try {
      // Step 1️⃣: Get main job info
      const jobRes = await fetch('<?php echo FUNCTIONS_URL; ?>global_get.php?type=cutting_jobs&id=' + jobId);
      const jobData = await jobRes.json();
      let job = Array.isArray(jobData) ? jobData[0] : jobData;

      if (!job || job.error) throw new Error('Failed to fetch job details.');

      // Step 2️⃣: Get assignment info (if exists)
      const assignRes = await fetch('<?php echo FUNCTIONS_URL; ?>global_get.php?type=job_assignments&id=' + jobId);
      const assignData = await assignRes.json();
      const assign = Array.isArray(assignData) && assignData.length ? assignData[0] : null;

      // Step 3️⃣: Merge data safely
      const workerId = assign?.worker_id || job.worker_id || '';
      const status = assign?.status || job.status || 'Pending';

      // Step 4️⃣: Prefill Modal
      document.getElementById('edit_job_id').value = job.job_id || '';
      document.getElementById('edit_job_name').value = job.job_name || '';
      document.getElementById('edit_job_due').value = job.due_date || '';
      document.getElementById('edit_job_status').value = status;

      const workerSelect = document.getElementById('edit_worker_id');
      if (workerId) workerSelect.value = workerId;

      // Step 5️⃣: Open Modal
      new bootstrap.Modal(document.getElementById('editJobModal')).show();

    } catch (err) {
      Swal.fire('Error', err.message || 'Unable to load job details.', 'error');
    }
  });
});


// 🔵 REASSIGN JOB — SUBMIT
document.getElementById('editJobForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch('<?php echo FUNCTIONS_URL; ?>cutting_job_update.php', { method: 'POST', body: fd })
    .then(r => r.json())
    .then(d => {
      Swal.fire(d.status === 'success' ? 'Updated!' : 'Error', d.message, d.status);
      if (d.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById('editJobModal')).hide();
        setTimeout(() => location.reload(), 1000);
      }
    })
    .catch(() => Swal.fire('Error', 'Unable to update job.', 'error'));
});


// 🗑️ DELETE JOB
function deleteJob(id, name) {
  Swal.fire({
    title: 'Delete this job?',
    text: 'Job: ' + name,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it',
    cancelButtonText: 'Cancel',
    confirmButtonColor: '#dc3545',
    cancelButtonColor: '#6c757d'
  }).then(r => {
    if (r.isConfirmed) {
      fetch('<?php echo FUNCTIONS_URL; ?>delete_cutting_jobs.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(id)
      })
      .then(r => r.json())
      .then(d => {
        Swal.fire(d.status === 'success' ? 'Deleted!' : 'Error', d.message, d.status);
        if (d.status === 'success') setTimeout(() => location.reload(), 1000);
      })
      .catch(() => Swal.fire('Error', 'Unable to delete job.', 'error'));
    }
  });
}


// 🧼 RESET MODALS WHEN CLOSED
['addJobModal', 'assignJobModal', 'editJobModal'].forEach(id => {
  const modalEl = document.getElementById(id);
  modalEl?.addEventListener('hidden.bs.modal', () => {
    const form = modalEl.querySelector('form');
    form?.reset();
  });
});


// 🔍 SEARCH FILTERS
function attachSearch(inputId,listId,selector){
  const input=document.getElementById(inputId);
  const list=document.getElementById(listId);
  input.addEventListener('input',()=>{
    const q=input.value.toLowerCase();
    list.querySelectorAll(selector).forEach(c=>{
      const n=c.dataset.name,w=c.dataset.worker||'';
      c.style.display=(n.includes(q)||w.includes(q))?'':'none';
    });
  });
}
attachSearch('searchUnassigned','unassignedList','.unassigned-item');
attachSearch('searchAssigned','assignedList','.job-card');
</script>


<?php include(ROOT_PATH . "partials/footer.php"); ?>
