$(document).ready(function () {
    initBulkAction();
});

function initBulkAction() {
    selectAllUsers();
    userSelection();
    submitBulkActionsForm();
}

function submitBulkActionsForm() {
    $('.bulk-actions-form').submit(function (e) {
        e.preventDefault();

        if (handleBulkActionsFormValidation($(this))) {
            const formData = extractFormData($(this), ['action', 'bulk_action']);
            const usersIds = getSelectedUserIds();
            formData.users_ids = usersIds.join(',');

            setInputValue(getFormInputByName($(this), 'users_ids'), usersIds);

            $.ajax({
                method: 'POST',
                url: '/includes/process-request.php',
                data: formData,
                dataType: 'json',
                success: function (response) {
                    if (!response.status) {
                        addStatusMessage(response.error.message, 'alert-danger');
                        return;
                    }

                    const status = formData.bulk_action === 'set_active' ? 'active' : '';

                    usersIds.forEach(userId => {
                        changeUserRowStatusClass(userId, status);
                        changeUserRoleName();
                    });

                    addStatusMessage('User statuses have been successfully changed.', 'alert-success');

                    setInputValue(getFormInputByName($(this), 'users_ids'), '');
                    $('.user-selection-checkbox, .select-all-users-checkbox').prop('checked', false);
                    $(this).trigger('reset');
                },
                error: function (xhr, status) {
                    console.error("Error fetching modal content:", status);
                }
            });
        }
    })
}

function handleBulkActionsFormValidation(form) {
    const bulkAction = form.find('.bulk-actions-select').val();
    const selectedUsers = $('.user-selection-checkbox:checked').length;
    const bulkActionModal = $('#bulkActionsModal');

    if (!bulkAction && selectedUsers === 0) {
        bulkActionModal.modal('show').find('.modal-body').html('No action and no users were selected.');
        return false;
    }

    if (!bulkAction && selectedUsers > 0) {
        bulkActionModal.modal('show').find('.modal-body').html('No action selected.');
        return false;
    }

    if (bulkAction && selectedUsers === 0) {
        bulkActionModal.modal('show').find('.modal-body').html('No users selected.');
        return false;
    }

    if (bulkAction === 'delete' && selectedUsers > 0) {
        bulkActionsUserDeleteModalOpen($('.user-delete-form'), $('#userDeleteModal'), getSelectedUserIds());
        return false;
    }

    return bulkAction && selectedUsers > 0;
}

function bulkActionsUserDeleteModalOpen(userDeleteForm, userDeleteModal, usersIds) {
    setInputValue(getFormInputByName(userDeleteForm, 'id'), usersIds);
    setInputValue(getFormInputByName(userDeleteForm, 'action'), 'user_delete_multiple');
    userDeleteModal.find('.modal-body').html('Are you sure you want to delete <b>these users</b>?');
    userDeleteModal.modal('show');
}

function selectAllUsers() {
    $('.select-all-users-checkbox').on('click', function (e) {
        $('.user-selection-checkbox').prop('checked', $(this).prop('checked'))
    })
}

function userSelection() {
    $('.users-table').on('click', '.user-selection-checkbox', function (e) {
        const selectAllUsersCheckbox = $('.select-all-users-checkbox');
        if (!$(this).prop('checked') && selectAllUsersCheckbox.prop('checked')) {
            selectAllUsersCheckbox.prop('checked', false);
        }
        if ($('.user-selection-checkbox').length === getSelectedUserIds().length) {
            selectAllUsersCheckbox.prop('checked', true)
        }
    })
}

function getSelectedUserIds() {
    return $('.user-selection-checkbox:checked').map(function () {
        return $(this).val();
    }).get();
}
