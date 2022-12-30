<?php
$title = 'Phòng chat';
session_start();
if (!isset($_SESSION['user_data'])) {
    header('location: index.php');
}
require_once "database/ChatRoom.php";
require_once "database/ChatUser.php";
$chat = new ChatRoom();
$user = new ChatUser();
$chats_data = $chat->getAllChatData();
$token = '';
$login_user_id = '';
foreach ($_SESSION['user_data'] as $key => $value) {
    $token = $value['token'];
    $login_user_id = $value['id'];
}
$user->setUserId($login_user_id);
$users_data = $user->getAllUserDataWithStatusCount();
?>
<?php include_once "part/header_chat.php" ?>
<?php include_once("part/navbar.php") ?>

    <!-- **************** MAIN CONTENT START **************** -->
    <main>
        <!-- Container START -->
        <div class="container">
            <div class="row gx-0">
                <div class="col-lg-4 col-xxl-3" id="chatTabs" role="tablist">
                    <!-- Advanced filter responsive toggler END -->
                    <div class="card card-body border-end-0 border-bottom-0 rounded-bottom-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h1 class="h5 mb-0">
                                Người dùng
                                <span class="badge bg-info bg-opacity-10 text-primary"><?php echo count($users_data) - 1; ?></span>
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
                            <a href="privatechat.php" data-privatechat="' . $user['user_id'] . '" class="private-select nav-link text-start">
                              <div class="d-flex">
                                <div id="receiver_status' . $user['user_id'] . '" class="flex-shrink-0 avatar me-2 ' . $status . '" data-status="' . $status . '">
                                  <img class="avatar-img rounded-circle" src="' . $user['user_profile'] . '" alt="" />
                                </div>
                                <div class="flex-grow-1 my-auto d-block">
                                  <h6 class="mb-0 mt-1">' . $user['user_name'] . '</h6>
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
                                        <div class="d-flex mb-2 mb-sm-0">
                                            <div class="d-block flex-grow-1">
                                                <h6 class="">Phòng chat</h6>
                                            </div>
                                        </div>
                                        <a href="privatechat.php" class="btn btn-success flex-grow-reverse-1">Chat
                                            riêng</a>
                                    </div>
                                    <hr>

                                    <!-- Chat conversation START -->
                                    <div class="chat-conversation-content custom-scrollbar" id="message-chat">
                                        <?php
                                        date_default_timezone_set("Asia/Ho_Chi_Minh");
                                        foreach ($chats_data as $key => $message) {
                                            $date = date("H:i:s, d/m/Y", $message['created_on']);
                                            $msg = $message['message'];
                                            $user_id = $message['user_id'];
                                            if (isset($_SESSION['user_data'][$user_id])) {
                                                echo '        <!-- Chat message right -->
                                    <div class="d-flex justify-content-end text-end mb-1">
                                        <div class="w-100">
                                            <div class="d-flex flex-column align-items-end">
                                                <div
                                                        class="bg-primary text-white p-2 px-3 rounded-2"
                                                >
                                                    ' . $msg . '
                                                </div>
                                                <div class="d-flex my-2">
                                                    <div class="small text-secondary">' . $date . '</div>
                                                    <div class="small ms-2">
                                                        <i class="fa-solid fa-check"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                            } else {
                                                echo '            <!-- Chat name -->
                                    <div class="text-start small my-2">
                                        ' . $message['user_name'] . '
                                    </div>
                                    <!-- Chat message left -->
                                    <div class="d-flex mb-1">
                                        <div class="flex-shrink-0 avatar avatar-xs me-2">
                                            <img
                                                    class="avatar-img rounded-circle"
                                                    src="' . $message['user_profile'] . '"
                                                    alt=""
                                            />
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="w-100">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div
                                                            class="bg-light text-secondary p-2 px-3 rounded-2"
                                                    >
                                                        ' . $msg . '
                                                    </div>
                                                    <div class="small my-2">' . $date . '</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                            }
                                        }
                                        ?>
                                    </div>
                                    <!-- Chat conversation END -->
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <form type="post" id="chat_form" class="d-sm-flex align-items-end">
                                <input type="text" id="chat_message" maxlength="1000" minlength="1"
                                       class="form-control mb-sm-0 mb-3"
                                       data-autoresize="" placeholder="Nhập tin nhắn"
                                       rows="1"/>
                                <button type="submit" class="btn btn-sm btn-primary ms-2">
                                    <i class="fa-solid fa-paper-plane fs-6"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div><!-- Sidebar START -->

            </div>
        </div>
    </main>
    <!-- Row END -->
    <!-- =======================
    Chat END -->

    <!-- Container END -->
    <!-- **************** MAIN CONTENT END **************** -->
    <script src="assets/js/chat.js"></script>
    <script>
        ChatRoomHandle("<?php echo $token ?>");
    </script>
<?php include_once "part/signed_footer.php" ?>