<?php
session_start();
if (!isset($_SESSION['user_data'])) {
    header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Social - Network, Community and Event Theme</title>

    <!-- Meta Tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico"/>

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
<?php include_once ("part/navbar.php")?>

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
                            <a href="#chat-2" class="nav-link text-start" id="chat-2-tab" data-bs-toggle="pill" role="tab">
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
            <!-- Sidebar START -->

            <!-- Chat conversation START -->
            <div class="col-lg-8 col-xxl-9">
                <div class="d-flex aligns-items-center justify-content-center card card-chat rounded-start-lg-0 border-start-lg-0">
                    <div class="d-flex aligns-items-center justify-content-center">
                        <strong>Hãy chọn một đoạn chat hoặc bắt đầu cuộc trò chuyện mới</strong>
                    </div>
                </div>
            </div>
            <!-- Chat conversation END -->
        </div>
        <!-- Row END -->
        <!-- =======================
      Chat END -->
    </div>
    <!-- Container END -->
</main>
<!-- **************** MAIN CONTENT END **************** -->

<!-- Chat START -->
<div class="position-fixed bottom-0 end-0 p-3">
    <!-- Chat toast START -->
    <div id="chatToast" class="toast bg-mode" role="alert" aria-live="assertive" aria-atomic="true"
         data-bs-autohide="false">
        <div class="toast-header bg-mode d-flex justify-content-between">
            <!-- Title -->
            <h6 class="mb-0">New message</h6>
            <button class="btn btn-secondary-soft-hover py-1 px-2" data-bs-dismiss="toast" aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <!-- Top avatar and status END -->
        <div class="toast-body collapse show" id="collapseChat">
            <!-- Chat conversation START -->
            <!-- Form -->
            <form>
                <div class="input-group mb-3">
                    <span class="input-group-text border-0">To</span>
                    <input class="form-control" type="text" placeholder="Type a name or multiple names"/>
                </div>
            </form>
            <!-- Chat conversation END -->
            <!-- Extra space -->
            <div class="h-200px"></div>
            <!-- Button  -->
            <div class="d-sm-flex align-items-end">
                <textarea class="form-control mb-sm-0 mb-3" placeholder="Type a message" rows="1"
                          spellcheck="false"></textarea>
                <button class="btn btn-sm btn-danger-soft ms-sm-2">
                    <i class="fa-solid fa-face-smile fs-6"></i>
                </button>
                <button class="btn btn-sm btn-secondary-soft ms-2">
                    <i class="fa-solid fa-paperclip fs-6"></i>
                </button>
                <button class="btn btn-sm btn-primary ms-2">
                    <i class="fa-solid fa-paper-plane fs-6"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Chat toast END -->
</div>
<!-- Chat END -->

<!-- =======================
JS libraries, plugins and custom scripts -->

<script src="assets/vendor/jquery-3.6.1.min.js"></script>
<!-- Bootstrap JS -->
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

<!-- Vendors -->
<script src="assets/vendor/OverlayScrollbars-master/js/OverlayScrollbars.min.js"></script>

<!-- Template Functions -->
<script src="assets/js/functions.js"></script>
<script>
    $(document).ready(function () {
        const id = $("#logout-id").val();
        $("#logout").click(function () {
            $.ajax({
                url: "action.php",
                method: "POST",
                data: {
                    "id": id,
                    "action": 'leave'

                },
                success: function (data) {
                    const json = JSON.parse(data);
                    if (json.status == 1) {
                        location.href = "index.php";
                    }
                }
            });
        });
    });
</script>
</body>

</html>