<?php
session_start();
if (!isset($_SESSION['user_data'])) {
    header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Phòng chat - Chat application</title>

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
                            Đang hoạt động
                            <span class="badge bg-success bg-opacity-10 text-success"><?php echo count($_SESSION['user_data']); ?></span>
                        </h1>
                        <!-- Chat new create message item START -->
                        <div class="dropend position-relative">
                            <div class="nav">
                                <a class="icon-md rounded-circle btn btn-sm btn-primary-soft nav-link toast-btn"
                                   data-target="chatToast" href="#">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </div>
                        </div>
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
                                <form class="position-relative">
                                    <input class="form-control py-2" type="search" placeholder="Search for chats"
                                           aria-label="Search"/>
                                    <button class="btn bg-transparent text-secondary px-2 py-0 position-absolute top-50 end-0 translate-middle-y"
                                            type="submit">
                                        <i class="bi bi-search fs-5"></i>
                                    </button>
                                </form>
                                <!-- Search chat END -->
                                <!-- Chat list tab START -->
                                <div class="mt-4 h-100">
                                    <div class="chat-tab-list custom-scrollbar">
                                        <ul class="nav flex-column nav-pills nav-pills-soft">
                                            <?php
                                            foreach ($_SESSION['user_data'] as $key => $value) {
                                                echo '
                          <li data-bs-dismiss="offcanvas">
                            <a href="#chat-1" class="nav-link text-start" id="chat-1-tab" data-bs-toggle="pill" role="tab">
                              <div class="d-flex">
                                <div class="flex-shrink-0 avatar me-2 status-online">
                                  <img class="avatar-img rounded-circle" src="' . $value['profile'] . '" alt="" />
                                </div>
                                <div class="flex-grow-1 d-block">
                                  <h6 class="mb-0 mt-1">' . $value['name'] . '</h6>
                                </div>
                              </div>
                            </a>
                          </li>';
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
                                    <div class="d-flex mb-2 mb-sm-0">
                                        <div class="d-block flex-grow-1">
                                            <h6 class="mb-0 mt-1">Phòng chat</h6>
                                            <div class="small text-secondary">
                                                <i class="fa-solid fa-circle text-success me-1"></i>Online
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>

                                <!-- Chat conversation START -->
                                <div class="chat-conversation-content custom-scrollbar" id="message-chat">
                                </div>
                                <!-- Chat conversation END -->
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
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
        const conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function (e) {
            console.log("Connection established!");
        };

        conn.onmessage = function (e) {
            console.log(e.data);
            var data = JSON.parse(e.data);
            var currentTime = new Date();
            var html = `            <!-- Chat name -->
                                    <div class="text-start small my-2">
                                        ${data.userName}
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
                                                    <div class="small my-2">${currentTime.getHours()}:${currentTime.getMinutes()}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
            $('#message-chat .os-content').append(html);
        }

        $('#chat_form').on('submit', function (e) {
            e.preventDefault();
            var msg = $("#chat_message").val();
            if (msg.length > 0) {
                var data = {
                    userId: $('#login_user_id').val(),
                    userName: $('#login_user_name').val(),
                    userProfile: $('#login_user_profile').val(),
                    msg: msg
                };
                conn.send(JSON.stringify(data));
                $('#chat_message').val('');
                var currentTime = new Date();
                var html = `        <!-- Chat message right -->
                                    <div class="d-flex justify-content-end text-end mb-1">
                                        <div class="w-100">
                                            <div class="d-flex flex-column align-items-end">
                                                <div
                                                        class="bg-primary text-white p-2 px-3 rounded-2"
                                                >
                                                    ${data.msg}
                                                </div>
                                                <div class="d-flex my-2">
                                                    <div class="small text-secondary">${currentTime.getHours()}:${currentTime.getMinutes()}</div>
                                                    <div class="small ms-2">
                                                        <i class="fa-solid fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                $('#message-chat .os-content').append(html);
            }
        })

    })
</script>
</body>

</html>