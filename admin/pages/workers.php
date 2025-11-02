<?php
include_once(__DIR__ . "/../config/db_connection.php");
include_once(__DIR__ . "/../functions/session_verifier.php");
include_once(__DIR__ . "/../partials/header.php");
?>

<style>
  /* 🎨 Styling Fixes */
  #searchInput {
    height: 38px;
    font-size: 0.9rem;
    border-radius: 6px;
    border: 1px solid #ced4da;
    transition: all 0.2s ease;
  }
  #searchInput:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.2);
  }

  .btn-add-worker {
    height: 38px;
    font-size: 0.9rem;
    border-radius: 6px;
  }

  .worker-card .card {
    border: 2px solid rgba(40, 167, 69, 0.25) !important;
    border-radius: 8px !important;
    transition: all 0.2s ease;
  }

  .worker-card .card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0 8px rgba(40, 167, 69, 0.3);
  }

  .worker-card small {
    font-size: 0.85rem;
  }
</style>

<div class="container-fluid py-4 px-0">

  <!-- Header -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 px-3">
    <h4 class="fw-semibold mb-0"><i class="bi bi-people-fill me-2"></i>Workers</h4>
    <div class="d-flex align-items-center gap-2" style="flex: 1; max-width: 450px;">
      <input type="text" id="searchInput" class="form-control shadow-sm" placeholder="🔍 Search worker name...">
      <button class="btn btn-success btn-add-worker px-3 shadow-sm"
              data-bs-toggle="modal"
              data-bs-target="#addWorkerModal">
        <i class="bi bi-person-plus-fill me-1"></i> Add Worker
      </button>
    </div>
  </div>

  <!-- Worker Cards -->
  <div class="row g-3 mx-0" id="workersContainer">
    <?php
    $query = "
      SELECT w.*, 
             GROUP_CONCAT(c.job_name SEPARATOR '|') AS job_names,
             GROUP_CONCAT(c.status SEPARATOR '|') AS job_statuses,
             GROUP_CONCAT(c.due_date SEPARATOR '|') AS job_dates
      FROM workers w
      LEFT JOIN job_assignments ja ON ja.worker_id = w.worker_id
      LEFT JOIN cutting_jobs c ON c.job_id = ja.job_id
      GROUP BY w.worker_id
      ORDER BY w.full_name ASC
    ";
    $workers = $conn->query($query);

    if ($workers->num_rows > 0):
      while ($w = $workers->fetch_assoc()):
        $jobNames = $w['job_names'] ? explode('|', $w['job_names']) : [];
        $jobStatuses = $w['job_statuses'] ? explode('|', $w['job_statuses']) : [];
        $jobDates = $w['job_dates'] ? explode('|', $w['job_dates']) : [];
    ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 worker-card" data-name="<?= strtolower($w['full_name']) ?>" data-id="<?= $w['worker_id'] ?>">
        <div class="card border-0 shadow-sm h-100 small">
          <div class="card-body pb-3">
            <div class="d-flex justify-content-between align-items-start mb-3">
              <div class="d-flex align-items-center">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                  <i class="bi bi-person-fill fs-4 text-secondary"></i>
                </div>
                <div>
                  <h6 class="fw-semibold mb-0"><?= htmlspecialchars($w['full_name']) ?></h6>
                  <span class="badge <?= strtolower($w['status']) == 'active' ? 'bg-success' : 'bg-secondary' ?>">
                    <?= htmlspecialchars($w['status']) ?>
                  </span>
                </div>
              </div>

              <div class="d-flex align-items-center gap-1">
                <button class="btn btn-sm btn-outline-primary btn-edit"
                        title="Edit Worker"
                        data-bs-toggle="modal"
                        data-bs-target="#editWorkerModal"
                        data-id="<?= $w['worker_id'] ?>"
                        data-name="<?= htmlspecialchars($w['full_name']) ?>"
                        data-age="<?= htmlspecialchars($w['age']) ?>"
                        data-address="<?= htmlspecialchars($w['address']) ?>"
                        data-contract="<?= htmlspecialchars($w['contract_no']) ?>"
                        data-status="<?= htmlspecialchars($w['status']) ?>">
                  <i class="bi bi-pencil"></i>
                </button>

                <button class="btn btn-sm btn-outline-danger btn-delete" title="Delete Worker" data-id="<?= $w['worker_id'] ?>">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>

            <div class="mb-3" style="line-height: 1.4;">
              <div><strong>Age:</strong> <?= htmlspecialchars($w['age']) ?></div>
              <div><strong>Address:</strong> <?= htmlspecialchars($w['address']) ?></div>
              <div><strong>Contract #:</strong> <?= htmlspecialchars($w['contract_no']) ?></div>
            </div>

            <div class="card border-light bg-light-subtle shadow-none">
              <div class="card-body py-2 px-3">
                <h6 class="fw-semibold mb-2">Cutting Jobs</h6>
                <?php if (count($jobNames) > 0): ?>
                  <?php foreach ($jobNames as $i => $jn): ?>
                    <div class="mb-2">
                      <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold"><?= htmlspecialchars($jn) ?></span>
                        <span class="badge <?= strtolower($jobStatuses[$i]) == 'completed' ? 'bg-success' : (strtolower($jobStatuses[$i]) == 'in progress' ? 'bg-warning text-dark' : 'bg-secondary') ?>" style="font-size: 0.7rem;">
                          <?= htmlspecialchars($jobStatuses[$i]) ?>
                        </span>
                      </div>
                      <small class="text-muted">- <?= date("F j, Y", strtotime($jobDates[$i])) ?></small>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <small class="text-muted fst-italic">No assigned jobs.</small>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p class="text-muted text-center mt-4">No workers found.</p>
    <?php endif; ?>
  </div>
