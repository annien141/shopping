<?php /*a:1:{s:79:"D:\01\phpstudy\PHPTutorial\WWW\shopping\application\admin\view\login\login.html";i:1561728673;}*/ ?>
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">

    <title>管理系统登录</title>
    <link rel="icon" href="/shopping/public/static/admin_login/images/favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="/shopping/public/static/admin_login/images/favicon.ico" type="image/x-icon"/>
    <link href="/shopping/public/static/admin_login/css/default.css" rel="stylesheet" type="text/css" />
    <!--必要样式-->
    <link href="/shopping/public/static/admin_login/css/styles.css" rel="stylesheet" type="text/css" />
    <link href="/shopping/public/static/admin_login/css/demo.css" rel="stylesheet" type="text/css" />
    <link href="/shopping/public/static/admin_login/css/loaders.css" rel="stylesheet" type="text/css" />
    <script src="/shopping/public/static/admin_login/js/jquery-2.1.1.min.js"></script>

</head>
<body>
<div class='login'>

    <!--<img class="MyLogo" src="loginSpecial/images/logo01.png" alt="   LOGO">-->
    <div class='login_title'>
        <span>管理员登录</span>
    </div>
    <div class='login_fields'>
        <div class='login_fields__user'>
            <div class='icon'>

                <img alt="" src='/shopping/public/static/admin_login/img/user_icon_copy.png'>
            </div>
            <input name="login" placeholder='用户名' maxlength="16" class="username" type='text' autocomplete="off" value=""/>
            <div class='validation'>
                <img alt="" src='/shopping/public/static/admin_login/img/tick.png'>
            </div>
        </div>
        <div class='login_fields__password'>
            <div class='icon'>
                <img alt="" src='/shopping/public/static/admin_login/img/lock_icon_copy.png'>
            </div>
            <input name="pwd" class="passwordNumder" placeholder='密码' maxlength="16" type='text' autocomplete="off">
            <div class='validation'>
                <img alt="" src='/shopping/public/static/admin_login/img/tick.png'>
            </div>
        </div>
        <div class='login_fields__password'>
            <div class='icon'>
                <img alt="" src='/shopping/public/static/admin_login/img/key.png'>
            </div>
            <input name="code" placeholder='验证码' maxlength="5"  class="ValidateNum" type='text' name="ValidateNum" autocomplete="off">
            <div class='validation' style="opacity: 1; right: -5px;top: -3px;">
                <canvas class="J_codeimg" id="myCanvas" onclick="Code();">对不起，您的浏览器不支持canvas，请下载最新版浏览器!</canvas>
            </div>
        </div>
        <div class='login_fields__submit'>
            <input type='button' value='登录'>
        </div>
    </div>
    <div class='success'>
    </div>
    <div class='disclaimer'>
        <p>欢迎登陆电商管理员平台</p>
    </div>
</div>
<div class='authent'>
    <div class="loader" style="height: 60px;width: 60px;margin-left: 28px;margin-top: 40px">
        <div class="loader-inner ball-clip-rotate-multiple">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <p>认证中...</p>
</div>
<div class="OverWindows"></div>
<link href="/shopping/public/static/admin_login/layui/css/layui.css" rel="stylesheet" type="text/css" />
<!--<script src="http://www.jq22.com/jquery/jquery-1.10.2.js"></script>-->
<script type="text/javascript" src="/shopping/public/static/admin_login/js/jquery-ui.min.js"></script>
<script type="text/javascript" src='/shopping/public/static/admin_login/js/stopExecutionOnTimeout.js?t=1'></script>
<script src="/shopping/public/static/admin_login/layui/layui.js" type="text/javascript"></script>
<script src="/shopping/public/static/admin_login/js/Particleground.js" type="text/javascript"></script>
<script src="/shopping/public/static/admin_login/js/Treatment.js" type="text/javascript"></script>
<script src="/shopping/public/static/admin_login/js/jquery.mockjax.js" type="text/javascript"></script>

