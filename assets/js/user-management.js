$(document).ready(function () {
    initUserManagement();
});

function initUserManagement() {
    handleUserAdd();
    handleUserUpdate();
    handleUserDelete();
}

function handleUserAdd() {
    const userAddForm = $('.user-add-form');

    const addUserModal = $('#addUserModal');
    addUserModal.on('hidden.bs.modal', function (e) {
        userAddForm.trigger('reset');
        clearFormErrors(userAddForm);
    })

    userAddForm.submit(function (e) {
        e.preventDefault();
        submitUserAddForm(userAddForm);
    });
}

function handleUserUpdate() {
    $('.users-table').on('click', '.user-update-link', function (e) {
        e.preventDefault();

        const userTableRow = $(this).closest('.user-table-row')
        const userId = userTableRow.data('userId');
        openUserUpdateModal(userId);

        const userUpdateForm = $('.user-update-form');

        userUpdateForm.submit(function (e) {
            e.preventDefault();
            submitUserUpdateForm(userUpdateForm, userTableRow, userId);
        });
    });
}

function handleUserDelete() {
    $('.users-table').on('click', '.user-delete-link', function (e) {
        e.preventDefault();

        const userTableRow = $(this).closest('.user-table-row')
        const userId = userTableRow.data('userId');
        openUserDeleteModal(userId);

        const userDeleteForm = $('.user-delete-form');

        userDeleteForm.submit(function (e) {
            e.preventDefault();
            submitUserDeleteForm(userDeleteForm, userTableRow, userId);
        });
    });
}

function submitUserAddForm(form) {
    const formData = extractFormData(form, ['action', 'first_name', 'last_name', 'role_id']);
    formData.status = form.find("[name='status']").is(':checked');

    clearFormErrors(form);

    ajaxRequest(
        '/includes/process-request.php',
        formData,
        function (response) {
            if (!response.status) {
                highlightFormErrors(form, response.error);
            } else {
                form.trigger('reset');
                addStatusMessage(`User with id ${response.id} has been created.`, 'alert-success');
                modalHide($('#addUserModal'));
                getUserResponse(response.id);
                $('.select-all-users-checkbox').prop('checked', false);
            }
        },
        function (xhr, status) {
            addStatusMessage('Could not reach server, please try again later.', 'alert-danger', '.user-add-form', 'prepend');
        },
    )
}

function submitUserUpdateForm(form, userTableRow, userId) {
    const updateUserModal = $('#updateUserModal');

    const formData = extractFormData(form, ['user_id', 'action', 'first_name', 'last_name', 'role_id']);
    formData.status = form.find("[name='status']").is(':checked');

    clearFormErrors(form);

    ajaxRequest(
        '/includes/process-request.php',
        formData,
        function (response) {
            if (!response.status) {
                highlightFormErrors(form, response.error);
            } else {
                modalHide(updateUserModal);
                let message = `User with id ${userId} was not updated. The field values have not been changed.`;
                let type = 'alert-info';
                if (response.updatedFields?.length) {
                    message = `User with id ${userId} was updated. Updated fields: ${response.updatedFields.join(', ')}.`;
                    type = 'alert-success';
                    getUserResponse(userId, 'replace');
                }
                addStatusMessage(message, type);
            }
        },
        function (xhr, status) {
            modalHide(updateUserModal);
            addStatusMessage('Could not reach server, please try again later.', 'alert-danger');
        });

}

function submitUserDeleteForm(form, userTableRow, userId) {
    const deleteUserModal = $('#deleteUserModal');

    const formData = extractFormData(form, ['action', 'user_id']);

    ajaxRequest(
        '/includes/process-request.php',
        formData,
        function (response) {
            if (!response.status) {
                modalHide(deleteUserModal);
                addStatusMessage(response.error, 'alert-danger');
            } else {
                modalHide(deleteUserModal);
                addStatusMessage(`User with id ${userId} deleted.`, 'alert-success');
                userTableRow.remove();
            }
        },
        function (xhr, status) {
            modalHide(deleteUserModal);
            addStatusMessage('Could not reach server, please try again later.', 'alert-danger');
        });

}

function openUserDeleteModal(user_id, multiple = false) {
    ajaxRequest(
        '/includes/modal/delete-user.php',
        {user_id, multiple},
        function (response) {
            $('body').prepend(response);
            const deleteUserModal = $('#deleteUserModal');
            deleteUserModal.modal('show');
            deleteUserModal.on('hidden.bs.modal', function (e) {
                deleteUserModal.remove()
            })
        },
        function (xhr, status) {
            console.error("Error fetching modal content:", status);
        },
        'POST',
        'html',
        false
    )
}

function openUserUpdateModal(user_id) {
    ajaxRequest(
        '/includes/modal/update-user.php',
        {user_id},
        function (response) {
            $('body').prepend(response);
            const updateUserModal = $('#updateUserModal');
            updateUserModal.modal('show');
            updateUserModal.on('hidden.bs.modal', function (e) {
                updateUserModal.remove()
            });
        },
        function (xhr, status) {
            console.error("Error fetching modal content:", status);
        },
        'POST',
        'html',
        false
    )
}

function getUserResponse(user_id, method = 'render') {
    ajaxRequest(
        '/includes/process-request.php',
        {action: 'user_get', user_id,},
        function (response) {
            if (method === 'render') {
                renderUserRow(response);
            } else {
                replaceUserRow(user_id, response);
            }
        },
        function (xhr, status) {
            console.error("Error fetching modal content:", status);
        },
        'GET',
        'html'
    )
}

function renderUserRow(response) {
    $('.users-table').prepend(response)
}

function replaceUserRow(userId, response) {
    const usersTable = $('.users-table');
    let updatedRow = usersTable.find(`.user-table-row[data-user-id='${userId}']`);
    let checkbox = usersTable.find(`.user-selection-checkbox[value='${userId}']`);

    if (updatedRow.length) {
        updatedRow.replaceWith(response);
        let updatedCheckbox = usersTable.find(`.user-selection-checkbox[value='${userId}']`);
        updatedCheckbox.prop('checked', checkbox.prop('checked'))
    }
}