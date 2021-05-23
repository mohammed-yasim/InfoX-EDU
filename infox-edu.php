<?php defined('INFOX') or die('No direct access allowed.'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>InfoX-EDU 1.0</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/cdn/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/cdn/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/cdn/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="/cdn/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
    <link rel="stylesheet" href="/cdn/dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/cdn/myclass.css">

    
    
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <script type="application/javascript" src="/cdn/rpm.js"></script>
    <script type="application/javascript" src="/cdn/rdpm.js"></script>
    <script type="application/javascript" src="/cdn/b.js"></script>
    <script type="application/javascript" src="/cdn/j.js"></script>
    <script type="application/javascript" src="/cdn/axios.js"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css" />
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css" />
    <link rel="stylesheet" href="/cdn/plugins/pace/pace.min.css">
    <script src="/cdn/plugins/pace/pace.min.js"></script>
    <style>
        body::-webkit-scrollbar {
            display: none;
            background-color: rosybrown;
        }
    </style>
    <style>
        .lds-spinner {
            color: official;
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
        }

        .lds-spinner div {
            transform-origin: 40px 40px;
            animation: lds-spinner 1.2s linear infinite;
        }

        .lds-spinner div:after {
            content: " ";
            display: block;
            position: absolute;
            top: 3px;
            left: 37px;
            width: 6px;
            height: 18px;
            border-radius: 20%;
            background: purple;
        }

        .lds-spinner div:nth-child(1) {
            transform: rotate(0deg);
            animation-delay: -1.1s;
        }

        .lds-spinner div:nth-child(2) {
            transform: rotate(30deg);
            animation-delay: -1s;
        }

        .lds-spinner div:nth-child(3) {
            transform: rotate(60deg);
            animation-delay: -0.9s;
        }

        .lds-spinner div:nth-child(4) {
            transform: rotate(90deg);
            animation-delay: -0.8s;
        }

        .lds-spinner div:nth-child(5) {
            transform: rotate(120deg);
            animation-delay: -0.7s;
        }

        .lds-spinner div:nth-child(6) {
            transform: rotate(150deg);
            animation-delay: -0.6s;
        }

        .lds-spinner div:nth-child(7) {
            transform: rotate(180deg);
            animation-delay: -0.5s;
        }

        .lds-spinner div:nth-child(8) {
            transform: rotate(210deg);
            animation-delay: -0.4s;
        }

        .lds-spinner div:nth-child(9) {
            transform: rotate(240deg);
            animation-delay: -0.3s;
        }

        .lds-spinner div:nth-child(10) {
            transform: rotate(270deg);
            animation-delay: -0.2s;
        }

        .lds-spinner div:nth-child(11) {
            transform: rotate(300deg);
            animation-delay: -0.1s;
        }

        .lds-spinner div:nth-child(12) {
            transform: rotate(330deg);
            animation-delay: 0s;
        }

        @keyframes lds-spinner {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }
    </style>
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="hold-transition skin-purple sidebar-mini fixed">
    <div class="wrapper">
        
        <header class="main-header">

            
            <a href="#" class="logo">
                
                <span class="logo-mini"><b>i</b>EDU</span>
                
                <span class="logo-lg"><b>InfoX-EDU</b> 1.0</span>
            </a>

            
            <nav class="navbar navbar-static-top" role="navigation">
                
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        
                        <li class="dropdown messages-menu">
                            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success">4</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    
                                    <ul class="menu">
                                        <li>
                                            
                                            <a href="#">
                                                <div class="pull-left">
                                                    
                                                    <img src="/cdn/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                                                </div>
                                                
                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
                                                
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                    
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>
                        

                        
                        <li class="dropdown notifications-menu">
                            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    
                                    <ul class="menu">
                                        <li>
                                            
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                        
                        <li class="dropdown tasks-menu">
                            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-flag-o"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 9 tasks</li>
                                <li>
                                    
                                    <ul class="menu">
                                        <li>
                                            
                                            <a href="#">
                                                
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                
                                                <div class="progress xs">
                                                    
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li>
                        
                        <li class="dropdown user user-menu">
                            
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                
                                <img src="/cdn/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                                
                                <span class="hidden-xs"><?php $user=$_SESSION['_name']; echo($user); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="?logout" class="btn btn-default btn-flat">Logout</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        

        <?php include(INFOX_PATH . '/navbar.php'); ?>
        
        <div class="content-wrapper">
            <div id="infox">
            </div>
            <?php if (file_exists(INFOX_PATH . $_SERVER['REQUEST_URI'] . '.php')) {
                include(INFOX_PATH . $_SERVER['REQUEST_URI'] . '.php');
            } else { ?>
                <h1>Error - Could Not Find The Module</h1>
            <?php } ?>
        </div>
        
        
        <footer class="main-footer">
            
            <div class="pull-right hidden-xs">
                Anything you want
            </div>
            
            <strong>Copyright &copy; 2021 <a href="#">Diyainfocare</a>.</strong> All rights reserved.
        </footer>
        
        <aside class="control-sidebar control-sidebar-dark" style="display: none;">
            
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            </ul>
            
            <div class="tab-content">
                
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <div style="width: 100%;">
                        <textarea class="form-control" style="width: 100%;" placeholder="Token"><?php echo (INFOX_TOKEN); ?></textarea>
                    </div>
                </div>
                
                
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                
                

                
            </div>
        </aside>
        
        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>

    <script src="/cdn/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/cdn/plugins/fastclick/lib/fastclick.js"></script>
    <script src="/cdn/dist/js/adminlte.min.js"></script>

    <script src="/cdn/dist/demo.js"></script>
</body>

</html>