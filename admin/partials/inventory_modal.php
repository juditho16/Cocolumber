<?php
/**
 * inventory_modal.php
 * Includes modals for Add, Edit, and Lumber Stocks overview.
 */
?>

<!-- 🟢 Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add New Inventory Item</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="addItemForm" method="POST">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Item Name</label>
              <input type="text" class="form-control" name="name" placeholder="e.g., Mahogany" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Type</label>
              <input type="text" class="form-control" name="type" placeholder="e.g., Lumber" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Size / Dimension</label>
              <input type="text" class="form-control" name="size" placeholder="e.g., 2x2x8" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Quantity</label>
              <input type="number" class="form-control" name="quantity" min="0" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Unit</label>
              <input type="text" class="form-control" name="unit" placeholder="pcs / cu.ft" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Status</label>
              <select class="form-select" name="status" required>
                <option value="In Stock">In Stock</option>
                <option value="Low Stock">Low Stock</option>
                <option value="Out of Stock">Out of Stock</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Remarks (optional)</label>
              <textarea class="form-control" name="remarks" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancel</button>
          <button type="submit" class="btn btn-success"><i class="bi bi-save2 me-1"></i>Save Item</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🟠 Edit Inventory Modal -->
<div class="modal fade" id="inventoryModal" tabindex="-1" aria-labelledby="inventoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Inventory Item</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="inventoryForm" method="POST">
        <div class="modal-body">
          <input type="hidden" id="inventory_id" name="inventory_id">

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Item Name</label>
              <input type="text" class="form-control" id="edit_name" name="name" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Type</label>
              <input type="text" class="form-control" id="edit_type" name="type" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Size / Dimension</label>
              <input type="text" class="form-control" id="edit_size" name="size" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Quantity</label>
              <input type="number" class="form-control" id="edit_quantity" name="quantity" min="0" required>
            </div>
            <div class="col-md-3">
              <label class="form-label fw-semibold">Unit</label>
              <input type="text" class="form-control" id="edit_unit" name="unit" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Status</label>
              <select class="form-select" id="edit_status" name="status" required>
                <option value="In Stock">In Stock</option>
                <option value="Low Stock">Low Stock</option>
                <option value="Out of Stock">Out of Stock</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Remarks</label>
              <textarea class="form-control" id="edit_remarks" name="remarks" rows="2"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="bi bi-save2 me-1"></i>Update Item</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🌲 Lumber Stock Modal (for summary card click) -->
<div class="modal fade" id="lumberStockModal" tabindex="-1" aria-labelledby="lumberStockModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="bi bi-tree me-2"></i>Lumber Stock Overview</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr class="text-center">
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Size</th>
                <th scope="col">Quantity</th>
                <th scope="col">Unit</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $lumbers = $conn->query("SELECT * FROM inventory_items WHERE type LIKE '%lumber%' ORDER BY name ASC");
              if ($lumbers->num_rows > 0):
                $i = 1;
                while ($lb = $lumbers->fetch_assoc()):
                  $badge = match($lb['status']) {
                    "In Stock" => "badge bg-success",
                    "Low Stock" => "badge bg-warning text-dark",
                    "Out of Stock" => "badge bg-danger",
                    default => "badge bg-secondary"
                  };
                  echo "<tr>
                          <td class='text-center'>{$i}</td>
                          <td>{$lb['name']}</td>
                          <td>{$lb['type']}</td>
                          <td>{$lb['size']}</td>
                          <td class='text-center'>{$lb['quantity']}</td>
                          <td class='text-center'>{$lb['unit']}</td>
                          <td class='text-center'><span class='{$badge}'>{$lb['status']}</span></td>
                        </tr>";
                  $i++;
                endwhile;
              else:
                echo "<tr><td colspan='7' class='text-center text-muted'>No lumber stock data available.</td></tr>";
              endif;
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
// 🔄 Reset forms when closed
['inventoryModal', 'addItemModal'].forEach(id => {
  const modalEl = document.getElementById(id);
  modalEl?.addEventListener('hidden.bs.modal', () => {
    const form = modalEl.querySelector('form');
    form?.reset();
  });
});
</script>
