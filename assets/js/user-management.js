$(document).ready(function () {
    initUserManagement();
});

function initUserManagement() {
    handleUserDelete();
    userCreateUpdateModalOpen();
    userCreateUpdateModalClose();
    submitUserCreateUpdateForm();
}

function handleUserDelete() {
    const userDeleteModal = $('#userDeleteModal');
    const userDeleteForm = $('.user-delete-form');

    handleUserDeleteModalClose(userDeleteForm, userDeleteModal);

    $('.users-table').on('click', '.user-delete-link', function (e) {
        e.preventDefault();

        const userTableRow = $(this).closest('.user-table-row')
        const userId = userTableRow.data('userId');
        const response = getUserResponse(userId);

        if (!response.status) {
            addStatusMessage(response.error.message, 'alert-danger');
            return;
        }

        handleUserDeleteModalOpen(userDeleteModal, userDeleteForm, response.user);
    });

    submitUserDeleteForm();
}

function submitUserCreateUpdateForm() {
    $('.user-create-update-form').submit(function (e) {
        e.preventDefault();
        const formData = extractFormData($(this), ['id', 'action', 'first_name', 'last_name', 'role_id']);
        formData.status = $(this).find("[name='status']").is(':checked');

        clearFormErrors($(this));

        $.ajax({
            method: 'POST',
            url: '/includes/process-request.php',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (!response.status) {
                    highlightFormErrors(form, response.error);
                    addStatusMessage(response.error.message, 'alert-danger', '.user-create-update-form .modal-body', 'prepend', false, false);
                } else {
                    response.user.role_name = getSelectRoleNameById(response.user.role_id);
                    switch (formData.action) {
                        case 'user_create':
                            addUserRow(response.user);
                            $('.select-all-users-checkbox').prop('checked', false);
                            addStatusMessage('User successfully created.', 'alert-success');
                            break;
                        case 'user_update':
                            replaceUserRow(response.user);
                            addStatusMessage('User successfully updated.', 'alert-success');
                            break;
                    }
                    $(this).trigger('reset');
                    modalHide($('#userCreateUpdateModal'));
                }
            },
            error: function (xhr, status) {
                addStatusMessage('Could not reach server, please try again later.', 'alert-danger', '.user-create-update-form', 'prepend');
            },
        });
    });
}

function submitUserDeleteForm() {
    $('.user-delete-form').submit(function (e) {
        e.preventDefault();
        const deleteUserModal = $('#userDeleteModal');

        const formData = extractFormData($(this), ['action', 'id']);

        $.ajax({
            method: 'POST',
            url: '/includes/process-request.php',
            data: formData,
            dataType: 'json',
            success: function (response) {
                if (!response.status) {
                    modalHide(deleteUserModal);
                    addStatusMessage(response.error.message, 'alert-danger');
                } else {
                    if (formData.action === 'user_delete') {
                        removeUserRow(response.user.id);
                        addStatusMessage('The user has been successfully deleted.', 'alert-success');
                    }
                    if (formData.action === 'user_delete_multiple') {
                        response.users_ids.forEach(userId => {
                            removeUserRow(userId);
                        });
                        addStatusMessage('Users have been successfully deleted.', 'alert-success');
                    }

                    modalHide(deleteUserModal);
                }
            },
            error: function (xhr, status) {
                modalHide(deleteUserModal);
                addStatusMessage('Could not reach server, please try again later.', 'alert-danger');
            }
        });
    });
}

function userCreateUpdateModalOpen() {
    const userCreateUpdateForm = $('.user-create-update-form');
    const userCreateUpdateModal = $('#userCreateUpdateModal');


    $(document).on('click', '.user-create-btn, .users-table .user-update-link', function (e) {
        e.preventDefault();
        const userCreateUpdateAction = $(this).data('userCreateUpdateAction');

        if (userCreateUpdateAction === 'user_create') {
            handleUserCreateModalOpen(userCreateUpdateForm, userCreateUpdateModal, 'user_create', 'Add User', 'Add');
        }

        if (userCreateUpdateAction === 'user_update') {
            const userTableRow = $(this).closest('.user-table-row')
            const userId = userTableRow.data('userId');

            const response = getUserResponse(userId);
            if (!response.status) {
                addStatusMessage(response.error.message, 'alert-danger');
            } else {
                setInputValue(getFormInputByName(userCreateUpdateForm, 'first_name'), response.user.first_name);
                setInputValue(getFormInputByName(userCreateUpdateForm, 'last_name'), response.user.last_name);
                setInputValue(getFormInputByName(userCreateUpdateForm, 'status'), response.user.status, true, response.user.status);
                userCreateUpdateForm.find("select[name='role_id']").val(response.user.role_id);
                handleUserUpdateModalOpen(userCreateUpdateForm, userCreateUpdateModal, 'user_update', 'Update User', 'Update', userTableRow.data('userId'));
            }
        }
    });
}

