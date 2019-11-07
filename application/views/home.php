<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <!--  This file has been downloaded from https://bootdey.com  -->
        <!--  All snippets are MIT license https://bootdey.com/license -->
        <title>Real Time Chat </title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/chat.css">
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url('node_modules/socket.io-client/dist/socket.io.js'); ?>"></script>
    </head>
    <body>
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        <div class="row bootstrap snippets">

            <div class="row chat_section">

                <div class="col-sm-5 col-sm-offset-2 col-xs-6 col-xs-offset-1">
                    <!-- DIRECT CHAT DANGER -->
                    <div class="box box-danger direct-chat direct-chat-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Public Chat</h3>

                            <div class="box-tools pull-right">
                                <a id="logout" class="btn btn-box-tool"><i class="fa fa-times"></i></a>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Conversations are loaded here -->
                            <span id="load_more">Load Previous ...</span>
                            <div class="direct-chat-messages">
                                <!-- Message. Default to the left -->
                                <?php foreach ($messages as $m) { ?>
                                    <?php
                                    if ($this->session->userdata('user_id') == $m->user_id) {
                                        $right = 'right';
                                    } else {
                                        $right = '';
                                    }
                                    ?>
                                    <div data-chat_id="<?php echo $m->chat_id; ?>" class="direct-chat-msg <?php echo $right; ?>">
                                        <div class="direct-chat-info clearfix">
                                            <span class="direct-chat-name pull-left"><?php echo $m->username; ?></span>
                                            <span class="direct-chat-timestamp pull-right"><?php echo return_datetime($m->created_at); ?></span>
                                        </div>
                                        <img class="direct-chat-img" src="<?php echo base_url(); ?>media/images/user/user_1.jpg" alt="<?php echo $m->username ?>">
                                        <div class="direct-chat-text">
                                            <?php echo $m->chat_message; ?>
                                            <?php if (file_exists($m->chat_image)) { ?>
                                                <span class="chat_img"><img src="<?php echo base_url(); ?><?php echo $m->chat_image; ?>" /></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>


                            </div>
                            <!--/.direct-chat-messages-->

                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <form class="msg_form" id="submit_msg">
                                <div class="input-group">
                                    <span class="msg_img" id="select_image"><i class="fa fa-paperclip" aria-hidden="true"></i></span>
                                    <input type="text" class="msg_text" id="message" name="message" placeholder="Type Message ..." class="form-control">
                                    <input type="file" name="image" id="image" class="hide" />
                                    <span class="input-group-btn">
                                        <button type="submit" id="send_message" class="btn btn-success btn-flat">Send</button>
                                    </span>
                                </div>
                                <div class="preview_area">
                                    <img id="img_preview" src="#" alt="" />
                                </div>
                                <div class="response_msg">

                                </div>
                                <input type="file" name="image_clone" id="image_clone" class="hide" />
                            </form>
                        </div>
                        <!-- /.box-footer-->
                    </div>
                </div>

                <div class="col-sm-3 col-xs-3">
                    <div class="active_user_section">
                        <div class="header box-header with-border">
                            <h3 class="box-title">Users Active</h3>
                        </div>
                        <div class="user_list">
                            <ul>
                                <?php foreach ($active_users as $au) { ?>
                                    <li data-user_id="<?php echo $au->user_id; ?>"><a href="javascript:void(0)"><?php echo $au->username; ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/.direct-chat -->
            </div>
        </div>


        <script>

            var socket = io.connect('http://' + window.location.hostname + ':3000');
            socket.on('enter_user', function (data) {
                $(".user_list ul").prepend('<li data-user_id="' + data.user_id + '"><a href="javascript:void(0)">' + data.username + '</a></li>');
            });
            socket.on('logout_user', function (data) {
                $('li[data-user_id=' + data.user_id + ']').remove();
            });

            socket.on('send_msg', function (data) {
                uid = '<?php echo $this->session->userdata('user_id'); ?>';
                msg = '';
                chat_img = '';
                if (data.chat_image != '') {
                    chat_img = '<span class="chat_img"><img src="' + data.chat_image + '" /></span>';
                }
                right = '';
                if (uid == data.user_id) {
                    right = 'right';
                }
                msg = '<div data-chat_id="' + data.chat_id + '" class="direct-chat-msg ' + right + '">' +
                        '<div class="direct-chat-info clearfix">' +
                        '<span class="direct-chat-name pull-left">' + data.username + '</span>' +
                        '<span class="direct-chat-timestamp pull-right">' + data.created_at + '</span>' +
                        '</div>' +
                        '<img class="direct-chat-img" src="<?php echo base_url(); ?>media/images/user/user_1.jpg" alt="' + data.username + '">' +
                        '<div class="direct-chat-text">' +
                        data.chat_message + chat_img +
                        '</div>' +
                        '</div>';



                $('.direct-chat-messages').append(msg);
                $('.direct-chat-messages').scrollTop($('.direct-chat-messages')[0].scrollHeight);
            });

            $(document).ready(function () {
                // Load More
                $(document).on('click', '#load_more', function () {
                    msg_box = $('.direct-chat-messages');
                    chat_id = $('.direct-chat-messages').children('.direct-chat-msg').first().data('chat_id');
                    $.ajax({
                        type: 'post',
                        url: '<?php echo base_url(); ?>previous_msg',
                        ContentType: 'application/json',
                        data: {
                            chat_id: chat_id
                        },
                        success: function (response) {
                            console.log(response);
                            result = JSON.parse(response);
                            if (result['status'] == 'success') {
                                msg_box.prepend(result['output']);
                            } else if (result['status'] == 'failed') {
                                msg_box.html('<span style="color: red;">Noting found</span>');
                            }
                        }
                    });
                });

                //Logout User
                $(document).on('click', '#logout', function () {
                    user_id = '<?php echo $this->session->userdata('user_id'); ?>'
                    var socket = io.connect('http://' + window.location.hostname + ':3000');
                    socket.emit('logout_user', {
                        user_id: user_id,
                    });
                    window.location.replace("<?php echo base_url(); ?>logout");
                });

                //Send Message
                $(document).on('submit', '#submit_msg', function (e) {
                    e.preventDefault();
                    error_msg = $('.response_msg');
                    error_msg.html('');
                    $.ajax({
                        url: '<?php echo base_url(); ?>submit_msg',
                        type: "post",
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        cache: false,
                        async: false,
                        success: function (response) {
                            result = JSON.parse(response);
                            if (result['status'] != 'success') {
                                if (result['status'] == 'path_error') {
                                    error_msg.html('<div class="error_msg">Path not found</div>');
                                } else if (result['status'] == 'size_error') {
                                    error_msg.html('<div class="error_msg">Max file size error</div>');
                                } else if (result['status'] == 'file_error') {
                                    error_msg.html('<div class="error_msg">File not found</div>');
                                }
                            } else if (result['status'] == 'success') {
                                var socket = io.connect('http://' + window.location.hostname + ':3000');
                                socket.emit('send_msg', {
                                    user_id: result['user_id'],
                                    chat_message: result['chat_message'],
                                    chat_image: result['chat_image'],
                                    created_at: result['created_at'],
                                    chat_id: result['chat_id'],
                                    username: result['username']
                                });
                                clear_form();
                            }
                        }
                    });
                });

                function clear_form() {
                    $('#message').val('');
                    $('#img_preview').attr('src', '');
                    $("#image").val('');
                    console.log('form cleared');
                }

                $(document).on('click', '#select_image', function () {
                    $('#image').trigger('click');
                });

                function readURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $('#img_preview').attr('src', e.target.result);
                        }

                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $("#image").change(function () {
                    readURL(this);
                });

                $("#img_preview").click(function () {
                    image = $("#image");
                    $('#img_preview').attr('src', '');
                    $("#image").val('');
                    ;
                });



            });
        </script>
    </body>
</html>