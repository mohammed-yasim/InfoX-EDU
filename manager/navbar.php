<?php defined('INFOX') or die('No direct access allowed.');?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li><a href="/dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
            <li class="header">Institution Management</li>
            <li class="treeview" style="height: auto;">
                <a href="#"><i class="fa fa-link"></i><span>Core</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="/courses"><i class="fa fa-link"></i> <span>courses</span></a></li>
                    <li><a href="/employees"><i class="fa fa-link"></i> <span>Employees</span></a></li>
                    <li><a href="/subjects"><i class="fa fa-sticky-note"></i> <span>Subjects/Paper</span></a></li>
                    <li><a href="/clients"><i class="fa fa-link"></i> <span>Clients</span></a></li>
                    <li><a href="/users"><i class="fa fa-users"></i> <span>Users</span></a></li>
                </ul>
            </li>
            <li><a href="/content"><i class="fa fa-circle-o text-aqua"></i> <span>Content Manger</span></a></li>
            <li><a href="/content"><i class="fa fa-circle-o text-aqua"></i> <span>Content Manger</span></a></li>
            <li class="header">Subscriptions</li>
            <!----
            <li class="treeview" style="height: auto;">
                <a href="#"><i class="fa fa-link"></i><span>Content</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="/assignments"><i class="fa fa-tasks"></i> <span>Assignments</span></a></li>
                    <li><a href="/exams"><i class="fa fa-pencil"></i> <span>Exams</span></a></li>
                    <li><a href="/topics"><i class="fa fa-users"></i> <span>Topics</span></a></li>
                </ul>
            </li>
            <li class="treeview" style="height: auto;">
                <a href="#"><i class="fa fa-link"></i><span>Media</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li><a href="/audios"><i class="fa fa-microphone"></i> <span>Audio</span></a></li>
                    <li><a href="/documents"><i class="fa fa-file"></i> <span>Documents</span></a></li>
                    <li><a href="/videos"><i class="fa fa-video-camera"></i> <span>Videos</span></a></li>
                </ul>
            </li>
            ---->
        </ul>
        <script type="text/javascript">
            $(document).ready(() => {
                var loc = window.location.pathname;
                $('.sidebar-menu').find('a').each(function() {
                    if ($(this).attr('href') == loc) {
                        $(this).parents('li').addClass('active');
                        $(this).parents('ul').show()
                    }
                });
            });
        </script>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>