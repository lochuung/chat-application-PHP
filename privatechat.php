<?php
$title = 'Chat riêng';
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
$count_online_user = $user->countOnlineUser();
?>
<?php include_once "part/header_chat.php" ?>
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
                                <span class="badge bg-success bg-opacity-10 text-success"
                                      id="online-user"><?php echo($count_online_user - 1) ?></span>
                            </h1>
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
                                        <a href="chatroom.php" class="btn btn-success flex-grow-reverse-1">Phòng
                                            chat</a>
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

    <script src="assets/js/chat.js"></script>
    <script>
        PrivateChatHandle("<?php echo $token ?>");
        <?php
        if (isset($_SESSION['private_select'])) {
        ?>
        $(document).ready(function () {
            $(`[data-userid='${<?php echo $_SESSION['private_select'] ?>}']`).click();
            $(`[data-userid='${<?php echo $_SESSION['private_select'] ?>}']`).addClass('active');
        })
        <?php
            unset($_SESSION['private_select']);
        }
        ?>
    </script>
<?php include_once "part/signed_footer.php" ?>