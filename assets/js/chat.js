let userListSelect = 0;
const wsServer = 'ws://localhost:8080';

function ChatRoomHandle(token) {
    $(document).ready(function () {
        const conn = new WebSocket(`${wsServer}/?token=${token}`);
        const chat_display = $("#message-chat .os-viewport-native-scrollbars-invisible");
        //scroll to down of the message chat
        chat_display.scrollTop(chat_display[0].scrollHeight);
        $(window).scrollTop($("body")[0].scrollHeight);

        conn.onopen = function () {
            console.log("Connection established!");
        };

        conn.onmessage = function (e) {
            console.log(e.data);
            ChatRoomMessageHandle(e, chat_display);
        }
        ChatRoomSubmitChat(conn);

    })
}

function PrivateChatHandle(token) {
    $(document).ready(function () {
        const conn = new WebSocket(`${wsServer}/?token=${token}`);
        const chat_display = $("#chat-body .os-viewport-native-scrollbars-invisible");
        conn.onopen = function () {
            console.log("Connection established!");
        };

        conn.onmessage = function (e) {
            PrivateChatMessageHandle(e, chat_display);
        };

        conn.onclose = function () {
            console.log("Connection is closed!");
        }
        PrivateChatSubmitHandle(conn);
        PrivateChatSelectUserHandle(chat_display);
    })
}

function ChatRoomSubmitChat(conn) {
    $('#chat_form').on('submit', function (e) {
        e.preventDefault();
        const msg = $("#chat_message").val();
        if (msg.length > 0) {
            const data = {
                userId: $('#login_user_id').val(),
                msg: msg
            };
            conn.send(JSON.stringify(data));
        }
    })
}

function ChatRoomMessageHandle(e, chat_display) {
    const data = JSON.parse(e.data);
    const status = $(`#receiver_status${data.status_user_id}`);
    if (data.status_type === 'online') {
        status.removeClass('status-offline');
        status.addClass('status-online');
    } else if (data.status_type === 'offline') {
        status.removeClass('status-online');
        status.addClass('status-offline');
    } else if (data.command === 'private') {
        const notifi = $(`#receiver_notification${data.pv_userId}`);
        let count = notifi.text();
        if (count == '') {
            count = 0;
        }
        console.log(`#receiver_notification${data.pv_userId}`);
        count++;
        notifi.text(count);
    } else {
        data.time = (new Date(data.time * 1000)).toLocaleString("vi-VN");
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

        $('#message-chat .os-content').append(html);
        //scroll to down of the message chat
        $(window).scrollTop($("main")[0].scrollHeight);
        chat_display.scrollTop(chat_display[0].scrollHeight);
    }
}

function MakeChatAreaInPrivateChat(id, name, status, profile) {
    status = (status === 'status-online') ? "Online" : "Offline";
    const status_color = status === 'Online' ? "text-success" : "text-danger";
    const header =
        `<div class="flex-shrink-0 avatar me-2">
              <img class="avatar-img rounded-circle" src="${profile}" alt="">
            </div>
            <div class="d-block flex-grow-1">
              <h6 class="mb-0 mt-1">${name}</h6>
              <div class="small text-secondary">
                <i id="chat-area-status-icon${id}" class="fa-solid fa-circle ${status_color} me-1"></i>
                <p id="chat-area-status${id}">${status}</p>
              </div>
            </div>`;

    $('#chat-header').html(header);

    //create chat text area
    const footer =
        `
                <form type="post" id="chat_form" class="d-sm-flex align-items-end">
                    <input type="text" id="chat_message" maxlength="1000" minlength="1" class="form-control mb-sm-0 mb-3"
                                                                data-autoresize="" placeholder="Nhập tin nhắn"
                                                                rows="1" />
                    <button type="submit" class="btn btn-sm btn-primary ms-2">
                        <i class="fa-solid fa-paper-plane fs-6"></i>
                    </button>
                </form>`;
    $('#chat-footer').html(footer);
}

