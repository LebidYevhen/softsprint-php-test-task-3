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
                addStatusMessage(`User with id ${userId} updated.`, 'alert-success');
                getUserResponse(userId, 'replace');
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

function openUserDeleteModal(user_id) {
    ajaxRequest(
        '/includes/modal/delete-user.php',
        {user_id},
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
    $('.users-table').find(`.user-table-row[data-user-id='${userId}']`).replaceWith(response);
}