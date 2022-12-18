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
                                                        $total_unread = $users_data['count_status'];
                                                    echo '
                          <li data-bs-dismiss="offcanvas">
                            <a href="#" class="nav-link text-start" data-bs-toggle="pill" role="tab">
                              <div class="d-flex">
                                <div class="flex-shrink-0 avatar me-2 ' . $status . '">
                                  <img class="avatar-img rounded-circle" src="' . $user['user_profile'] . '" alt="" />
                                </div>
                                <div class="flex-grow-1 my-auto d-block">
                                  <h6 class="mb-0 mt-1">' . $user['user_name'] . '</h6>
                                  <span class="badge bg-danger badge-pill">' . $total_unread . '</span>
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
                                    <div class="d-flex mb-2 mb-sm-0">
                                        <div class="d-block flex-grow-1">
                                            <h6 class="">Chat riêng tư</h6>
                                        </div>
                                    </div>
                                    <a href="chatroom.php" class="btn btn-success flex-grow-reverse-1">Phòng chat</a>
                                </div>
                                <hr>

                                <!-- Chat conversation START -->
                                <div class="chat-conversation-content custom-scrollbar" id="message-chat">
                                </div>
                                <!-- Chat conversation END -->
                            </div>
                        </div>
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
        const conn = new WebSocket('ws://localhost:8080?token=<?php echo $token ?>');

        conn.onopen = function (e) {
            console.log("Connection established!");
        };

        conn.onmessage = function (e) {

        };

        conn.onclose = function (e) {
            console.log("Connection is closed!");
        }
    })
</script>
</body>

</html>