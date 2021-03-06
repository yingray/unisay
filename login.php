<?php
// `$page_name` = 'login';

require __DIR__ . '/__connect_db.php';

if (isset($_POST['type'])) {

    if ($_POST['type'] === 'login') {
        $email_id = $_POST['email_id'];
        $password = $_POST['password'];

        if (!empty($email_id) and !empty($password)) {
            $password = sha1($password);

            $sql = sprintf("SELECT * FROM `members` WHERE `email_id`='%s' AND `password`='%s'",
                $mysqli->escape_string($email_id),
                $mysqli->escape_string($password)
            );

            $result = $mysqli->query($sql);

            $success = $result->num_rows > 0;

            if ($success) {
                $_SESSION['user'] = $result->fetch_assoc();
            } else {
                unset($_SESSION['user']);
//                header("Location: ./login.php");
                echo "<script>alert('您輸入的帳號或密碼有誤，請重新登入。'); location.href = './login.php';</script>";
            }

        }
    } else if ($_POST['type'] === 'register') {
        $email_id = $_POST['email_id'];
        $password = $_POST['password'];
        $nickname = $_POST['nickname'];
        $mobile = $_POST['mobile'];
        $address = $_POST['address'];

        if (!empty($email_id) and !empty($password) and !empty($nickname)) {
            $cert = sha1($email_id . uniqid());
            $password = sha1($password);

            $sql = "INSERT INTO `members`(
            `email_id`, `password`, `nickname`, `mobile`,
            `address`, `created_at`, `certification`
            ) VALUES (
            ?, ?, ?, ?,
            ?, NOW(), '$cert'
            )
            ";

            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('sssss', $email_id, $password, $nickname, $mobile, $address);
            $success = $stmt->execute();
            $stmt->close();

            if (isset($success) and $success) {
                header('Location: login_registered.html');
                exit();
            }

        }

    }
}


?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta charset="UTF-8">
    <title>UniSay</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="css/login_main.css" rel="stylesheet" type="text/css">


    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">


    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative" rel="stylesheet">
    <!-- font-family: 'Cinzel Decorative', cursive; -->

    <link href="https://fonts.googleapis.com/css?family=Amita" rel="stylesheet">
    <!-- font-family: 'Amita', cursive; -->

    <link href="https://fonts.googleapis.com/css?family=IM+Fell+English+SC" rel="stylesheet">
    <!-- font-family: 'IM Fell English SC', serif; -->

    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great" rel="stylesheet">
    <!-- font-family: 'Fredericka the Great', cursive; -->

    <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet">
    <!-- font-family: 'Titillium Web', sans-serif; -->


</head>


<!-- ====================================================================================== -->


<body>