</div>

<!-- 📦 Include Worker Modals -->
<?php include(__DIR__ . '/../partials/worker_modal.php'); ?>

<!-- ✅ Bootstrap + SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// 🔍 Live Search
document.getElementById("searchInput").addEventListener("input", e => {
  const q = e.target.value.toLowerCase();
  document.querySelectorAll(".worker-card").forEach(c => {
    c.style.display = c.dataset.name.includes(q) ? "" : "none";
  });
});

// 🟢 Add Worker
document.getElementById("addWorkerForm")?.addEventListener("submit", e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch("../admin/functions/worker_add.php", { method: "POST", body: fd })
    .then(r => r.json()).then(d => {
      Swal.fire(d.status === 'success' ? 'Added!' : 'Error', d.message, d.status);
      if (d.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById("addWorkerModal")).hide();
        setTimeout(() => location.reload(), 1000);
      }
    }).catch(() => Swal.fire("Error", "Unable to add worker.", "error"));
});

// ✏️ Edit Worker Prefill (works with modal)
document.querySelectorAll(".btn-edit").forEach(btn => {
  btn.addEventListener("click", () => {
    document.getElementById("edit_worker_id").value = btn.dataset.id;
    document.getElementById("edit_worker_name").value = btn.dataset.name;
    document.getElementById("edit_worker_age").value = btn.dataset.age;
    document.getElementById("edit_worker_address").value = btn.dataset.address;
    document.getElementById("edit_worker_contract").value = btn.dataset.contract;
    document.getElementById("edit_worker_status").value = btn.dataset.status;
  });
});

// 🔵 Update Worker
document.getElementById("editWorkerForm")?.addEventListener("submit", e => {
  e.preventDefault();
  const fd = new FormData(e.target);
  fetch("../admin/functions/worker_update.php", { method: "POST", body: fd })
    .then(r => r.json()).then(d => {
      Swal.fire(d.status === 'success' ? 'Updated!' : 'Error', d.message, d.status);
      if (d.status === 'success') {
        bootstrap.Modal.getInstance(document.getElementById("editWorkerModal")).hide();
        setTimeout(() => location.reload(), 1000);
      }
    }).catch(() => Swal.fire("Error", "Unable to update worker.", "error"));
});

// 🔴 Delete Worker
document.querySelectorAll(".btn-delete").forEach(btn => {
  btn.onclick = () => {
    const id = btn.dataset.id;
    Swal.fire({
      title: "Delete this worker?",
      text: "This action cannot be undone.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it",
      cancelButtonText: "Cancel",
      confirmButtonColor: "#dc3545",
      cancelButtonColor: "#6c757d"
    }).then(res => {
      if (res.isConfirmed) {
        fetch("../admin/functions/worker_delete.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id=" + encodeURIComponent(id)
        })
        .then(r => r.json()).then(d => {
          Swal.fire(d.status === 'success' ? 'Deleted!' : 'Error', d.message, d.status);
          if (d.status === 'success') document.querySelector(`.worker-card[data-id="${id}"]`)?.remove();
        });
      }
    });
  };
});
</script>

<?php include_once(__DIR__ . "/../partials/footer.php"); ?>
