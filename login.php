<?php defined('INFOX') or die('No direct access allowed.');?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>InfoX-EDU | Log in</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/cdn/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/cdn/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/cdn/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/cdn/dist/css/AdminLTE.min.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<style>
.mybody{
    background-color:#fff;
  background-image:url('https://picsum.photos/1280/720');
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}
.login-box{
    position:fixed;
    top:0;left:0;right:0;bottom:0;
    margin:auto;
    max-height:50vh;
}
</style>
</head>
<body class="hold-transition login-page mybody" style="">
    <div class="login-box">
        <div class="login-box-body" style="    box-shadow: 0 0px 10px rgba(0,0,0,0.30), 0 0px 10px rgba(0,0,0,0.22);">
        <h3 class="text-center"><a><b>InfoX-EDU</b> 0.2</a></h3>
            <p class="login-box-msg text-danger"><?php if (isset($response['message'])) {
                                                    echo $response['message'];
                                                }else{echo("Sign in to start your session");} ?></p>
            <form method="post">
                <div class="form-group has-feedback">
                    <select class="form-control input-sm" required name="role">
                        <option value="" selected>Choose Role</option>
                        <option value="employee">Faculty / Staff / Teacher</option>
                        <option value="manager">Institutions / Managers</option>
                        <option value="admin">Admins / Moderator / Advisor</option>
                    </select>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Email/Username" name="username" required>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="test" value="0" checked="">PRODUCTION</label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="test" value="1">LOCAL/WAN</label>
                            </div>

                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="/cdn/j.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="/cdn/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- iCheck -->
</body>

</html>
<?php exit; ?>