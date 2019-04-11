$('a.ajax-send').on('click', function (event) {
    let send = true
    const offerId = $(this).data('offer-id')


    if ($(this).hasClass('confirm-first')) {
        send = confirm('Вы уверены?')
    }

    if (send) {
        ajaxSend(
            $(this).attr('href'),
            $(this).data('method'),
            {
                offerId
            },
            function (response) {
                $('#row-offer-id-' + offerId).remove();
                alert('Вы успешно совершили сделку!');
            },
            function (response) {
                if (response.status === 403) {
                    alert('Недостаточно средств');
                }
            }
        )
    }

    return false;
})

function ajaxSend(url, method, data, success, error) {
    $.ajax({
        url: url,
        method: method,
        data: data,
        success: success,
        error: error
    });
}

$('.show-tooltip').tooltip();