$(document).ready(function () {
    initBulkAction();
});

function initBulkAction() {
    selectAllUsers();
    userSelection();
    bulkActionSubmitHandler();
}

function selectAllUsers() {
    $('.select-all-users-checkbox').on('click', function (e) {
        $('.user-selection-checkbox').prop('checked', $(this).prop('checked'))
    })
}

function userSelection() {
    $('.user-selection-checkbox').on('click', function (e) {
        const selectAllUsersCheckbox = $('.select-all-users-checkbox');
        if (!$(this).prop('checked') && selectAllUsersCheckbox.prop('checked')) {
            selectAllUsersCheckbox.prop('checked', false);
        }
    })
}

function bulkActionSubmitHandler() {
    $('.bulk-action-form').submit(function (e) {
        e.preventDefault();
        if (validateBulkActionForm($(this))) {
            submitBulkActionForm($(this));
        }
    })
}

function validateBulkActionForm(form) {
    const bulkAction = form.find('.bulk-action-select').val();
    const selectedUsers = $('.user-selection-checkbox:checked').length;
    const bulkActionModal = $('#bulkActionModal');

    if (bulkAction && selectedUsers > 0) {
        return true;
    }

    if (!bulkAction && selectedUsers > 0) {
        showModal(bulkActionModal, 'No action selected.');
    }

    if (bulkAction && selectedUsers === 0) {
        showModal(bulkActionModal, 'No users selected.');
    }

    return false;
}

function submitBulkActionForm(form) {
    const formData = extractFormData(form, ['action', 'bulk_action']);
    const usersIds = getSelectedUserIds();
    formData.users_ids = usersIds;

    ajaxRequest(
        '/includes/process-request.php',
        formData,
        function (response) {
            if (!response.status) {

            } else {
                form.trigger('reset');
                uncheckCheckboxes();
                switch (formData.bulk_action) {
                    case 'delete':
                        updateUserRow(usersIds, removeUserRow);
                        addStatusMessage(`Users with ids: ${usersIds.join(', ')} have been removed.`, 'alert-success');
                        break;
                    case 'set_active':
                        updateUserRow(usersIds, changeUserRowStatusClass, 'add');
                        addStatusMessage(`Users status with ids: ${usersIds.join(', ')} have been changed.`, 'alert-success');
                        break;
                    case 'set_not_active':
                        updateUserRow(usersIds, changeUserRowStatusClass, 'remove');
                        addStatusMessage(`Users status with ids: ${usersIds.join(', ')} have been changed.`, 'alert-success');
                        break;
                }
            }
        },
        function (xhr, status) {
            console.error("Error fetching modal content:", status);
        }
    )
}

function getSelectedUserIds() {
    return $('.user-selection-checkbox:checked').map(function () {
        return $(this).val();
    }).get();
}

function showModal(modal, bodyHtml) {
    modal.modal('show').find('.modal-body').html(bodyHtml);
}

function removeUserRow(userId) {
    $('.users-table').find(`.user-table-row[data-user-id='${userId}']`).remove();
}

function changeUserRowStatusClass(userId, type) {
    $('.users-table').find(`.user-table-row[data-user-id='${userId}']`).find('.user-status')[type + 'Class']('active');
}

function uncheckCheckboxes() {
    $('.user-selection-checkbox, .select-all-users-checkbox').prop('checked', false);
}

function updateUserRow(usersIds, callback, type = null) {
    $.each(usersIds, function (index, value) {
        callback(value, type);
    });
}