function PrivateChatMessageHandle(e, chat_display) {
    const data = JSON.parse(e.data);
    const status = $(`#receiver_status${data.status_user_id}`);
    const statusIconChatArea = $(`#chat-area-status-icon${data.status_user_id}`);
    const statusChatArea = $(`#chat-area-status${data.status_user_id}`);
    $("#online-user").text(data.online_user - 1);
    if (data.status_type === 'online') {
        status.removeClass('status-offline');
        status.addClass('status-online');
        status[0].dataset.status = 'status-online';
        statusIconChatArea.removeClass('text-danger');
        statusIconChatArea.addClass('text-success');
        statusChatArea.text('Online');

    } else if (data.status_type === 'offline') {
        status.removeClass('status-online');
        status.addClass('status-offline');
        status[0].dataset.status = 'status-offline';
        statusIconChatArea.removeClass('text-success');
        statusIconChatArea.addClass('text-danger');
        statusChatArea.text('Offline');
    } else {
        data.pv_time = (new Date(data.pv_time * 1000)).toLocaleString("vi-VN");
        if ((data.pv_receiverId == $('#login_user_id').val() && userListSelect == data.pv_userId) || data.pv_from == 'me') {
            if (userListSelect == data.pv_userId) $(`[data-userid='${data.pv_userId}']`).click();
            let html = `            <!-- Chat name -->
                                    <div class="text-start small my-2">
                                        ${data.pv_from}
                                    </div>
                                    <!-- Chat message left -->
                                    <div class="d-flex mb-1">
                                        <div class="flex-shrink-0 avatar avatar-xs me-2">
                                            <img
                                                    class="avatar-img rounded-circle"
                                                    src="${data.pv_userProfile}"
                                                    alt=""
                                            />
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="w-100">
                                                <div class="d-flex flex-column align-items-start">
                                                    <div
                                                            class="bg-light text-secondary p-2 px-3 rounded-2"
                                                    >
                                                        ${data.pv_msg}
                                                    </div>
                                                    <div class="small my-2">${data.pv_time}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;

            if (data.pv_from === 'me') {
                html = `        <!-- Chat message right -->
                                    <div class="d-flex justify-content-end text-end mb-1">
                                        <div class="w-100">
                                            <div class="d-flex flex-column align-items-end">
                                                <div
                                                        class="bg-primary text-white p-2 px-3 rounded-2"
                                                >
                                                    ${data.pv_msg}
                                                </div>
                                                <div class="d-flex my-2">
                                                    <div class="small text-secondary">${data.pv_time}</div>
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
            const notifi = $(`#receiver_notification${data.pv_userId}`);
            let count = notifi.text();
            if (count == '') {
                count = 0;
            }
            console.log(`#receiver_notification${data.pv_userId}`);
            count++;
            notifi.text(count);
        }
    }
}

function PrivateChatSubmitHandle(conn) {
    $(document).on('submit', '#chat_form', function (e) {
        e.preventDefault();
        const msg = $("#chat_message").val();
        const receiver_id = $('#receiver-id').val();
        if (msg.length > 0 && receiver_id.length > 0) {
            const data = {
                pv_userId: $('#login_user_id').val(),
                pv_msg: msg,
                pv_receiverId: receiver_id,
                command: 'private'
            };
            conn.send(JSON.stringify(data));
        }
    })
}

function PrivateChatSelectUserHandle(chat_display) {
    $(document).on('click', '.select-user', function () {
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
        MakeChatAreaInPrivateChat(receiver.id, receiver.name, receiver.status, receiver.profile);
        FetchChatDataInPrivateChat(receiver.id, chat_display);
    });
}

function FetchChatDataInPrivateChat(id, chat_display) {
    const promise = new Promise(function (resolve) {
        $.ajax({
            url: "action.php",
            method: "POST",
            data: {
                action: 'fetch_chat_data',
                to_user_id: id,
                from_user_id: $('#login_user_id').val()
            },
            success: function (data) {
                data = JSON.parse(data);
                resolve(data);
            }
        });
    });
    promise.then(function (data) {
        for (let i = 0; i < data.length; i++) {
            const time = (new Date(data[i]["timestamp"] * 1000)).toLocaleString("vi-VN");
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
    }).then(function () {
        setTimeout(function () {
            chat_display.scrollTop(chat_display[0].scrollHeight);
        }, 500);
    });
}