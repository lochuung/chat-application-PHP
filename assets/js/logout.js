$(document).ready(function () {
    $('#logout').click(function () {
        $.ajax({
            url: 'action.php',
            method: 'post',
            data: {
                id: $('#login_user_id').val(),
                action: 'leave',
            },
            success: function(data) {
                console.log(data);
                data = JSON.parse(data);
                if (data.status === 1) {
                    location.href = 'index.php'
                }
            },
            error: function (e) {
                console.log(e);
            }
        })
    });
})