function extractFormData(form, fields) {
    let formData = {};
    fields.forEach(field => {
        formData[field] = form.find(`[name='${field}']`).val();
    });
    return formData;
}

function removeElClassByClassName(parent, className) {
    parent.find(`.${className}`).removeClass(className);
}

function ajaxRequest(url, data, success, error, type = 'POST', dataType = 'json', async = true, encode = true) {
    return $.ajax({
        url,
        data,
        success,
        error,
        type,
        dataType,
        async,
        encode
    });
}

function highlightFormErrors(form, errors) {
    $.each(errors.fields, function (index, {fieldName, fieldValue, message}) {
        form.find(`[name='${fieldName}']`).after(`<p class="text-danger">${message}</p>`).addClass('border-danger');
    });
}

function clearFormErrors(form) {
    form.find('.text-danger').remove();
    form.find('.alert').remove();
    removeElClassByClassName(form, 'border-danger');
}

function addStatusMessage(message, type, container = '.status-messages', location = 'append', timeout = true, scroll = true) {
    const statusElement = $('<div></div>').addClass(`alert ${type}`).text(message);
    container = $(container);

    clearStatusMessage(container, type);

    if (location === 'prepend') {
        container.prepend(statusElement);
    } else if (location === 'append') {
        container.append(statusElement);
    }

    if (scroll) {
        scrollTo(container);
    }

    if (timeout) {
        setTimeout(() => {
            statusElement.remove();
        }, 5000);
    }
}

function clearStatusMessage(container, type) {
    container.find(`.alert.${type}`).remove();
}

function scrollTo(element, speed = 500) {
    $('html, body').animate({
        scrollTop: element.offset().top
    }, speed);
}

function modalHide(modal) {
    modal.modal('hide');
}

function setInputValue(input, value, isCheckbox = false, isChecked = false) {
    if (isCheckbox) {
        input.prop('checked', isChecked);
    } else {
        input.val(value);
    }
}

function getFormInputByName(form, name) {
    return form.find(`input[name='${name}']`);
}