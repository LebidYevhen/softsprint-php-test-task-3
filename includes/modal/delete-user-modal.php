<!-- Delete User Modal -->
<div class="modal fade" id="userDeleteModal" tabindex="-1" aria-labelledby="userDeleteModalLabel"
     aria-hidden="true">
    <div class="modal-inner">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-5" id="userDeleteModalLabel">Delete Confirmation</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <?php include_once FORM_PATH . '/user-delete-form.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>