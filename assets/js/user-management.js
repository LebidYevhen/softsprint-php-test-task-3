$(document).ready(function () {
    initUserManagement();
});

function initUserManagement() {
    userCreateUpdateModalOpen($('.user-create-update'));
    userDeleteModalOpen($('.user-delete-link'));
    submitUserCreateUpdateForm();
    submitUserDeleteForm();
}

function submitUserCreateUpdateForm() {
    $('.user-create-update-form').submit(function (e) {
        e.preventDefault();
        const form = $(this);

        const formData = extractFormData(form, ['id', 'action', 'first_name', 'last_name', 'role_id']);
        formData.status = form.find("[name='status']").is(':checked');

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
                    form.trigger('reset');
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
                        addStatusMessage(`User ${response.user.first_name} ${response.user.last_name} has been successfully deleted.`, 'alert-success');
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

function userCreateUpdateModalOpen(element) {
    element.on('click', function (e) {
        e.preventDefault();
        const userId = $(this).data('userId');
        handleUserCreateUpdateModalOpen(userId);
    })
}

function handleUserCreateUpdateModalOpen(userId) {
    let user = {
        first_name: null,
        last_name: null,
        role_id: null,
        status: null,
    };

    let modalTitle = 'Add User';
    let submitText = 'Add';
    let action = 'user_create';

    if (userId) {
        const response = getUserResponse(userId);
        if (response.status) {
            user = {...response.user};
            modalTitle = 'Update User';
            submitText = 'Update';
            action = 'user_update';
        } else {
            addStatusMessage(response.error.message, 'alert-danger');
            return;
        }
    }

    populateUserCreateUpdateForm(user, action, modalTitle, submitText)

    const userCreateUpdateModal = $('#userCreateUpdateModal');
    userCreateUpdateModal.modal('show');
}

function populateUserCreateUpdateForm(user, action, modalTitle, submitText) {
    const userCreateUpdateForm = $('.user-create-update-form');

    setInputValue(getFormInputByName(userCreateUpdateForm, 'first_name'), user.first_name);
    setInputValue(getFormInputByName(userCreateUpdateForm, 'last_name'), user.last_name);
    setInputValue(getFormInputByName(userCreateUpdateForm, 'status'), user.status, true, user.status);
    setInputValue(getFormInputByName(userCreateUpdateForm, 'action'), action);
    setInputValue(getFormInputByName(userCreateUpdateForm, 'id'), user.id);
    userCreateUpdateForm.find("select[name='role_id']").val(user.role_id);
    userCreateUpdateForm.find('.modal-title').html(modalTitle);
    userCreateUpdateForm.find("button[type='submit']").text(submitText);
}

function userDeleteModalOpen(element) {
    element.on('click', function (e) {
        e.preventDefault();

        const userTableRow = $(this).closest('.user-table-row')
        const userId = userTableRow.data('userId');
        const response = getUserResponse(userId);

        if (!response.status) {
            addStatusMessage(response.error.message, 'alert-danger');
            return;
        }

        handleUserDeleteModalOpen(response.user);
    });
}

function handleUserDeleteModalOpen(user) {
    const userDeleteModal = $('#userDeleteModal');
    const userDeleteForm = $('.user-delete-form');

    setInputValue(getFormInputByName(userDeleteForm, 'id'), user.id);
    setInputValue(getFormInputByName(userDeleteForm, 'action'), 'user_delete');
    userDeleteModal.find('.modal-body').html(`Are you sure you want to delete user <b>${user.first_name} ${user.last_name}</b>?`);
    userDeleteModal.modal('show');
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
    $('.users-table tbody').append(getUserRowHtml(user));
    userCreateUpdateModalOpen(getUserRow(user.id).find('.user-create-update'));
}

function getUserRowHtml(user) {
    return $(`
    <tr data-user-id="${user.id}" class="user-table-row">
        <td class="align-middle">
            <label class="form-check-label">
                <input class="form-check-input user-id user-selection-checkbox" type="checkbox"
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
                <a href="#" class="d-inline-block text-decoration-none p-1 border-end border-dark user-create-update" data-user-id="${user.id}">
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

    const newUserRow = getUserRow(user.id);
    userCreateUpdateModalOpen(newUserRow.find('.user-create-update'));
    userDeleteModalOpen(newUserRow.find('.user-delete-link'));

    let checkbox = usersTable.find(`.user-selection-checkbox[value='${user.id}']`);
    checkbox.prop('checked', userCheckboxVal);
}

function removeUserRow(userId) {
    getUserRow(userId).remove();
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
