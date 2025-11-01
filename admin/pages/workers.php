<?php include_once(__DIR__ . "/../partials/header.php"); ?>

<div class="container-fluid py-4 px-0"><!-- Removed horizontal padding -->

  <!-- 🔹 Header Section -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 px-3">
    <h4 class="fw-semibold mb-0"><i class="bi bi-people-fill me-2"></i>Workers</h4>
    <div class="d-flex align-items-center gap-2" style="flex: 1; max-width: 400px;">
      <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search worker name...">
      <button class="btn btn-success btn-sm px-3 py-2 shadow-sm"
        style="border-radius:8px; width:160px; white-space:nowrap;">
  <i class="bi bi-person-plus-fill me-1"></i> Add Worker
</button>


    </div>
  </div>

  <?php
  // 🧠 Dummy Data
  $workers = [
    [
      "name" => "Juan Dela Cruz",
      "status" => "active",
      "age" => 32,
      "address" => "Purok 4, Mahayag, Zamboanga del Sur",
      "contract_no" => "CNT-2025-001",
      "jobs" => [
        ["job_name" => "2x3 Lumber Cutting", "status" => "pending", "date" => "2025-11-01"],
        ["job_name" => "2x4 Lumber Cutting", "status" => "done", "date" => "2025-10-28"],
      ]
    ],
    [
      "name" => "Pedro Santos",
      "status" => "inactive",
      "age" => 40,
      "address" => "Barangay Upper Mahayag",
      "contract_no" => "CNT-2025-002",
      "jobs" => [
        ["job_name" => "Plywood Cutting", "status" => "done", "date" => "2025-10-29"],
        ["job_name" => "2x6 Lumber Sizing", "status" => "pending", "date" => "2025-11-02"],
      ]
    ],
    [
      "name" => "Mario Lopez",
      "status" => "active",
      "age" => 29,
      "address" => "Sitio Proper, Mahayag",
      "contract_no" => "CNT-2025-003",
      "jobs" => [
        ["job_name" => "2x2 Lumber Cutting", "status" => "pending", "date" => "2025-11-02"],
        ["job_name" => "Wood Plank Sizing", "status" => "done", "date" => "2025-10-30"],
      ]
    ],
    [
      "name" => "Carlos Ramos",
      "status" => "active",
      "age" => 36,
      "address" => "Mahayag Town Center",
      "contract_no" => "CNT-2025-004",
      "jobs" => [
        ["job_name" => "Coco Lumber Sizing", "status" => "done", "date" => "2025-10-25"]
      ]
    ],
  ];
  ?>

  <!-- 🧱 Worker Cards Grid -->
  <div class="row g-3 mx-0" id="workersContainer"><!-- Removed outer margins -->
    <?php foreach ($workers as $worker): ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 worker-card" data-name="<?= strtolower($worker['name']) ?>">
        <div class="card border-0 shadow-sm h-100 small" style="font-size: 0.85rem;">
          <div class="card-body pb-3">

            <!-- 🧍 Worker Header -->
            <div class="d-flex align-items-center mb-3">
              <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width:45px;height:45px;">
                <i class="bi bi-person-fill fs-4 text-secondary"></i>
              </div>
              <div>
                <h6 class="fw-semibold mb-0"><?= htmlspecialchars($worker['name']) ?></h6>
                <span class="badge <?= $worker['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                  <?= ucfirst($worker['status']) ?>
                </span>
              </div>
            </div>

            <!-- 🧾 Personal Info -->
            <div class="mb-3" style="line-height: 1.4;">
              <div><strong>Age:</strong> <?= htmlspecialchars($worker['age']) ?></div>
              <div><strong>Address:</strong> <?= htmlspecialchars($worker['address']) ?></div>
              <div><strong>Contract #:</strong> <?= htmlspecialchars($worker['contract_no']) ?></div>
            </div>

            <!-- 📋 Cutting Jobs Section -->
            <div class="card border-light bg-light-subtle shadow-none">
              <div class="card-body py-2 px-3">
                <h6 class="fw-semibold mb-2">Cutting Jobs</h6>
                <?php foreach ($worker['jobs'] as $job): ?>
                  <div class="mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                      <span class="fw-semibold"><?= htmlspecialchars($job['job_name']) ?></span>
                      <span class="badge <?= $job['status'] == 'done' ? 'bg-success' : 'bg-warning text-dark' ?>" style="font-size: 0.7rem;">
                        <?= ucfirst($job['status']) ?>
                      </span>
                    </div>
                    <small class="text-muted">- <?= date("F j, Y", strtotime($job['date'])) ?></small>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- 🔎 Client-side search -->
<script>
  document.getElementById('searchInput').addEventListener('input', function() {
    const query = this.value.toLowerCase();
    document.querySelectorAll('.worker-card').forEach(card => {
      card.style.display = card.dataset.name.includes(query) ? '' : 'none';
    });
  });
</script>

<?php include_once(__DIR__ . "/../partials/footer.php"); ?>
