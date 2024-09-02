$(document).ready(function () {
  selectAllUsers();
  userSelection();
  bulkActionConfirm();
});

function selectAllUsers() {
  $('.select-all-users-checkbox').on('click', function (e) {
    $('.user-selection-checkbox').prop('checked', $(this).prop('checked'))
  })
}

function userSelection() {
  const selectAllUsers = $('.select-all-users-checkbox');
  $('.user-selection-checkbox').on('click', function (e) {
    if (!$(this).prop('checked') && selectAllUsers.prop('checked')) {
      selectAllUsers.prop('checked', false);
    }
  })
}

function bulkActionConfirm() {
  const bulkActionConfirm = $('.bulk-action-confirm');
  bulkActionConfirm.on('click', function (e) {
    e.preventDefault();

    const bulkActionConfirmWarningModal = $('#bulkActionConfirmModal');
    const bulkActionSelect = $(this).closest('.bulk-action-form').find('.bulk-action-select');

    console.log(bulkActionSelect)
    if (bulkActionSelect.val() && !isUserSelectionEmpty()) {
      bulkActionConfirmWarningModal.modal('show');
      bulkActionConfirmWarningModal.find('.modal-body').text('No users were selected')
    }

    if (!bulkActionSelect.val() && isUserSelectionEmpty()) {
      bulkActionConfirmWarningModal.modal('show');
      bulkActionConfirmWarningModal.find('.modal-body').text('No action was selected')
    }
  })
}

function isUserSelectionEmpty() {
  return $('.user-selection-checkbox:checked').length;
}