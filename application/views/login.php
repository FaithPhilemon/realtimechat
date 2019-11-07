<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Sign Up</title>

        <!-- Font Icon -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fonts/material-icon/css/material-design-iconic-font.min.css">

        <!-- Main css -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
        <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
        <script src="<?php echo base_url('node_modules/socket.io-client/dist/socket.io.js'); ?>"></script>
    </head>
    <body>

        <div class="main">

            <!-- Sing in  Form -->
            <section class="sign-in">
                <div class="container">
                    <h1 class="site_heading">Codeignier Real Time Chat Application with Socket.IO</h1>

                    <div class="signin-content">
                        <div class="signin-form">
                            <h2 class="form-title">Login</h2>
                            <form class="register-form" id="login-form" autocomplete="off">
                                <div class="form-group">
                                    <label for="your_name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                    <input type="text" id="username" placeholder="Username"/>
                                </div>
                                <div class="form-group">
                                    <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                    <input type="password" id="password" placeholder="Password" autocomplete="off"/>
                                </div>

                                <div class="form-group form-button">
                                    <input type="button" id="login" class="form-submit" value="Log in" />
                                </div>
                                <div class="login_error">

                                </div>
                            </form>
                        </div>
                        <div class="signup-form">
                            <h2 class="form-title">Registration</h2>
                            <form class="register-form" autocomplete="off">
                                <div class="form-group">
                                    <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                    <input type="text" id="reg_username" placeholder="Username" autocomplete="off"/>
                                </div>
                                <div class="form-group">
                                    <label for="email"><i class="zmdi zmdi-email"></i></label>
                                    <input type="email" id="reg_email" placeholder="Your Email" autocomplete="off"/>
                                </div>
                                <div class="form-group">
                                    <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                    <input type="password" id="reg_pass" placeholder="Password" autocomplete="off"/>
                                </div>
                                <div class="form-group">
                                    <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                    <input type="password" id="re_pass" placeholder="Repeat your password" autocomplete="off"/>
                                </div>

                                <div class="form-group form-button">
                                    <input type="button" name="signup" id="signup" class="form-submit" value="Register"/>
                                </div>

                                <div class="reg_error">

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

        </div>

        <!-- JS -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/main.js"></script>
        <script>
            $(document).ready(function () {

                // Registration
                $(document).on("click", "#signup", function () {
                    username = $("#reg_username").val();
                    email = $("#reg_email").val();
                    password = $("#reg_pass").val();
                    con_password = $("#re_pass").val();
                    reg_error = $(".reg_error");
                    if (password === con_password) {
                        $.ajax({
                            type: 'POST',
                            url: '<?php echo base_url(); ?>save_user', // defined in route
                            ContentType: 'application/json',
                            data: {
                                username: username,
                                email: email,
                                password: password
                            },
                            success: function (response) {
                                result = JSON.parse(response);
                                if (result.status == 'failed') {
                                    reg_error.html(result.message);
                                } else if (result.status == 'success') {
                                    window.location.replace("<?php echo base_url(); ?>");
                                }
                            }
                        });
                    } else {
                        reg_error.html(
                                "<div class='error_msg'>" +
                                " Confirm password not matched " +
                                "</div>"
                                );
                    }
                });

                //
                $(document).on("click", "#login", function () {
                    username = $("#username").val();
                    password = $("#password").val();
                    login_error = $('.login_error');
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo base_url(); ?>loginCheck', // defined in route
                        ContentType: 'application/json',
                        data: {
                            username: username,
                            password: password
                        },
                        success: function (response) {
                            result = JSON.parse(response);
                            if (result.status == 'failed') {
                                login_error.html(result.message);
                            } else if (result.status == 'success') {
                                var socket = io.connect('http://' + window.location.hostname + ':3000');
                                socket.emit('enter_user', {
                                    user_id: result.user_id,
                                    username: result.username,
                                });
                                window.location.replace("<?php echo base_url(); ?>");
                            }
                        }
                    });
                });

            });
        </script>
    </body>
</html>