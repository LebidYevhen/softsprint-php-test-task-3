<!-- Add User Modal -->
<div class="modal fade" id="userCreateUpdateModal" tabindex="-1" aria-labelledby="userCreateUpdateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-5" id="userCreateUpdateModalLabel"></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-4">
                <?php include_once FORM_PATH . '/user-create-update-form.php'; ?>
            </div>
        </div>
    </div>
</div>