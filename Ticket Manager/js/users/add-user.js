$(function() {
    $("#form-add-user").on("submit", function() {
        var username = $("#username").val();
        var password = $("#password").val();
        var role = $("#role").val();
        var name = $("#name").val();
        var surname = $("#surname").val();
        var mobile = $("#mobile").val();
        var email = $("#email").val();

        $.ajax({
            method: "post",
            url: "api/users/add-user.php",
            data: {
                name: name,
                surname: surname,
                email: email,
                mobile: mobile,
                username: username,
                password: password,
                role: role,
                fromAjax: 1
            },
            success: function(result) {
                if (result.success) {
                    alert(result.msg);
                    $("#add-modal").modal("hide");
                    window.location.reload();
                } else {
                    alert(result.msg);
                }
            }
        });
    });
});
