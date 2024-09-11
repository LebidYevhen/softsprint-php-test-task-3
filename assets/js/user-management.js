$(document).ready(function () {
    initUserManagement();
});

function initUserManagement() {
    handleUserCreateUpdate();
    handleUserDelete();
}

function handleUserCreateUpdate() {
    const userCreateUpdateForm = $('.user-create-update-form');
    const userCreateUpdateModal = $('#userCreateUpdateModal');

    userCreateUpdateModalOpen(userCreateUpdateForm, userCreateUpdateModal);
    userCreateUpdateModalClose(userCreateUpdateForm, userCreateUpdateModal);

    submitUserCreateUpdateForm(userCreateUpdateForm);
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

function submitUserCreateUpdateForm(form) {
    $('.user-create-update-form').submit(function (e) {
        e.preventDefault();
        const formData = extractFormData(form, ['user_id', 'action', 'first_name', 'last_name', 'role_id']);
        formData.status = form.find("[name='status']").is(':checked');

        clearFormErrors(form);

        ajaxRequest(
            '/includes/process-request.php',
            formData,
            function (response) {
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
            function (xhr, status) {
                addStatusMessage('Could not reach server, please try again later.', 'alert-danger', '.user-create-update-form', 'prepend');
            },
        )
    });
}

function submitUserDeleteForm() {
    $('.user-delete-form').submit(function (e) {
        e.preventDefault();
        const deleteUserModal = $('#userDeleteModal');

        const formData = extractFormData($(this), ['action', 'user_id']);
        ajaxRequest(
            '/includes/process-request.php',
            formData,
            function (response) {
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
            function (xhr, status) {
                modalHide(deleteUserModal);
                addStatusMessage('Could not reach server, please try again later.', 'alert-danger');
            });
    });
}

function userCreateUpdateModalOpen(userCreateUpdateForm, userCreateUpdateModal) {
    $(document).on('click', '.user-create-btn, .users-table .user-update-link', function (e) {
        e.preventDefault();
        const userCreateUpdateAction = $(this).data('userCreateUpdateAction');

        switch (userCreateUpdateAction) {
            case 'user_create':
                handleUserCreateModalOpen(userCreateUpdateForm, userCreateUpdateModal, 'user_create', 'Add User', 'Add');
                break;
            case 'user_update':
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
                break;
        }
    });
}

function userCreateUpdateModalClose(userCreateUpdateForm, userCreateUpdateModal) {
    userCreateUpdateModal.on('hidden.bs.modal', function (e) {
        userCreateUpdateForm.trigger('reset');
        clearFormErrors(userCreateUpdateForm);
        setInputValue(getFormInputByName(userCreateUpdateForm, 'action'), '');
        setInputValue(getFormInputByName(userCreateUpdateForm, 'user_id'), '');
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
    setInputValue(getFormInputByName(userCreateUpdateForm, 'user_id'), userId);
    userCreateUpdateForm.find("button[type='submit']").text(submitText);
    userCreateUpdateModal.modal('show');
}

function handleUserDeleteModalOpen(userDeleteModal, userDeleteForm, user) {
    setInputValue(getFormInputByName(userDeleteForm, 'user_id'), user.id);
    setInputValue(getFormInputByName(userDeleteForm, 'action'), 'user_delete');
    userDeleteModal.find('.modal-body').html('Are you sure you want to delete the user?');
    userDeleteModal.modal('show');
}

function handleUserDeleteModalClose(userDeleteForm, userDeleteModal) {
    userDeleteModal.on('hidden.bs.modal', function (e) {
        userDeleteForm.trigger('reset');
        setInputValue(getFormInputByName(userDeleteForm, 'user_id'), '');
        setInputValue(getFormInputByName(userDeleteForm, 'action'), '');
    })
}

function getUserResponse(user_id) {
    let user = null;
    ajaxRequest(
        '/includes/process-request.php',
        {action: 'user_get', user_id,},
        function (response) {
            user = response;
        },
        function (xhr, status) {
            addStatusMessage('Could not reach server, please try again later.', 'alert-danger');
        },
        'GET',
        'json',
        false
    );

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
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey"
                         class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path
                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd"
                              d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                    </svg>
                </a>
                <a href="#"
                   class="d-inline-block text-decoration-none p-1 user-delete-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="grey" class="bi bi-trash"
                         viewBox="0 0 16 16">
                        <path
                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                        <path
                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                    </svg>
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
    const userStatusEl =  userRow.find('.user-status');
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
