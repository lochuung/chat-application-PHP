<?php
session_start();
if (!isset($_SESSION['user_data'])) {
    header('location: index.php');
}
require_once "database/ChatUser.php";
$login_user_id = '';
$token = '';
foreach ($_SESSION['user_data'] as $key => $value) {
    $login_user_id = $value['id'];
    $token = $value['token'];
}

$user = new ChatUser();
$user->setUserId($login_user_id);
$users_data = $user->getAllUserDataWithStatusCount();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Chat riêng - Chat application</title>

    <!-- Meta Tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <script src="assets/vendor/jquery-3.6.1.min.js"></script>
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"/>

    <!-- Plugins CSS -->
    <link rel="stylesheet" type="text/css" href="assets/vendor/font-awesome/css/all.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap-icons/bootstrap-icons.css"/>
    <link rel="stylesheet" type="text/css" href="assets/vendor/OverlayScrollbars-master/css/OverlayScrollbars.min.css"/>

    <!-- Theme CSS -->
    <link id="style-switch" rel="stylesheet" type="text/css" href="assets/css/style.css"/>
    <script src="assets/vendor/jquery-3.6.1.min.js"></script>
</head>

<body>
<?php include_once("part/navbar.php") ?>

<!-- **************** MAIN CONTENT START **************** -->
<main>
    <!-- Container START -->
    <div class="container">
        <div class="row gx-0">
            <!-- Sidebar START -->
            <div class="col-lg-4 col-xxl-3" id="chatTabs" role="tablist">
                <!-- Divider -->
                <div class="d-flex align-items-center mb-4 d-lg-none">
                    <button class="border-0 bg-transparent" type="button" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <i class="btn btn-primary fw-bold fa-solid fa-sliders"></i>
                        <span class="h6 mb-0 fw-bold d-lg-none ms-2">Chats</span>
                    </button>
                </div>
                <!-- Advanced filter responsive toggler END -->
                <div class="card card-body border-end-0 border-bottom-0 rounded-bottom-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h5 mb-0">
                            Người dùng
                            <span class="badge bg-info bg-opacity-10 text-primary">0</span>
                        </h1>
                        <!-- Chat new create message item START -->
                        <!--                        <div class="dropend position-relative">-->
                        <!--                            <div class="nav">-->
                        <!--                                <a class="icon-md rounded-circle btn btn-sm btn-primary-soft nav-link toast-btn"-->
                        <!--                                   data-target="chatToast" href="#">-->
                        <!--                                    <i class="bi bi-pencil-square"></i>-->
                        <!--                                </a>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <!-- Chat new create message item END -->
                    </div>
                </div>

                <nav class="navbar navbar-light navbar-expand-lg mx-0">
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
                        <!-- Offcanvas header -->
                        <div class="offcanvas-header">
                            <button type="button" class="btn-close text-reset ms-auto"
                                    data-bs-dismiss="offcanvas"></button>
                        </div>

                        <!-- Offcanvas body -->
                        <div class="offcanvas-body p-0">
                            <div class="card card-chat-list rounded-end-lg-0 card-body border-end-lg-0 rounded-top-0">
                                <!-- Search chat START -->
                                <!--                                <form class="position-relative">-->
                                <!--                                    <input class="form-control py-2" type="search" placeholder="Empty input"-->
                                <!--                                           aria-label="Search"/>-->
                                <!--                                    <button class="btn bg-transparent text-secondary px-2 py-0 position-absolute top-50 end-0 translate-middle-y"-->
                                <!--                                            type="submit">-->
                                <!--                                        <i class="bi bi-search fs-5"></i>-->
                                <!--                                    </button>-->
                                <!--                                </form>-->
                                <!-- Search chat END -->
                                <!-- Chat list tab START -->
                                <div class="mt-4 h-100">
                                    <div class="chat-tab-list custom-scrollbar">
                                        <ul class="nav flex-column nav-pills nav-pills-soft">
                                            <?php
                                            foreach ($users_data as $key => $user) {
                                                $status = 'status-online';
                                                if ($user['user_login_status'] == 'Logout')
                                                    $status = 'status-offline';
                                                if ($user['user_id'] != $login_user_id) {
                                                    $total_unread = '';
                                                    if ($user['count_status'] > 0)
                                                        $total_unread = $user['count_status'];
                                                    echo '
                                          <li data-bs-dismiss="offcanvas">
                                            <a class="nav-link text-start select-user" data-userid="' . $user['user_id'] . '" data-bs-toggle="pill" role="tab">
                                              <div class="d-flex">
                                                <div id="receiver_status' . $user['user_id'] . '" class="flex-shrink-0 avatar me-2 ' . $status . '" data-status="' . $status . '">
                                                  <img class="avatar-img rounded-circle" id="receiver_profile' . $user['user_id'] . '" src="' . $user['user_profile'] . '" alt="" />
                                                </div>
                                                <div class="flex-grow-1 my-auto d-block">
                                                  <h6 class="mb-0 mt-1" id="receiver' . $user['user_id'] . '">' . $user['user_name'] . '</h6>
                                                  <span id="receiver_notification' . $user['user_id'] . '" class="badge bg-danger badge-pill">' . $total_unread . '</span>
                                                </div>
                                              </div>
                                            </a>
                                          </li>';
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <!-- Chat list tab END -->
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- Chat conversation START -->
            <div class="col-lg-8 col-xxl-9">
                <div class="card card-chat rounded-start-lg-0 border-start-lg-0">
                    <div class="card-body h-100">
                        <div class="tab-content py-0 mb-0 h-100">
                            <div class="fade tab-pane show active h-100" id="chat-1" role="tabpanel"
                                 aria-labelledby="chat-1-tab">
                                <div class="d-sm-flex justify-content-between align-items-center">
                                    <div class="d-flex mb-2 mb-sm-0" id="chat-header">
                                        <div class="d-block flex-grow-1">
                                            <h6 class="">Chat riêng tư</h6>
                                        </div>
                                    </div>
                                    <a href="chatroom.php" class="btn btn-success flex-grow-reverse-1">Phòng chat</a>
                                </div>
                                <hr>

                                <!-- Chat conversation START -->
                                <div class="chat-conversation-content custom-scrollbar" id="chat-body">
                                </div>
                                <!-- Chat conversation END -->

                            </div>
                        </div>
                    </div>

                    <div class="card-footer" id="chat-footer"></div>
                </div>
            </div>
        </div>
    </div>
</main>
<input type="hidden" id="receiver-id" value=""/>
<!-- Row END -->
<!-- =======================
Chat END -->

<!-- Container END -->
<!-- **************** MAIN CONTENT END **************** -->

<!-- =======================
JS libraries, plugins and custom scripts -->

<!-- Bootstrap JS -->
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Vendors -->
<script src="assets/vendor/OverlayScrollbars-master/js/OverlayScrollbars.min.js"></script>

<!-- Template Functions -->
<script src="assets/js/functions.js"></script>
<script src="assets/js/logout.js"></script>
<script>
    $(document).ready(function () {
        const conn = new WebSocket('ws://localhost:8080?token=<?php echo $token ?>');
        const chat_display = $("#chat-body .os-viewport-native-scrollbars-invisible");
        let userListSelect = 0;
        conn.onopen = function (e) {
            console.log("Connection established!");
        };

        conn.onmessage = function (e) {
            //console.log(e.data);
            const data = JSON.parse(e.data);
            data.time = (new Date(data.time)).toLocaleTimeString("vi-VN");
            if ((data.receiverId == $('#login_user_id').val() && userListSelect == data.userId) || data.from == 'me') {
                let html = `            <!-- Chat name -->
                                    <div class="text-start small my-2">
                                        ${data.from}
                                    </div>
                                    <!-- Chat message left -->
                                    <div class="d-flex mb-1">
                                        <div class="flex-shrink-0 avatar avatar-xs me-2">
                                            <img
                                                    class="avatar-img rounded-circle"
                                                    src="${data.userProfile}"
                                                    alt=""
                                            />
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="w-100">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div
                                                            class="bg-light text-secondary p-2 px-3 rounded-2"
                                                    >
                                                        ${data.msg}
                                                    </div>
                                                    <div class="small my-2">${data.time}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;

                if (data.from === 'me') {
                    html = `        <!-- Chat message right -->
                                    <div class="d-flex justify-content-end text-end mb-1">
                                        <div class="w-100">
                                            <div class="d-flex flex-column align-items-end">
                                                <div
                                                        class="bg-primary text-white p-2 px-3 rounded-2"
                                                >
                                                    ${data.msg}
                                                </div>
                                                <div class="d-flex my-2">
                                                    <div class="small text-secondary">${data.time}</div>
                                                    <div class="small ms-2">
                                                        <i class="fa-solid fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    $('#chat_message').val('');
                }

                $('#chat-body .os-content').append(html);
                //scroll to down of the message chat
                $(window).scrollTop($("main")[0].scrollHeight);
                chat_display.scrollTop(chat_display[0].scrollHeight);
            } else {
                const notifi = $(`#receiver_notification${data.userId}`);
                let count = notifi.text();
                if (count == '') {
                    count = 0;
                }
                count++;
                console.log(count);
                notifi.text(count);
            }
        };

        conn.onclose = function (e) {
            console.log("Connection is closed!");
        }
        $(document).on('submit', '#chat_form', function (e) {
            e.preventDefault();
            const msg = $("#chat_message").val();
            const receiver_id = $('#receiver-id').val();
            if (msg.length > 0 && receiver_id.length > 0) {
                const data = {
                    userId: $('#login_user_id').val(),
                    msg: msg,
                    receiverId: receiver_id,
                    command: 'private'
                };
                conn.send(JSON.stringify(data));
            }
        })

        $(document).on('click', '.select-user', function (e) {
            $('#chat-body .os-content').html('');
            const user_id = $(this).data('userid');
            userListSelect = user_id;
            $('#receiver-id').val(user_id);
            const receiver = {
                id: user_id,
                name: $(`#receiver${user_id}`).text(),
                status: $(`#receiver_status${user_id}`).data('status'),
                profile: $(`#receiver_profile${user_id}`)[0].src,
            }
            makeChatArea(receiver.name, receiver.status, receiver.profile);
            var promise = new Promise(function (resolve) {
                $.ajax({
                    url: "action.php",
                    method: "POST",
                    data: {
                        action: 'fetch_chat_data',
                        to_user_id: receiver.id,
                        from_user_id: $('#login_user_id').val()
                    },
                    success: function (data) {
                        data = JSON.parse(data);
                        resolve(data);
                    }
                });
            })
            promise.then(function (data) {
                for (let i = 0; i < data.length; i++) {
                    const time = (new Date(data[i]["timestamp"])).toLocaleTimeString("vi-VN");
                    let html = `            <!-- Chat name -->
                                    <div class="text-start small my-2">
                                        ${data[i]["user_name"]}
                                    </div>
                                    <!-- Chat message left -->
                                    <div class="d-flex mb-1">
                                        <div class="flex-shrink-0 avatar avatar-xs me-2">
                                            <img
                                                    class="avatar-img rounded-circle"
                                                    src="${data[i]["user_profile"]}"
                                                    alt=""
                                            />
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="w-100">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div
                                                            class="bg-light text-secondary p-2 px-3 rounded-2"
                                                    >
                                                        ${data[i]["chat_message"]}
                                                    </div>
                                                    <div class="small my-2">${time}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;

                    if (data[i].from_user_id == $('#login_user_id').val()) {
                        html = `        <!-- Chat message right -->
                                    <div class="d-flex justify-content-end text-end mb-1">
                                        <div class="w-100">
                                            <div class="d-flex flex-column align-items-end">
                                                <div
                                                        class="bg-primary text-white p-2 px-3 rounded-2"
                                                >
                                                    ${data[i]["chat_message"]}
                                                </div>
                                                <div class="d-flex my-2">
                                                    <div class="small text-secondary">${time}</div>
                                                    <div class="small ms-2">
                                                        <i class="fa-solid fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                    }

                    $('#chat-body .os-content').append(html);
                }
            }).then(function () {
                const notifi = $(`#receiver_notification${userListSelect}`);
                notifi.text('');
                chat_display.scrollTop(chat_display[0].scrollHeight);
                return chat_display.scrollTop < chat_display[0].scrollHeight;
            }).then(function (check) {
                setTimeout(function () {
                    chat_display.scrollTop(chat_display[0].scrollHeight);
                }, 500);
            });
        });

    })

    function makeChatArea(name, status, profile) {
        status = (status === 'status-online') ? "Online" : "Offline";
        const status_color = status === 'Online' ? "text-success" : "text-danger";
        const header =
            `<div class="flex-shrink-0 avatar me-2">
              <img class="avatar-img rounded-circle" src="${profile}" alt="">
            </div>
            <div class="d-block flex-grow-1">
              <h6 class="mb-0 mt-1">${name}</h6>
              <div class="small text-secondary">
                <i class="fa-solid fa-circle ${status_color} me-1"></i>
                ${status}
              </div>
            </div>`;

        $('#chat-header').html(header);

        //create chat text area
        const footer =
            `
                <form type="post" id="chat_form" class="d-sm-flex align-items-end">
                    <textarea id="chat_message" maxlength="1000" minlength="1" class="form-control mb-sm-0 mb-3"
                                                                data-autoresize="" placeholder="Nhập tin nhắn"
                                                                rows="1"></textarea>
                    <button class="btn btn-sm btn-danger-soft ms-sm-2">
                        <i class="fa-solid fa-face-smile fs-6"></i>
                    </button>
                    <button class="btn btn-sm btn-secondary-soft ms-2">
                        <i class="fa-solid fa-paperclip fs-6"></i>
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary ms-2">
                        <i class="fa-solid fa-paper-plane fs-6"></i>
                    </button>
                </form>`;
        $('#chat-footer').html(footer);
    }
</script>
</body>

</html>