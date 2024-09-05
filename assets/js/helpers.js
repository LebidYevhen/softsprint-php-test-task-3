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
    $.each(errors, function (field, message) {
        form.find(`[name='${field}']`).after(`<p class="text-danger">${message}</p>`).addClass('border-danger');
    });
}

function clearFormErrors(form) {
    form.find('.text-danger').remove();
    removeElClassByClassName(form, 'border-danger');
}

function addStatusMessage(message, type, container = '.status-messages', location = 'append') {
    const statusElement = $('<div></div>').addClass(`alert ${type}`).text(message);
    container = $(container);

    if (location === 'prepend') {
        container.prepend(statusElement);
    } else if (location === 'append') {
        container.append(statusElement);
    }

    scrollTo(container);

    setTimeout(() => {
        statusElement.remove();
    }, 5000);
}

function scrollTo(element, speed = 500) {
    $('html, body').animate({
        scrollTop: element.offset().top
    }, speed);
}

function modalHide(modal) {
    modal.modal('hide');
}