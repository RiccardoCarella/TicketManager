$(function() {
    $(
        $("#login-form").on("submit", function() {
            var username = $("#login-username").val();
            var password = $("#login-password").val();

            $.ajax({
                method: "post",
                url: "api/login.php",
                data: {
                    username: username,
                    password: password,
                    fromAjax: 1
                },
                success: function(result) {
                    if (result.success) {
                        window.location.href = "issues.php";
                    } else {
                        alert(result.msg);
                    }
                }
            });
            return false;
        })
    );
});