<!--登录特效-->
<script>
    // \lkj20180323
    var canGetCookie = 1;//是否支持存储Cookie 0 不支持 1 支持
    var ajaxmockjax = 0;//是否启用虚拟Ajax的请求响 0 不启用  1 启用

    var CodeVal = 0;
    Code();
    function Code() {
        if(canGetCookie == 1){
            createCode("AdminCode");
            var AdminCode = getCookieValue("AdminCode");
            showCheck(AdminCode);
        }else{
            showCheck(createCode(""));
        }
    }
    function showCheck(a) {
        CodeVal = a;
        var c = document.getElementById("myCanvas");
        var ctx = c.getContext("2d");
        ctx.clearRect(0, 0, 1000, 1000);
        ctx.font = "80px 'Hiragino Sans GB'";
        ctx.fillStyle = "#E8DFE8";
        ctx.fillText(a, 0, 100);
    }
    $(document).keypress(function (e) {
        // 回车键事件
        if (e.which == 13) {
            $('input[type="button"]').click();
        }
    });
    //粒子背景特效
    $('body').particleground({
        dotColor: '#E8DFE8',
        lineColor: '#1b3273'
    });
    $('input[name="pwd"]').focus(function () {
        $(this).attr('type', 'password');
    });
    $('input[type="text"]').focus(function () {
        $(this).prev().animate({ 'opacity': '1' }, 200);
    });
    $('input[type="text"],input[type="password"]').blur(function () {
        $(this).prev().animate({ 'opacity': '.5' }, 200);
    });
    $('input[name="login"],input[name="pwd"]').keyup(function () {
        var Len = $(this).val().length;
        if (!$(this).val() == '' && Len >= 5) {
            $(this).next().animate({
                'opacity': '1',
                'right': '30'
            }, 200);
        } else {
            $(this).next().animate({
                'opacity': '0',
                'right': '20'
            }, 200);
        }
    });
    var open = 0;
    layui.use('layer', function () {
        //
        // 		var msgalert = '默认账号:' + truelogin + '<br/> 默认密码:' + truepwd;
        var msgalert = '欢迎登陆';
        var index = layer.alert(msgalert, { icon: 6, time: 4000, offset: 't', closeBtn: 0, title: '友情提示', btn: [], anim: 2, shade: 0 });
        layer.style(index, {
            color: '#777'
        });
        //     第一次弹出框
        //非空验证
        $('input[type="button"]').click(function () {
            var login = $('.username').val();
            var pwd = $('.passwordNumder').val();
            var code = $('.ValidateNum').val();
            if (login == '') {
                ErroAlert('请输入您的账号');
                return false;
            } else if (pwd == '') {

                ErroAlert('请输入密码');
                return false;
            } else if (code == '' || code.length != 5) {
                ErroAlert('验证码错误');
                return false;

            } else {
                //认证中..
                // fullscreen();
                //登陆
                var JsonData = { login: login, pwd: pwd, code: code };
                //此处做为ajax内部判断
                var url = "";
                if( JsonData.code.toUpperCase() == CodeVal.toUpperCase()) {
                    $('.login').addClass('test'); //倾斜特效
                    setTimeout(function () {
                        $('.login').addClass('testtwo'); //平移特效
                    }, 300);
                    setTimeout(function () {
                        $('.authent').show().animate({ right: -320 }, {
                            easing: 'easeOutQuint',
                            duration: 600,
                            queue: false
                        });
                        $('.authent').animate({ opacity: 1 }, {
                            duration: 200,
                            queue: false
                        }).addClass('visible');
                    }, 500);

                    $.ajax({
                        url: "<?php echo url('login/login'); ?>",
                        data: {
                            name1: login,
                            password1: pwd,
                            code:code
                        },
                        type:"post",
                        dataType:"json",
                        success: function (result) {
                            if (result.status == "ok") {
                                url = "Ajax/Login";
                                setTimeout(function(){
                                    var msgalert = result.message;
                                    var index = layer.alert(msgalert, { icon: 6, time: 4000, offset: 't', closeBtn: 0, title: '友情提示', btn: [], anim: 2, shade: 0 });
                                    layer.style(index, {
                                        color: '#777'
                                    });
                                    setTimeout(function(){
                                        location.href = "<?php echo url('index/index'); ?>";
                                    },1200);
                                },1500);
                            } else {
                                url = "Ajax/LoginFalse";
                                setTimeout(function(){
                                    ErroAlert(result.message)
                                    setTimeout(function(){
                                        location.href = "<?php echo url('login/index'); ?>";
                                    },2000);
                                },1500);
                            }
                        }
                    })
                }else{
                    ErroAlert("验证码错误")
                    return false;
                }
            }
            return false;
        })
    })
</script>
</body>
</html>
