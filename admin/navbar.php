<?php defined('INFOX') or die('No direct access allowed.');?>
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">HEADER</li>
            <!-- Optionally, you can add icons to the links -->
            <li><a href="/dashboard"><i class="fa fa-link"></i> <span>Dashboard</span></a></li>
            <li><a href="/institutions"><i class="fa fa-link"></i> <span>Institutions</span></a></li>
            <li><a href="/managers"><i class="fa fa-link"></i> <span>Institutions Manager</span></a></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#">Link in level 2</a></li>
                    <li><a href="#">Link in level 2</a></li>
                </ul>
            </li>
            <li class="header">HEADER</li>
            <li><a href="/dashboard"><i class="fa fa-link"></i> <span>Institutional Foundations</span></a></li>
            <li><a href="/dashboard"><i class="fa fa-link"></i> <span>Shared Courses</span></a></li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>