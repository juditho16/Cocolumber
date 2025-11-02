<?php
include_once(__DIR__ . "/../config/db_connection.php");
include_once(ROOT_PATH . "functions/session_verifier.php");
include_once(ROOT_PATH . "partials/header.php");
?>

<style>
  :root { --scrollbar-track:#e9f3e8; --scrollbar-thumb:#28a745; }
  body { overflow:hidden!important; }
  .inventory-header{font-size:1.25rem;font-weight:600;}
  .summary-row{display:flex;flex-wrap:wrap;gap:1rem;margin-bottom:1rem;}
  .summary-card{flex:1;min-width:200px;background:#fff;border:1px solid #e5e5e5;border-radius:6px;
    padding:1rem;display:flex;align-items:center;gap:1rem;cursor:pointer;transition:all .2s ease-in-out;}
  .summary-card:hover{background-color:#f8f9fa;transform:scale(1.02);}
  .summary-icon{font-size:1.75rem;}
  .layout-wrapper{display:flex;gap:1rem;height:calc(90vh - 190px);overflow:hidden;}
  .inventory-section{width:70%;padding:0;overflow-y:auto;scrollbar-width:thin;
    scrollbar-color:var(--scrollbar-thumb)var(--scrollbar-track);}
  .delivery-sidebar{width:30%;min-width:320px;border-left:1px solid #dee2e6;border-radius:6px;
    padding:.15rem;display:flex;flex-direction:column;background:transparent;overflow-y:auto;
    scrollbar-width:thin;scrollbar-color:var(--scrollbar-thumb)var(--scrollbar-track);}
  .inventory-section::-webkit-scrollbar,.delivery-sidebar::-webkit-scrollbar{width:10px;}
  .inventory-section::-webkit-scrollbar-thumb,.delivery-sidebar::-webkit-scrollbar-thumb{
    background-color:var(--scrollbar-thumb);border-radius:6px;}
  .inventory-section::-webkit-scrollbar-track,.delivery-sidebar::-webkit-scrollbar-track{
    background:var(--scrollbar-track);}
  .search-box{position:sticky;top:0;z-index:10;padding:.5rem 0;border-bottom:1px solid #dee2e6;}
  .inventory-card{border:1px solid #e5e5e5;border-radius:6px;padding:1rem 1.25rem;margin-bottom:10px;
    transition:all .2s ease-in-out;background-color:rgba(255,255,255,.5);}
  .inventory-card:hover{background-color:#f8f9fa;}
  .stock-badge{font-size:.7rem;font-weight:600;border-radius:4px;padding:.25rem .5rem;}
  .stock-line{display:flex;justify-content:space-between;align-items:center;font-size:.8rem;margin-top:4px;}
  .delivery-log{border:1px solid #e5e5e5;border-radius:6px;padding:.75rem 1rem;margin-bottom:.6rem;
    background-color:rgba(255,255,255,.7);transition:.2s;}
  .delivery-log:hover{background-color:rgba(245,245,245,.9);}
  .delivery-log .log-header{display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;}
  .delivery-log .log-title{font-weight:600;font-size:.9rem;color:#212529;}
  .delivery-log .badge-qty{font-size:.7rem;font-weight:600;background-color:#28a745;color:white;
    border-radius:10px;padding:.25rem .5rem;}
  .delivery-log .log-date{font-size:.8rem;color:#6c757d;margin-top:.25rem;text-align:right;}
  .action-btn{border:none;background:transparent;font-size:1rem;padding:.25rem .4rem;border-radius:4px;
    transition:.2s;}
  .action-btn:hover{background-color:rgba(0,0,0,.08);}
  @media(max-width:992px){.layout-wrapper{flex-direction:column;height:auto;}
    .inventory-section,.delivery-sidebar{width:100%;height:auto;}}
</style>

<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 px-2">
    <h4 class="inventory-header mb-0"><i class="bi bi-box-seam me-2"></i>Inventory Management</h4>
    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">
      <i class="bi bi-plus-lg me-1"></i>Add Item
    </button>
  </div>

  <!-- 📊 Summary -->
  <?php
  $totalItems     = $conn->query("SELECT COUNT(*) AS total FROM inventory_items")->fetch_assoc()['total'];
  $totalSuppliers = $conn->query("SELECT COUNT(*) AS total FROM suppliers")->fetch_assoc()['total'];
  $lumberCount    = $conn->query("SELECT COUNT(*) AS total FROM inventory_items WHERE type LIKE '%lumber%'")->fetch_assoc()['total'];
  ?>
  <div class="summary-row">
    <div class="summary-card" id="viewInventory">
      <i class="bi bi-boxes summary-icon text-primary"></i>
      <div><div class="fw-semibold text-dark">Total Items</div>
      <div class="text-muted small"><?= $totalItems ?> Inventory Entries</div></div>
    </div>

    <div class="summary-card" id="viewSuppliers" data-bs-toggle="modal" data-bs-target="#suppliersModal">
      <i class="bi bi-truck summary-icon text-success"></i>
      <div><div class="fw-semibold text-dark">Suppliers</div>
      <div class="text-muted small"><?= $totalSuppliers ?> Registered</div></div>
    </div>

    <div class="summary-card" data-bs-toggle="modal" data-bs-target="#lumberStockModal">
      <i class="bi bi-tree summary-icon text-warning"></i>
      <div><div class="fw-semibold text-dark">Lumber Stocks</div>
      <div class="text-muted small"><?= $lumberCount ?> Lumber Types</div></div>
    </div>
  </div>

  <div class="layout-wrapper">
    <!-- LEFT: INVENTORY -->
    <div class="inventory-section">
      <div class="search-box">
        <input type="text" id="searchInventory" class="form-control form-control-sm" placeholder="Search by wood name or type...">
      </div>

      <div id="inventoryList">
        <?php
        $inventory = $conn->query("SELECT * FROM inventory_items ORDER BY name ASC");
        if ($inventory->num_rows > 0):
          while ($item = $inventory->fetch_assoc()):
            $badgeClass = match($item['status']) {
              "In Stock" => "bg-success text-white",
              "Low Stock" => "bg-warning text-dark",
              "Out of Stock" => "bg-danger text-white",
              default => "bg-secondary text-white"
            };
            $stockIn = rand(10,50);
            $stockOut= rand(5,20);
        ?>
          <div class="inventory-card" data-id="<?= $item['inventory_id'] ?>" data-name="<?= strtolower($item['name']) ?>" data-type="<?= strtolower($item['type']) ?>">
            <div class="d-flex justify-content-between align-items-center">
              <h6 class="fw-semibold text-dark mb-0"><?= htmlspecialchars($item['name']) ?>
                <span class="text-muted">(#<?= $item['inventory_id'] ?>)</span></h6>
              <small class="text-muted fst-italic">Stock In: <span class="text-success fw-semibold"><?= $stockIn ?></span></small>
            </div>
            <div class="stock-line">
              <span class="small text-muted"><?= htmlspecialchars($item['type']) ?> • <?= htmlspecialchars($item['size']) ?> • <?= htmlspecialchars($item['unit']) ?></span>
              <span class="text-danger fw-semibold">Stock Out: <?= $stockOut ?></span>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-1 flex-wrap">
              <div><span class="stock-badge <?= $badgeClass ?>"><?= strtoupper($item['status']) ?></span>
                <small class="text-muted ms-2"><?= $item['quantity'] ?> Available</small></div>
              <div class="d-flex align-items-center gap-2">
                <button class="action-btn btn-edit text-success" data-id="<?= $item['inventory_id'] ?>"><i class="bi bi-pencil-square"></i></button>
                <button class="action-btn btn-delete text-danger" data-id="<?= $item['inventory_id'] ?>"><i class="bi bi-trash"></i></button>
              </div>
            </div>
          </div>
        <?php endwhile; else: ?>
          <p class="text-muted text-center mt-3">No inventory items found.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- RIGHT: DELIVERIES -->
    <div class="delivery-sidebar">
      <div class="search-box">
        <input type="text" id="searchLogs" class="form-control form-control-sm" placeholder="Search by supplier or wood type...">
      </div>
      <div id="logsList">
        <?php
        $logs = $conn->query("
          SELECT d.*, s.name AS supplier_name, i.name AS wood_name
          FROM deliveries d
          JOIN suppliers s ON s.supplier_id = d.supplier_id
          JOIN inventory_items i ON i.inventory_id = d.inventory_id
          ORDER BY d.delivery_date DESC LIMIT 20
        ");
        if ($logs->num_rows > 0):
          while ($log = $logs->fetch_assoc()):
        ?>
          <div class="delivery-log" data-supplier="<?= strtolower($log['supplier_name']) ?>" data-wood="<?= strtolower($log['wood_name']) ?>">
            <div class="log-header">
              <div class="log-title"><?= htmlspecialchars($log['wood_name']) ?> - <?= htmlspecialchars($log['supplier_name']) ?></div>
              <div class="badge-qty"><?= htmlspecialchars($log['quantity'])." ".htmlspecialchars($log['unit']) ?></div>
            </div>
            <div class="log-date"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars($log['delivery_date']) ?></div>
          </div>
        <?php endwhile; else: ?>
          <p class="text-muted text-center mt-3">No recent deliveries found.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php include(ROOT_PATH . "partials/inventory_modal.php"); ?>

<!-- Suppliers Modal -->
<div class="modal fade" id="suppliersModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-truck me-2"></i>Registered Suppliers</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <table class="table table-striped align-middle">
          <thead><tr><th>#</th><th>Supplier Name</th><th>Contact Person</th><th>Contact Info</th></tr></thead>
          <tbody>
          <?php
          $suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");
          if ($suppliers->num_rows > 0):
            $i=1;
            while($s=$suppliers->fetch_assoc()):
              echo "<tr><td>{$i}</td><td>{$s['name']}</td><td>{$s['contact_person']}</td><td>{$s['email_or_phone']}</td></tr>";
              $i++;
            endwhile;
          else:
            echo "<tr><td colspan='4' class='text-center text-muted'>No suppliers found.</td></tr>";
          endif;
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Scroll to inventory list
  document.getElementById('viewInventory').onclick = ()=>document.querySelector('.inventory-section').scrollIntoView({behavior:'smooth'});

  // Search filters
  const invCards=[...document.querySelectorAll('.inventory-card')];
  document.getElementById('searchInventory').addEventListener('input',e=>{
    const q=e.target.value.toLowerCase();
    invCards.forEach(c=>{const n=c.dataset.name,t=c.dataset.type;c.style.display=(n.includes(q)||t.includes(q))?"":"none";});
  });
  const logs=[...document.querySelectorAll('.delivery-log')];
  document.getElementById('searchLogs').addEventListener('input',e=>{
    const q=e.target.value.toLowerCase();
    logs.forEach(c=>{const s=c.dataset.supplier,w=c.dataset.wood;c.style.display=(s.includes(q)||w.includes(q))?"":"none";});
  });

  // Edit
  document.querySelectorAll('.btn-edit').forEach(btn=>{
    btn.onclick=()=>{
      const id=btn.dataset.id;
      fetch('<?php echo FUNCTIONS_URL; ?>global_get.php?type=inventory&id='+id)
        .then(r=>r.json())
        .then(data=>{
          if(data.error){Swal.fire('Error',data.error,'error');return;}
          document.getElementById('inventory_id').value=data.inventory_id;
          document.getElementById('edit_name').value=data.name;
          document.getElementById('edit_type').value=data.type;
          document.getElementById('edit_size').value=data.size;
          document.getElementById('edit_quantity').value=data.quantity;
          document.getElementById('edit_unit').value=data.unit;
          document.getElementById('edit_status').value=data.status;
          document.getElementById('edit_remarks').value=data.remarks??'';
          new bootstrap.Modal('#inventoryModal').show();
        }).catch(()=>Swal.fire('Error','Unable to fetch data','error'));
    };
  });

  // Add
  document.getElementById('addItemForm').onsubmit=e=>{
    e.preventDefault();
    const fd=new FormData(e.target);
    fetch('<?php echo FUNCTIONS_URL; ?>inventory_add.php',{method:'POST',body:fd})
      .then(r=>r.json())
      .then(d=>{
        Swal.fire(d.status==='success'?'Added!':'Error',d.message,d.status);
        if(d.status==='success'){bootstrap.Modal.getInstance('#addItemModal').hide();setTimeout(()=>location.reload(),1000);}
      });
  };

  // Update
  document.getElementById('inventoryForm').onsubmit=e=>{
    e.preventDefault();
    const fd=new FormData(e.target);
    fetch('<?php echo FUNCTIONS_URL; ?>inventory_update.php',{method:'POST',body:fd})
      .then(r=>r.json())
      .then(d=>{
        Swal.fire(d.status==='success'?'Updated!':'Error',d.message,d.status);
        if(d.status==='success'){bootstrap.Modal.getInstance('#inventoryModal').hide();setTimeout(()=>location.reload(),1000);}
      });
  };

  // Delete
  document.querySelectorAll('.btn-delete').forEach(btn=>{
    btn.onclick=()=>{
      const id=btn.dataset.id;
      Swal.fire({title:'Delete this item?',text:'This action cannot be undone.',icon:'warning',showCancelButton:true,
        confirmButtonText:'Yes, delete it',cancelButtonText:'Cancel'}).then(res=>{
          if(res.isConfirmed){
            fetch('<?php echo FUNCTIONS_URL; ?>inventory_delete.php',{method:'POST',
              headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'id='+id})
            .then(r=>r.json())
            .then(d=>{
              Swal.fire(d.status==='success'?'Deleted!':'Error',d.message,d.status);
              if(d.status==='success'){document.querySelector(`.inventory-card[data-id="${id}"]`).remove();}
            });
          }
        });
    };
  });
</script>

<?php include(ROOT_PATH . "partials/footer.php"); ?>
