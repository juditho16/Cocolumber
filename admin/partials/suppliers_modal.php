<?php
/**
 * suppliers_modal.php
 * Contains modals for Add, Edit Supplier, and Add Delivery.
 */
?>

<!-- 🟢 Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="bi bi-plus-lg me-2"></i>Add Supplier</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="addSupplierForm" method="POST">
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label fw-semibold">Supplier Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Contact Person</label>
            <input type="text" name="contact_person" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Address</label>
            <input type="text" name="address" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Email / Phone</label>
            <input type="text" name="email_or_phone" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🟠 Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Supplier</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="editSupplierForm" method="POST">
        <div class="modal-body">
          <input type="hidden" id="edit_supplier_id" name="supplier_id">
          <div class="mb-2">
            <label class="form-label fw-semibold">Supplier Name</label>
            <input type="text" id="edit_supplier_name" name="name" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Contact Person</label>
            <input type="text" id="edit_supplier_contact" name="contact_person" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Address</label>
            <input type="text" id="edit_supplier_address" name="address" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Email / Phone</label>
            <input type="text" id="edit_supplier_email" name="email_or_phone" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- 🚚 Add Delivery Log Modal -->
<div class="modal fade" id="addDeliveryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="bi bi-truck me-2"></i>Add Delivery Log</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addDeliveryForm" method="POST">
        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label fw-semibold">Supplier</label>
            <select name="supplier_id" class="form-select" required>
              <option value="">Select Supplier</option>
              <?php
              $suppliers = $conn->query("SELECT supplier_id, name FROM suppliers ORDER BY name ASC");
              while ($s = $suppliers->fetch_assoc()):
                echo "<option value='{$s['supplier_id']}'>{$s['name']}</option>";
              endwhile;
              ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Inventory Item</label>
            <select name="inventory_id" class="form-select" required>
              <option value="">Select Item</option>
              <?php
              $items = $conn->query("SELECT inventory_id, name FROM inventory_items ORDER BY name ASC");
              while ($i = $items->fetch_assoc()):
                echo "<option value='{$i['inventory_id']}'>{$i['name']}</option>";
              endwhile;
              ?>
            </select>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Unit</label>
            <input type="text" name="unit" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Delivery Date</label>
            <input type="date" name="delivery_date" class="form-control" required>
          </div>
          <div class="mb-2">
            <label class="form-label fw-semibold">Remarks (optional)</label>
            <textarea name="remarks" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Save Log</button>
        </div>
      </form>
    </div>
  </div>
</div>