function userCreateUpdateModalClose() {
    const userCreateUpdateForm = $('.user-create-update-form');
    const userCreateUpdateModal = $('#userCreateUpdateModal');

    userCreateUpdateModal.on('hidden.bs.modal', function (e) {
        userCreateUpdateForm.trigger('reset');
        clearFormErrors(userCreateUpdateForm);

        setInputValue(getFormInputByName(userCreateUpdateForm, 'action'), '');

        deleteHiddenInput(userCreateUpdateForm, 'id');

        userCreateUpdateForm.find("button[type='submit']").text('');
    })
}

function handleUserCreateModalOpen(userCreateUpdateForm, userCreateUpdateModal, action, modalTitle, submitText) {
    userCreateUpdateModal.find('.modal-title').html(modalTitle);
    setInputValue(getFormInputByName(userCreateUpdateForm, 'action'), action);
    userCreateUpdateForm.find("button[type='submit']").text(submitText);
    userCreateUpdateModal.modal('show');
}

function handleUserUpdateModalOpen(userCreateUpdateForm, userCreateUpdateModal, action, modalTitle, submitText, userId) {
    userCreateUpdateModal.find('.modal-title').html(modalTitle);
    setInputValue(getFormInputByName(userCreateUpdateForm, 'action'), action);

    createHiddenInput(userCreateUpdateForm, 'id');
    setInputValue(getFormInputByName(userCreateUpdateForm, 'id'), userId);

    userCreateUpdateForm.find("button[type='submit']").text(submitText);
    userCreateUpdateModal.modal('show');
}

function handleUserDeleteModalOpen(userDeleteModal, userDeleteForm, user) {
    setInputValue(getFormInputByName(userDeleteForm, 'id'), user.id);
    setInputValue(getFormInputByName(userDeleteForm, 'action'), 'user_delete');
    userDeleteModal.find('.modal-body').html(`Are you sure you want to delete user <b>${user.first_name} ${user.last_name}</b>?`);
    userDeleteModal.modal('show');
}

function handleUserDeleteModalClose(userDeleteForm, userDeleteModal) {
    userDeleteModal.on('hidden.bs.modal', function (e) {
        userDeleteForm.trigger('reset');
        setInputValue(getFormInputByName(userDeleteForm, 'id'), '');
        setInputValue(getFormInputByName(userDeleteForm, 'action'), '');
    })
}

function getUserResponse(id) {
    let user = null;

    $.ajax({
        method: 'GET',
        url: '/includes/process-request.php',
        data: {action: 'user_get', id},
        dataType: 'json',
        async: false,
        success: function (response) {
            user = response;
        },
        function(xhr, status) {
            addStatusMessage('Could not reach server, please try again later.', 'alert-danger');
        },
    });

    return user;
}

function addUserRow(user) {
    $('.users-table').find('tbody').append(getUserRowHtml(user));
}

function getUserRowHtml(user) {
    return $(`
    <tr data-user-id="${user.id}" class="user-table-row">
        <td class="align-middle">
            <label class="form-check-label">
                <input class="form-check-input user-id user-selection-checkbox" type="checkbox" name="checkBoxesArray[]"
                       value="${user.id}">
            </label>
        </td>
        <td class="align-middle user-fullname">${user.first_name} ${user.last_name}</td>
        <td class="align-middle user-role">${user.role_name}</td>
        <td class="text-center align-middle user-status ${user.status ? 'active' : ''}">
            <span class="user-status-indicator"></span>
        </td>
        <td class="text-center align-middle">
            <div class="border border-dark rounded d-inline-block">
                <a href="#" class="d-inline-block text-decoration-none p-1 border-end border-dark user-update-link" data-user-create-update-action="user_update">
                    <i class="bi bi-pencil-square text-secondary"></i>
                </a>
                <a href="#"
                   class="d-inline-block text-decoration-none p-1 user-delete-link">
                    <i class="bi bi-trash-fill text-secondary"></i>
                </a>
            </div>
        </td>
    </tr>
    `);
}

function getUserRow(userId) {
    return $('.users-table').find(`.user-table-row[data-user-id='${userId}']`);
}

function replaceUserRow(user) {
    let usersTable = $('.users-table');
    let userRow = getUserRow(user.id);
    let userCheckboxVal = isUserSelected(user.id);

    userRow.replaceWith(getUserRowHtml(user));
    let checkbox = usersTable.find(`.user-selection-checkbox[value='${user.id}']`);
    checkbox.prop('checked', userCheckboxVal);
}

function removeUserRow(userId) {
    $('.users-table').find(`.user-table-row[data-user-id='${userId}']`).remove();
}

function changeUserRowStatusClass(userId, status) {
    const userRow = getUserRow(userId);
    const userStatusEl = userRow.find('.user-status');
    status === 'active' ? userStatusEl.addClass('active') : userStatusEl.removeClass('active');
}

function changeUserRoleName(userId, roleName) {
    getUserRow(userId).find('.user-role').text(roleName);
}

function isUserSelected(id) {
    return $(`.user-selection-checkbox[value="${id}"]`).is(':checked')
}

function getSelectRoleNameById(id) {
    return $('#role_id').find(`option[value=${id}]`).data('roleName');
}

function createHiddenInput(form, name) {
    const input = $('<input>').attr({
        type: 'hidden',
        name: name,
    });
    form.append(input);
}

function deleteHiddenInput(form, name) {
    form.find(`input[name=${name}]`).remove();
}