<!-- 主頁面內容 -->
<div class="wrap">

    <header>

        <!-- 平板手機會fixed的banner -->
        <div class="banner">

            <!-- 三明治選單 -->
            <div class="sandwich"></div>


            <!-- fixed的按鈕 -->

            <div class="cart_icon"></div>
            <div class="cart_sidebar"></div>

            <div class="member_icon"></div>
            <div class="member_sidebar"></div>

            <div class="totop_icon"></div>


            <!-- 中央logo -->
            <div class="logo"><img src="images/header/header_logo.svg" alt=""></div>


            <!-- 上方選單列 -->
            <nav>
                <ul>
                    <!-- 當前頁面掛上here的class -->
                    <li class="icon_aboutus here">
                        <a href="aboutus.html"></a>
                    </li>
                    <li class="icon_product">
                        <a href="product.html"></a>
                    </li>
                    <li class="icon_custom">
                        <a href="custom.html"></a>
                    </li>
                    <li class="icon_inspire">
                        <a href="inspire.html"></a>
                    </li>
                </ul>
            </nav>


            <!-- 暗幕的背景 -->
            <div class="fixed_shadowbg"></div>

        </div><!-- banner包全部 -->

    </header>


    <!-- ====================================================================================== -->
    <content>


        <?php if (isset($success)): ?>
            <?php if ($success): ?>
                <div class="con">
                    <!-- 麵包屑 -->
                    <div class="loginnav">
                        <p>LOG IN</p>
                        <img src="images/member/line.svg">
                    </div>

                    <!-- 中間提示內容 -->
                    <div class="sign5"></div>

                    <div class="btn-groups">
                        <div class="btn-keepgoing">
                            <a href="product.html">繼續選購</a>
                        </div>

                        <div class="btn-back">
                            <a href="memberaccountmember.php">返回會員中心</a>
                        </div>

                        <div class="btn-logout">
                            <a href="logout.php">會員登出</a>
                        </div>
                    </div>
                    
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="con">
                <!-- 麵包屑 -->
                <div class="loginnav">
                    <p>LOG IN</p>
                    <img src="images/member/line.svg">
                </div>

                <!-- 會員登入 -->


                <div class="signin_joinus">
                    <div class="signin" id="loginRight">
                        <form class="mainbody1" name="form1" method="post"> <!-- 不要讓表單送出 -->
                            <div class="picsignin">
                                <img src="images/member/picsignin.svg">
                            </div>
                            <div class="form-group">
                                <label for="">帳號：</label>
                                <input type="email" class="form-control" id="email_id" name="email_id"
                                       placeholder=" Email">
                            </div>
                            <div class="form-group">
                                <label for="">密碼：</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <input type="hidden" name="type" value="login">
                            <div class="memberforgerpassword">
                                <a href="memberforgerpassword.php">忘記密碼？</a>
                            </div>
                            <div class="loginmember">

                                <a href="#" onclick="$(this).closest('form').submit()">登入會員</a>

                            </div>
                        </form>
                    </div>
                    <!-- 加入會員 -->
                    <div class="joinus" id="loginRight">
                        <form class="mainbody2" name="form2" method="post" onsubmit="return checkForm();">
                            <div class="piclogin">
                                <img src="images/member/picjoinin.svg">
                            </div>
                            <div class="form-group havetowrite">
                                <label for="email_id">帳號：</label> <span id="email_id_info2"
                                                                        style="color:red;display:none;">請填寫正確的 email 格式</span>
                                <input type="email" class="form-control" id="email_id" name="email_id"
                                       placeholder=" Email">
                            </div>
                            <div class="form-group havetowrite">
                                <label for="password">密碼：</label> <span id="password_info2"
                                                                        style="color:red;display:none;">密碼長度請設定大於 6 !</span>
                                <input type="password" class="form-control" id="password" name="password" placeholder=" 密碼長度至少6位">
                            </div>
                            <div class="form-group havetowrite">
                                <label for="nickname">暱稱：</label> <span id="nickname_info2"
                                                                        style="color:red;display:none;">暱稱長度請設定大於 2 !</span>
                                <input type="text" class="form-control" id="nickname" name="nickname" placeholder=" 暱稱">
                            </div>
                            <div class="form-group havetowrite">
                                <label for="mobile">手機：</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder=" 手機號碼">
                            </div>
                            <div class="form-group havetowrite">
                                <label for="address"> 地址：</label>
                                <input type="text" class="form-control" id="address" name="address"></input>
                            </div>
                            <div class="joinmenber">
                                <input type="hidden" name="type" value="register">
                                <a href="#" onclick="$(this).closest('form').submit()">加入會員</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        <?php endif; ?>


    </content>


    <!-- ====================================================================================== -->


    <footer>


    </footer>


    <!-- ====================================================================================== -->


</div><!-- wrap的結尾 -->


<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script src="js/nav_icon.js"></script>
<script>
    function checkForm() {
        var pattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
        var email_id = form2.email_id.value;
        var password = form2.password.value;
        var nickname = form2.nickname.value;
        var pattern2 = /^[a-z]\d{3}/;

        var isPass = true;

        var info1 = $('#email_id_info2');
        var info2 = $('#password_info2');
        var info3 = $('#nickname_info2');
        info1.hide();
        info2.hide();
        info3.hide();

        if (!pattern.test(email_id)) {
//            info1.show();
//            info1.text('請填寫正確的 email 格式');
            location.href = 'login_field_required.html';
            isPass = false;
        }
        if (password.length < 6) {
//            info2.show();
            location.href = 'login_field_required.html';
            isPass = false;
        }
        if (nickname.length < 2) {
//            info3.show();
            location.href = 'login_field_required.html';
            isPass = false;
        }

        if (isPass) {
            $.get('aj__check_email_id.php', {email_id: email_id}, function (data) {
                if (data === 'yes') {
//                    info1.show();
//                    info1.text('此 email 已註冊過');
                    location.href = 'login_existed_email.html';
                } else if (data === 'no') {
                    form2.submit();
                }
            });
        }

        return false;
    }

</script>


</body>
</html>
