<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Avenger Angular</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Avenger">
    <meta name="author" content=" KaijuThemes">

    <!-- prochtml:remove:dist -->
    <link href="assets/less/styles.less" rel="stylesheet/less" media="all">
    <!-- /prochtml -->

    <link href='//fonts.googleapis.com/css?family=RobotoDraft:300,400,400italic,500,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700' rel='stylesheet' type='text/css'>

    <!-- The following CSS are included as plugins and can be removed if unused-->

    <!-- build:css assets/css/vendor.css -->
    <!-- bower:css -->
    <link rel="stylesheet" href="bower_components/chartist/dist/chartist.min.css" />
    <link rel="stylesheet" href="bower_components/angular-meditor/dist/meditor.min.css" />
    <link rel="stylesheet" href="bower_components/nanoscroller/bin/css/nanoscroller.css" />
    <link rel="stylesheet" href="bower_components/angular-ui-grid/ui-grid.css" />
    <link rel="stylesheet" href="bower_components/angular-ui-select/dist/select.css" />
    <link rel="stylesheet" href="bower_components/angular-ui-tree/dist/angular-ui-tree.min.css" />
    <link rel="stylesheet" href="bower_components/angular-xeditable/dist/css/xeditable.css" />
    <link rel="stylesheet" href="bower_components/nvd3/src/nv.d3.css" />
    <link rel="stylesheet" href="bower_components/animate.css/animate.css" />
    <link rel="stylesheet" href="bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" href="bower_components/bower-jvectormap/jquery-jvectormap-1.2.2.css" />
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="bower_components/fullcalendar/dist/fullcalendar.css" />
    <link rel="stylesheet" href="bower_components/ng-grid/ng-grid.css" />
    <link rel="stylesheet" href="bower_components/switchery/dist/switchery.css" />
    <link rel="stylesheet" href="bower_components/pnotify/pnotify.core.css" />
    <link rel="stylesheet" href="bower_components/pnotify/pnotify.buttons.css" />
    <link rel="stylesheet" href="bower_components/pnotify/pnotify.history.css" />
    <link rel="stylesheet" href="bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css" />
    <link rel="stylesheet" href="bower_components/skylo/vendor/styles/skylo.css" />
    <link rel="stylesheet" href="bower_components/textAngular/src/textAngular.css" />
    <link rel="stylesheet" href="bower_components/themify-icons/themify-icons.css" />
    <link rel="stylesheet" href="bower_components/weather-icons/css/weather-icons.min.css" />
    <!-- endbower -->
    <link rel='stylesheet' type="text/css" href="assets/plugins/form-nestable/jquery.nestable.css" />
    <link rel='stylesheet' type='text/css' href='assets/plugins/form-fseditor/fseditor.css' />
    <link rel='stylesheet' type='text/css' href='assets/plugins/jcrop/css/jquery.Jcrop.min.css' />
    <link rel="stylesheet" type='text/css' href="assets/plugins/iCheck/skins/all.css" />
    <link rel="stylesheet" type='text/css' href="assets/plugins/google-code-prettify/prettify.css" />
    <!-- endbuild -->

    <!-- build:css({.tmp,app}) assets/css/main.css -->
    <link rel="stylesheet" href="assets/css/styles.css">
    <!-- endbuild -->

    <!--[if lt IE 10]>
    {{ "js/media.match.min.js" | asset_url | script_tag }}
    {{ "js/respond.min.js" | asset_url | script_tag }}
    {{ "js/placeholder.min.js" | asset_url | script_tag }}
    <![endif]-->

    <!-- prochtml:remove:dist -->
    <script type="text/javascript">less = { env: 'development'};</script>
    <script type="text/javascript" src="assets/plugins/misc/less.js"></script>
    <!-- /prochtml -->
</head>

<body
        ng-app="themesApp"
        ng-controller="MainController"
        ng-class="{
              'static-header': !getLayoutOption('fixedHeader'),
              'focused-form': getLayoutOption('fullscreen'),
              'horizontal-nav': getLayoutOption('layoutHorizontal'),
              'layout-boxed': getLayoutOption('layoutBoxed'),
              'extrabar-show': getLayoutOption('extraBarShown'),
              'sidebar-collapsed': getLayoutOption('leftbarCollapsed') && !getLayoutOption('leftbarShown'),
              'show-sidebar': getLayoutOption('leftbarShown'),
              'infobar-active': getLayoutOption('rightbarCollapsed'),
            }"
        class="animated-content infobar-offcanvas"
        ng-click="hideHeaderBar();"
        to-top-on-load
        faux-offcanvas
        wijets
        >

<!-- HEADERBAR -->
<div id="headerbar"
     ng-class="{headerbarHide: getLayoutOption('headerBarHidden'), headerbarShow: !getLayoutOption('headerBarHidden')}"
     ng-show="!getLayoutOption('fullscreen')"
     ng-cloak
        >
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6 col-sm-2">
                <a href="#" class="shortcut-tile tile-brown">
                    <div class="tile-body">
                        <div class="pull-left"><i class="fa fa-pencil"></i></div>
                    </div>
                    <div class="tile-footer">
                        Create Post
                    </div>
                </a>
            </div>
            <div class="col-xs-6 col-sm-2">
                <a href="#" class="shortcut-tile tile-grape">
                    <div class="tile-body">
                        <div class="pull-left"><i class="fa fa-group"></i></div>
                        <div class="pull-right"><span class="badge">2</span></div>
                    </div>
                    <div class="tile-footer">
                        Contacts
                    </div>
                </a>
            </div>
            <div class="col-xs-6 col-sm-2">
                <a href="#" class="shortcut-tile tile-primary">
                    <div class="tile-body">
                        <div class="pull-left"><i class="fa fa-envelope-o"></i></div>
                        <div class="pull-right"><span class="badge">10</span></div>
                    </div>
                    <div class="tile-footer">
                        Messages
                    </div>
                </a>
            </div>
            <div class="col-xs-6 col-sm-2">
                <a href="#" class="shortcut-tile tile-inverse">
                    <div class="tile-body">
                        <div class="pull-left"><i class="fa fa-camera"></i></div>
                        <div class="pull-right"><span class="badge">3</span></div>
                    </div>
                    <div class="tile-footer">
                        Gallery
                    </div>
                </a>
            </div>

            <div class="col-xs-6 col-sm-2">
                <a href="#" class="shortcut-tile tile-midnightblue">
                    <div class="tile-body">
                        <div class="pull-left"><i class="fa fa-cog"></i></div>
                    </div>
                    <div class="tile-footer">
                        Settings
                    </div>
                </a>
            </div>
            <div class="col-xs-6 col-sm-2">
                <a href="#" class="shortcut-tile tile-orange">
                    <div class="tile-body">
                        <div class="pull-left"><i class="fa fa-wrench"></i></div>
                    </div>
                    <div class="tile-footer">
                        Plugins
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- /HEADERBAR -->

<!-- HEADER -->
<header
        class="navbar ng-hide clearfix {{getLayoutOption('topNavThemeClass')}}"
        ng-class="{'navbar-fixed-top': getLayoutOption('fixedHeader'), 'navbar-static-top': !getLayoutOption('fixedHeader')}"
        role="banner"
        ng-show="!layoutLoading"
        ng-cloak
        id="topnav">

<span id="trigger-sidebar" class="toolbar-trigger toolbar-icon-bg" ng-click="toggleLeftBar()" ng-show="!getLayoutOption('layoutHorizontal')">
        <a data-toggle="tooltips" data-placement="right" title="Toggle Sidebar"><span class="icon-bg"><i class="fa fa-fw fa-bars"></i></span></a>
    </span>

<a class="navbar-brand" href="#/">Avenger</a>

    <span id="trigger-infobar" class="toolbar-trigger toolbar-icon-bg" ng-click="toggleRightBar()">
        <a data-toggle="tooltips" data-placement="left" title="Toggle Infobar"><span class="icon-bg"><i class="fa fa-fw fa-bars"></i></span></a>
    </span>

<div class="yamm navbar-left navbar-collapse collapse in hidden-xs">
    <ul class="nav navbar-nav">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown">Megamenu <span class="caret"></span></a>
            <ul class="dropdown-menu" style="width: 900px;">
                <li>
                    <div class="yamm-content container-sm-height">
                        <div class="row row-sm-height yamm-col-bordered">
                            <div class="col-sm-3 col-sm-height yamm-col">

                                <h3 class="yamm-category">Sidebar</h3>
                                <ul class="list-unstyled mb20">
                                    <li><a href="layout-fixed-sidebars.html">Stretch Sidebars</a></li>
                                    <li><a href="layout-sidebar-scroll.html">Scroll Sidebar</a></li>
                                    <li><a href="layout-static-leftbar.html">Static Sidebar</a></li>
                                    <li><a href="layout-leftbar-widgets.html">Sidebar Widgets</a></li>
                                </ul>

                                <h3 class="yamm-category">Infobar</h3>
                                <ul class="list-unstyled">
                                    <li><a href="layout-infobar-offcanvas.html">Offcanvas Infobar</a></li>
                                    <li><a href="layout-infobar-overlay.html">Overlay Infobar</a></li>
                                    <li><a href="layout-chatbar-overlay.html">Chatbar</a></li>
                                    <li><a href="layout-rightbar-widgets.html">Infobar Widgets</a></li>
                                </ul>

                            </div>
                            <div class="col-sm-3 col-sm-height yamm-col">

                                <h3 class="yamm-category">Page Content</h3>
                                <ul class="list-unstyled mb20">
                                    <li><a href="layout-breadcrumb-top.html">Breadcrumbs on Top</a></li>
                                    <li><a href="layout-page-tabs.html">Page Tabs</a></li>
                                    <li><a href="layout-fullheight-panel.html">Full-Height Panel</a></li>
                                    <li><a href="layout-fullheight-content.html">Full-Height Content</a></li>
                                </ul>

                                <h3 class="yamm-category">Misc</h3>
                                <ul class="list-unstyled">
                                    <li><a href="layout-topnav-options.html">Topnav Options</a></li>
                                    <li><a href="layout-horizontal-small.html">Horizontal Small</a></li>
                                    <li><a href="layout-horizontal-large.html">Horizontal Large</a></li>
                                    <li><a href="layout-boxed.html">Boxed</a></li>
                                </ul>

                            </div>
                            <div class="col-sm-3 col-sm-height yamm-col">

                                <h3 class="yamm-category">Analytics</h3>
                                <ul class="list-unstyled mb20">
                                    <li><a href="charts-flot.html">Flot</a></li>
                                    <li><a href="charts-sparklines.html">Sparklines</a></li>
                                    <li><a href="charts-morris.html">Morris</a></li>
                                    <li><a href="charts-easypiechart.html">Easy Pie Charts</a></li>
                                </ul>

                                <h3 class="yamm-category">Components</h3>
                                <ul class="list-unstyled">
                                    <li><a href="ui-tiles.html">Tiles</a></li>
                                    <li><a href="custom-knob.html">jQuery Knob</a></li>
                                    <li><a href="custom-jqueryui.html">jQuery Slider</a></li>
                                    <li><a href="custom-ionrange.html">Ion Range Slider</a></li>
                                </ul>

                            </div>
                            <div class="col-sm-3 col-sm-height yamm-col">
                                <h3 class="yamm-category">Rem</h3>
                                <img src="assets/demo/stockphoto/communication_12_carousel.jpg" class="mb20 img-responsive" style="width: 100%;">
                                <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium. totam rem aperiam eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </li>
        <li class="dropdown" id="widget-classicmenu">
            <a class="dropdown-toggle" data-toggle="dropdown">Dropdown<span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#">Action</a></li>
                <li><a href="#">Another action</a></li>
                <li><a href="#">Something else here</a></li>
                <li class="divider"></li>
                <li><a href="#">Separated link</a></li>
                <li class="divider"></li>
                <li><a href="#">One more separated link</a></li>
            </ul>
        </li>
    </ul>
</div>

<ul class="nav navbar-nav toolbar pull-right">
    <li class="dropdown toolbar-icon-bg" autocollapse>
        <a href="#" id="navbar-links-toggle" data-toggle="collapse" data-target="header>.navbar-collapse">
                <span class="icon-bg">
                    <i class="fa fa-fw fa-ellipsis-h"></i>
                </span>
        </a>
    </li>

    <li class="dropdown toolbar-icon-bg demo-search-hidden">
        <a href="#" class="dropdown-toggle tooltips" data-toggle="dropdown"><span class="icon-bg"><i class="fa fa-fw fa-search"></i></span></a>

        <div class="dropdown-menu dropdown-alternate arrow search dropdown-menu-form">
            <div class="dd-header">
                <span>Search</span>
                <span><a href="#">Advanced search</a></span>
            </div>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="">
                    
                    <span class="input-group-btn">
                        
                        <a class="btn btn-primary" href="#">Search</a>
                    </span>
            </div>
        </div>
    </li>

    <li class="toolbar-icon-bg demo-headerdrop-hidden" ng-click="showHeaderBar($event)">
        <a id="headerbardropdown"><span class="icon-bg"><i class="fa fa-fw fa-level-down"></i></span></i></a>
    </li>

    <li class="toolbar-icon-bg hidden-xs" toggle-fullscreen >
        <a href="#" class="toggle-fullscreen"><span class="icon-bg"><i class="fa fa-fw fa-arrows-alt"></i></span></i></a>
    </li>

    <li class="dropdown toolbar-icon-bg" dropdown ng-controller="NotificationsController" ng-show="isLoggedIn">
        <a href dropdown-toggle class="hasnotifications">
                <span class="icon-bg">
                    <i class="fa fa-fw fa-bell"></i>
                </span>
            <span class="badge badge-info" ng-if="unseenCount>0" ng-bind="unseenCount"></span>
        </a>
        <div class="dropdown-menu dropdown-alternate animated notifications arrow">
            <div class="dd-header">
                <span>Notifications</span>
                <span><a href="#/">Settings</a></span>
            </div>
            <div class="scrollthis scroll-pane" >
                <ul class="scroll-content">
                    <li ng-repeat="item in notifications">
                        <a href="#/" class="{{item.class}}">
                            <div class="notification-icon"><i class="fa {{item.iconClasses}} fa-fw"></i></div>
                            <div class="notification-content">{{item.text}}</div>
                            <div class="notification-time">{{item.time}}</div>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="dd-footer">
                <a href="#/">See all notifications</a>
            </div>
        </div>
    </li>

    <li class="dropdown toolbar-icon-bg hidden-xs" dropdown ng-controller="MessagesController" ng-show="isLoggedIn">
        <a href dropdown-toggle class="hasnotifications">
                <span class="icon-bg">
                    <i class="fa fa-fw fa-envelope"></i>
                </span>
        </a>
        <div class="dropdown-menu dropdown-alternate messages arrow">
            <div class="dd-header">
                <span>Messages</span>
                <span><a href="#/">Settings</a></span>
            </div>

            <div class="scrollthis scroll-pane">
                <ul class="scroll-content">
                    <li ng-repeat="item in messages">
                        <a href>
                            <img class="msg-avatar" ng-src="{{item.thumb}}" alt="avatar" />
                            <div class="msg-content">
                                <span class="name">{{item.name}}</span>
                                <span class="msg">{{item.message}}</span>
                            </div>
                            <span class="msg-time">{{item.time}}</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="dd-footer"><a href="#">View all messages</a></div>
        </div>
    </li>



    <li class="dropdown toolbar-icon-bg">
        <a href="#" class="dropdown-toggle" data-toggle='dropdown'><span class="icon-bg"><i class="fa fa-fw fa-user"></i></span></a>
        <ul class="dropdown-menu userinfo arrow">
            <li><a href="#"><span class="pull-left">Profile</span> <span class="badge badge-info">80%</span></a></li>
            <li><a href="#"><span class="pull-left">Account</span> <i class="pull-right fa fa-user"></i></a></li>
            <li><a href="#"><span class="pull-left">Settings</span> <i class="pull-right fa fa-cog"></i></a></li>
            <li class="divider"></li>
            <li><a href="#"><span class="pull-left">Earnings</span> <i class="pull-right fa fa-line-chart"></i></a></li>
            <li><a href="#"><span class="pull-left">Statement</span> <i class="pull-right fa fa-list-alt"></i></a></li>
            <li><a href="#"><span class="pull-left">Withdrawals</span> <i class="pull-right fa fa-dollar"></i></a></li>
            <li class="divider"></li>
            <li><a href="#"><span class="pull-left">Sign Out</span> <i class="pull-right fa fa-sign-out"></i></a></li>
        </ul>
    </li>

</ul>

</header>

<!-- /HEADER -->

<div id="wrapper">
    <nav id="headernav" class="navbar ng-hide {{getLayoutOption('sidebarThemeClass').replace('sidebar', 'navbar')}}" role="navigation" ng-show="getLayoutOption('layoutHorizontal') && !layoutLoading">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <i class="fa fa-reorder"></i>
            </button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse" ng-class="{'large-icons-nav': getLayoutOption('layoutHorizontalLargeIcons')}" id="horizontal-navbar">
            <ul ng-controller="NavigationController" class="nav navbar-nav">
                <li ng-repeat="item in menu"
                    ng-if="!(item.hideOnHorizontal||item.separator)"
                    ng-class="{ hasChild: (item.children!==undefined),
                                        active: item.selected,
                                          open: (item.children!==undefined) && item.open,
                               'nav-separator': item.separator==true }"
                    ng-include="'templates/nav_renderer_horizontal.html'"
                        ></li>
            </ul>
        </div>
    </nav>
    <div id="layout-static">
        <div class="static-sidebar-wrapper {{getLayoutOption('sidebarThemeClass')}}" ng-show="!layoutLoading">
            <div class="static-sidebar" role="navigation">
                <div class="sidebar" ng-cloak>
                    <div class="widget stay-on-collapse" id="widget-welcomebox">
                        <div class="widget-body welcome-box tabular">
                            <div class="tabular-row">
                                <div class="tabular-cell welcome-avatar">
                                    <a href="#"><img src="assets/demo/avatar/avatar_02.png" class="avatar"></a>
                                </div>
                                <div class="tabular-cell welcome-options">
                                    <span class="welcome-text">Welcome,</span>
                                    <a href="#" class="name">Jonathan Smith</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="widget stay-on-collapse">
                        <nav class="widget-body">
                            <ul ng-controller="NavigationController" class="acc-menu" sticky-scroll="40">
                                <li ng-repeat="item in menu"
                                    ng-class="{ hasChild: (item.children!==undefined),
                                                    active: item.selected,
                                                      open: (item.children!==undefined) && item.open,
                                           'nav-separator': item.separator==true,
                                            'search-focus': (searchQuery.length>0 && item.selected) }"
                                    ng-show="!(searchQuery.length && !item.selected)"
                                    ng-include="'templates/nav_renderer.html'"
                                        ></li>
                            </ul>
                        </nav>
                    </div>

                </div>
            </div> <!-- #sidebar-->
        </div>
        <div class="static-content-wrapper">
            <div class="static-content">
                <div class="page-content mainview-animation animated" ng-view="">
                </div> <!--wrap -->
            </div>
            <footer role="contentinfo" ng-show="!layoutLoading" ng-cloak>
                <div class="clearfix">
                    <ul class="list-unstyled list-inline pull-left">
                        <li><h6 style="margin: 0;">&copy; 2015 KaijuThemes</h6></li>
                    </ul>
                    <button class="pull-right btn btn-link btn-xs hidden-print" back-to-top><i class="ti ti-arrow-up"></i></button>
                </div>
            </footer>
        </div>
    </div>
</div>

<div class="infobar-wrapper scroll-pane scrollthis">
    <div class="infobar scroll-content">

        <div id="widgetarea">

            <div class="widget" id="widget-sparkline">
                <div class="widget-heading">
                    <a href="javascript:;" data-toggle="collapse" data-target="#sparklinestats"><h4>Sparkline Stats</h4></a>
                </div>
                <div id="sparklinestats" class="collapse in">
                    <div class="widget-body">
                        <ul class="sparklinestats">
                            <li>
                                <div class="title">Earnings</div>
                                <div class="stats">$22,500</div>
                                <div
                                        sparklines="{
                              type: 'line',
                              lineColor: '#6678c1',
                              fillColor: '#e9ecf5',
                              height: '32',
                              lineWidth: 1.125,
                              width: '100%',
                              spotRadius: 0
                            }" data-data="[120,160,130,230,170,200,80,60,150,190,240,320,290,200,240,190,130,150,230,180,80,20,90,110,200,240,210,250]"
                                        ></div>
                            </li>
                            <li>
                                <div class="title">Orders</div>
                                <div class="stats">4,750</div>
                                <div
                                        sparklines="{
                              type: 'line',
                              lineColor: '#7f96a0',
                              fillColor: '#edf0f2',
                              height: '32',
                              lineWidth: 1.125,
                              width: '100%',
                              spotRadius: 0
                            }" data-data="[240,200,230,180,170,120,90,30,100,120,180,150,190,270,280,320,250,170,230,170,90,110,200,190,220,110,150,130]"
                                        ></div>
                            </li>
                        </ul>
                        <a href="#" class="more">More Sparklines</a>
                    </div>
                </div>
            </div>

            <div class="widget">
                <div class="widget-heading">
                    <a href="javascript:;" data-toggle="collapse" data-target="#recentactivity"><h4>Recent Activity</h4></a>
                </div>
                <div id="recentactivity" class="collapse in">
                    <div class="widget-body">
                        <ul class="recent-activities">
                            <li>
                                <div class="avatar">
                                    <img src="assets/demo/avatar/avatar_11.png" class="img-responsive img-circle">
                                </div>
                                <div class="content">
                                    <span class="msg"><a href="#" class="person">Jean Alanis</a> invited 3 unconfirmed members</span>
                                    <span class="time">2 mins ago</span>

                                </div>
                            </li>
                            <li>
                                <div class="avatar">
                                    <img src="assets/demo/avatar/avatar_09.png" class="img-responsive img-circle">
                                </div>
                                <div class="content">
                                    <span class="msg"><a href="#" class="person">Anthony Ware</a> is now following you</span>
                                    <span class="time">4 hours ago</span>

                                </div>
                            </li>
                            <li>
                                <div class="avatar">
                                    <img src="assets/demo/avatar/avatar_04.png" class="img-responsive img-circle">
                                </div>
                                <div class="content">
                                    <span class="msg"><a href="#" class="person">Bruce Ory</a> commented on <a href="#">Dashboard UI</a></span>
                                    <span class="time">16 hours ago</span>
                                </div>
                            </li>
                            <li>
                                <div class="avatar">
                                    <img src="assets/demo/avatar/avatar_01.png" class="img-responsive img-circle">
                                </div>
                                <div class="content">
                                    <span class="msg"><a href="#" class="person">Roxann Hollingworth</a>is now following you</span>
                                    <span class="time">Feb 13, 2015</span>
                                </div>
                            </li>
                        </ul>
                        <a href="#" class="more">See all activities</a>
                    </div>
                </div>
            </div>

            <div class="widget" >
                <div class="widget-heading">
                    <a href="javascript:;" data-toggle="collapse" data-target="#widget-milestones"><h4>Milestones</h4></a>
                </div>
                <div id="widget-milestones" class="collapse in">
                    <div class="widget-body">
                        <div class="contextual-progress">
                            <div class="clearfix">
                                <div class="progress-title">UI Design</div>
                                <div class="progress-percentage">12/16</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-lime" style="width: 75%"></div>
                            </div>
                        </div>
                        <div class="contextual-progress">
                            <div class="clearfix">
                                <div class="progress-title">UX Design</div>
                                <div class="progress-percentage">8/24</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-orange" style="width: 33.3%"></div>
                            </div>
                        </div>
                        <div class="contextual-progress">
                            <div class="clearfix">
                                <div class="progress-title">Frontend Development</div>
                                <div class="progress-percentage">8/40</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-purple" style="width: 20%"></div>
                            </div>
                        </div>
                        <div class="contextual-progress m0">
                            <div class="clearfix">
                                <div class="progress-title">Backend Development</div>
                                <div class="progress-percentage">24/48</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar progress-bar-danger" style="width: 50%"></div>
                            </div>
                        </div>
                        <a href="#" class="more">See All</a>
                    </div>
                </div>
            </div>

            <div class="widget">
                <div class="widget-heading">
                    <a href="javascript:;" data-toggle="collapse" data-target="#widget-contact"><h4>Contacts</h4></a>
                </div>
                <div id="widget-contact" class="collapse in">
                    <div class="widget-body">
                        <ul class="contact-list">
                            <li id="contact-1">
                                <a href="javascript:;"><img src="assets/demo/avatar/avatar_02.png" alt=""><span>Jeremy Potter</span></a>
                            </li>
                            <li id="contact-2">
                                <a href="javascript:;"><img src="assets/demo/avatar/avatar_07.png" alt=""><span>David Tennant</span></a>
                            </li>
                            <li id="contact-3">
                                <a href="javascript:;"><img src="assets/demo/avatar/avatar_03.png" alt=""><span>Anna Johansson</span></a>
                            </li>
                            <li id="contact-4">
                                <a href="javascript:;"><img src="assets/demo/avatar/avatar_09.png" alt=""><span>Alan Doyle</span></a>
                            </li>
                            <li id="contact-5">
                                <a href="javascript:;"><img src="assets/demo/avatar/avatar_05.png" alt=""><span>Simon Corbett</span></a>
                            </li>
                            <li id="contact-6">
                                <a href="javascript:;"><img src="assets/demo/avatar/avatar_01.png" alt=""><span>Polly Paton</span></a>
                            </li>
                        </ul>
                        <a href="#" class="more">See All</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Switcher -->
<div class="demo-options">
    <div class="demo-options-icon"><i class="fa fa-spin fa-fw fa-smile-o"></i></div>
    <div class="demo-heading">Demo Settings</div>

    <div class="demo-body">
        <div class="tabular">
            <div class="tabular-row">
                <div class="tabular-cell">Fixed Header</div>
                <div class="tabular-cell demo-switches">
                    <input type="checkbox" ng-model="layoutFixedHeader" class="js-switch switchery-xs" ui-switch="{color: '#8bc34a', secondaryColor: '#e0e0e0', size: 'small'}">
                </div>
            </div>
            <div class="tabular-row">
                <div class="tabular-cell">Boxed Layout</div>
                <div class="tabular-cell demo-switches">
                    <input type="checkbox" ng-model="layoutLayoutBoxed" class="js-switch switchery-xs" ui-switch="{color: '#8bc34a', secondaryColor: '#e0e0e0', size: 'small'}">
                </div>
            </div>
            <div class="tabular-row">
                <div class="tabular-cell">Collapse Leftbar</div>
                <div class="tabular-cell demo-switches">
                    <input type="checkbox" ng-model="layoutLeftbarCollapsed" class="js-switch switchery-xs" ui-switch="{color: '#8bc34a', secondaryColor: '#e0e0e0', size: 'small'}">
                </div>
            </div>
        </div>

    </div>

    <div class="demo-body">
        <div class="option-title">Header Colors</div>
        <ul id="demo-header-color" class="demo-color-list">
            <li><span class="demo-white" ng-click="setNavbarClass('navbar-default', $event)"></span></li>
            <li><span class="demo-black" ng-click="setNavbarClass('navbar-inverse', $event)"></span></li>
            <li><span class="demo-midnightblue" ng-click="setNavbarClass('navbar-midnightblue', $event)"></span></li>
            <li><span class="demo-primary" ng-click="setNavbarClass('navbar-primary', $event)"></span></li>
            <li><span class="demo-info" ng-click="setNavbarClass('navbar-info', $event)"></span></li>
            <li><span class="demo-alizarin" ng-click="setNavbarClass('navbar-alizarin', $event)"></span></li>
            <li><span class="demo-green" ng-click="setNavbarClass('navbar-green', $event)"></span></li>
            <li><span class="demo-violet" ng-click="setNavbarClass('navbar-violet', $event)"></span></li>
            <li><span class="demo-indigo" ng-click="setNavbarClass('navbar-indigo', $event)"></span></li>
        </ul>
    </div>

    <div class="demo-body">
        <div class="option-title">Sidebar</div>
        <ul id="demo-sidebar-color" class="demo-color-list">
            <li><span class="demo-white" ng-click="setSidebarClass('sidebar-white', $event)"></span></li>
            <li><span class="demo-black" ng-click="setSidebarClass('sidebar-inverse', $event)"></span></li>
            <li><span class="demo-midnightblue" ng-click="setSidebarClass('sidebar-midnightblue', $event)"></span></li>
            <li><span class="demo-primary" ng-click="setSidebarClass('sidebar-primary', $event)"></span></li>
            <li><span class="demo-info" ng-click="setSidebarClass('sidebar-info', $event)"></span></li>
            <li><span class="demo-alizarin" ng-click="setSidebarClass('sidebar-alizarin', $event)"></span></li>
            <li><span class="demo-green" ng-click="setSidebarClass('sidebar-green', $event)"></span></li>
            <li><span class="demo-violet" ng-click="setSidebarClass('sidebar-violet', $event)"></span></li>
            <li><span class="demo-indigo" ng-click="setSidebarClass('sidebar-indigo', $event)"></span></li>
        </ul>
    </div>
</div>
<!-- /Switcher -->

<!--[if lt IE 9]>
<script src="bower_components/es5-shim/es5-shim.js"></script>
<script src="bower_components/json3/lib/json3.min.js"></script>
<![endif]-->

<script type='text/javascript' src='//maps.google.com/maps/api/js?sensor=true'></script>

<!-- build:js scripts/vendor.js -->
<!-- bower:js -->
<script src="bower_components/modernizr/modernizr.js"></script>
<script src="bower_components/jquery/dist/jquery.js"></script>
<script src="bower_components/angular/angular.js"></script>
<script src="bower_components/angular-animate/angular-animate.js"></script>
<script src="bower_components/angular-bootstrap/ui-bootstrap-tpls.js"></script>
<script src="bower_components/chartist/dist/chartist.min.js"></script>
<script src="bower_components/angular-chartist.js/dist/angular-chartist.js"></script>
<script src="bower_components/angular-cookies/angular-cookies.js"></script>
<script src="bower_components/angular-meditor/dist/meditor.min.js"></script>
<script src="bower_components/nanoscroller/bin/javascripts/jquery.nanoscroller.js"></script>
<script src="bower_components/angular-nanoscroller/scrollable.js"></script>
<script src="bower_components/angular-resource/angular-resource.js"></script>
<script src="bower_components/angular-route/angular-route.js"></script>
<script src="bower_components/angular-sanitize/angular-sanitize.js"></script>
<script src="bower_components/skycons/skycons.js"></script>
<script src="bower_components/angular-skycons/angular-skycons.js"></script>
<script src="bower_components/angular-ui-grid/ui-grid.js"></script>
<script src="bower_components/angular-ui-select/dist/select.js"></script>
<script src="bower_components/angular-ui-tree/dist/angular-ui-tree.js"></script>
<script src="bower_components/angular-xeditable/dist/js/xeditable.js"></script>
<script src="bower_components/d3/d3.js"></script>
<script src="bower_components/nvd3/nv.d3.js"></script>
<script src="bower_components/angularjs-nvd3-directives/dist/angularjs-nvd3-directives.js"></script>
<script src="bower_components/bootstrap/dist/js/bootstrap.js"></script>
<script src="bower_components/bootbox.js/bootbox.js"></script>
<script src="bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="bower_components/moment/moment.js"></script>
<script src="bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="bower_components/bower-jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="bower_components/bower-jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="bower_components/card/lib/js/jquery.card.js"></script>
<script src="bower_components/enquire/dist/enquire.js"></script>
<script src="bower_components/flot/jquery.flot.js"></script>
<script src="bower_components/flot/jquery.flot.stack.js"></script>
<script src="bower_components/flot/jquery.flot.pie.js"></script>
<script src="bower_components/flot/jquery.flot.resize.js"></script>
<script src="bower_components/flot.tooltip/js/jquery.flot.tooltip.js"></script>
<script src="bower_components/gmaps/gmaps.js"></script>
<script src="bower_components/google-code-prettify/src/prettify.js"></script>
<script src="bower_components/iCheck/icheck.min.js"></script>
<script src="bower_components/jquery-autosize/jquery.autosize.js"></script>
<script src="bower_components/jquery.easing/js/jquery.easing.js"></script>
<script src="bower_components/jquery.easy-pie-chart/dist/angular.easypiechart.js"></script>
<script src="bower_components/jquery.knob/js/jquery.knob.js"></script>
<script src="bower_components/jquery.pulsate/jquery.pulsate.js"></script>
<script src="bower_components/jquery.sparkline/index.js"></script>
<script src="bower_components/jquery.ui/ui/jquery.ui.core.js"></script>
<script src="bower_components/jquery.ui/ui/jquery.ui.widget.js"></script>
<script src="bower_components/jquery.ui/ui/jquery.ui.mouse.js"></script>
<script src="bower_components/jquery.ui/ui/jquery.ui.draggable.js"></script>
<script src="bower_components/jquery.ui/ui/jquery.ui.sortable.js"></script>
<script src="bower_components/jquery.ui/ui/jquery.ui.resizable.js"></script>
<script src="bower_components/flow.js/dist/flow.js"></script>
<script src="bower_components/ng-flow/dist/ng-flow.js"></script>
<script src="bower_components/ng-grid/build/ng-grid.js"></script>
<script src="bower_components/transitionize/dist/transitionize.js"></script>
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<script src="bower_components/switchery/dist/switchery.js"></script>
<script src="bower_components/ng-switchery/src/ng-switchery.js"></script>
<script src="bower_components/oclazyload/dist/ocLazyLoad.min.js"></script>
<script src="bower_components/pnotify/pnotify.core.js"></script>
<script src="bower_components/pnotify/pnotify.buttons.js"></script>
<script src="bower_components/pnotify/pnotify.callbacks.js"></script>
<script src="bower_components/pnotify/pnotify.confirm.js"></script>
<script src="bower_components/pnotify/pnotify.desktop.js"></script>
<script src="bower_components/pnotify/pnotify.history.js"></script>
<script src="bower_components/pnotify/pnotify.nonblock.js"></script>
<script src="bower_components/seiyria-bootstrap-slider/js/bootstrap-slider.js"></script>
<script src="bower_components/shufflejs/dist/jquery.shuffle.js"></script>
<script src="bower_components/skylo/vendor/scripts/skylo.js"></script>
<script src="bower_components/rangy/rangy-core.js"></script>
<script src="bower_components/rangy/rangy-classapplier.js"></script>
<script src="bower_components/rangy/rangy-highlighter.js"></script>
<script src="bower_components/rangy/rangy-selectionsaverestore.js"></script>
<script src="bower_components/rangy/rangy-serializer.js"></script>
<script src="bower_components/rangy/rangy-textrange.js"></script>
<script src="bower_components/textAngular/src/textAngular.js"></script>
<script src="bower_components/textAngular/src/textAngular-sanitize.js"></script>
<script src="bower_components/textAngular/src/textAngularSetup.js"></script>
<script src="bower_components/rangy/rangy-selectionsaverestore.js"></script>
<script src="bower_components/underscore/underscore.js"></script>
<script src="bower_components/velocity/velocity.js"></script>
<script src="bower_components/velocity/velocity.ui.js"></script>
<!-- endbower -->

<script type="text/javascript" src="assets/plugins/form-nestable/jquery.nestable.min.js"></script>
<script type='text/javascript' src='assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js'></script>
<script type='text/javascript' src='assets/plugins/form-fseditor/jquery.fseditor-min.js'></script>
<script type='text/javascript' src='assets/plugins/form-jasnyupload/fileinput.min.js'></script>
<script type='text/javascript' src='assets/plugins/flot/jquery.flot.spline.js'></script>
<script type='text/javascript' src='assets/plugins/flot/jquery.flot.orderBars.js'></script>
<script type='text/javascript' src='assets/plugins/wijets/wijets.js'></script>
<script type='text/javascript' src="assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script>
<script type='text/javascript' src="assets/plugins/chartist-plugin-tooltip/chartist-plugin-tooltip.min.js"></script>

<!-- endbuild -->

<!-- build:js({.tmp,app}) scripts/scripts.js -->
<script src="scripts/core/controllers/mainController.js"></script>
<script src="scripts/core/controllers/navigationController.js"></script>
<script src="scripts/core/controllers/notificationsController.js"></script>
<script src="scripts/core/controllers/messagesController.js"></script>
<script src="scripts/core/directives/directives.js"></script>
<script src="scripts/core/directives/form.js"></script>
<script src="scripts/core/directives/ui.js"></script>
<script src="scripts/core/modules/templateOverrides.js"></script>
<script src="scripts/core/modules/templates.js"></script>
<script src="scripts/core/services/services.js"></script>
<script src="scripts/core/services/theme.js"></script>
<script src="scripts/core/theme.js"></script>
<script src="scripts/calendar/calendar.js"></script>
<script src="scripts/chart/canvas.js"></script>
<script src="scripts/chart/flot.js"></script>
<script src="scripts/chart/morris.js"></script>
<script src="scripts/chart/sparklines.js"></script>
<script src="scripts/gallery/gallery.js"></script>
<script src="scripts/map/googleMaps.js"></script>
<script src="scripts/map/vectorMaps.js"></script>
<script src="scripts/demos/modules/basicTables.js"></script>
<script src="scripts/demos/modules/boxedLayout.js"></script>
<script src="scripts/demos/modules/calendar.js"></script>
<script src="scripts/demos/modules/canvasCharts.js"></script>
<script src="scripts/demos/modules/chartistCharts.js"></script>
<script src="scripts/demos/modules/nvd3Charts.js"></script>
<script src="scripts/demos/modules/chatBox.js"></script>
<script src="scripts/demos/modules/editableTable.js"></script>
<script src="scripts/demos/modules/flotCharts.js"></script>
<script src="scripts/demos/modules/form/form.js"></script>
<script src="scripts/demos/modules/form/controllers/angularFormValidationController.js"></script>
<script src="scripts/demos/modules/form/controllers/datepickerDemoController.js"></script>
<script src="scripts/demos/modules/form/controllers/dateRangePickerDemoController.js"></script>
<script src="scripts/demos/modules/form/controllers/formComponentsController.js"></script>
<script src="scripts/demos/modules/form/controllers/imageCropController.js"></script>
<script src="scripts/demos/modules/form/controllers/inlineEditableController.js"></script>
<script src="scripts/demos/modules/form/controllers/timepickerDemoController.js"></script>
<script src="scripts/demos/modules/gallery.js"></script>
<script src="scripts/demos/modules/googleMaps.js"></script>
<script src="scripts/demos/modules/horizontalLayout.js"></script>
<script src="scripts/demos/modules/mail/controllers/composeController.js"></script>
<script src="scripts/demos/modules/mail/controllers/inboxController.js"></script>
<script src="scripts/demos/modules/mail/mail.js"></script>
<script src="scripts/demos/modules/morrisCharts.js"></script>
<script src="scripts/demos/modules/sparklineCharts.js"></script>
<script src="scripts/demos/modules/ngGrid.js"></script>
<script src="scripts/demos/modules/signupPage.js"></script>
<script src="scripts/demos/modules/notFoundController.js"></script>
<script src="scripts/demos/modules/errorPageController.js"></script>
<script src="scripts/demos/modules/apps.js"></script>
<script src="scripts/demos/modules/ui-components/uiComponents.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/alertsController.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/carouselController.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/modalsController.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/paginationsController.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/progressbarsController.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/ratingsController.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/slidersController.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/tabsController.js"></script>
<script src="scripts/demos/modules/ui-components/controllers/tilesController.js"></script>
<script src="scripts/demos/modules/vectorMaps.js"></script>
<script src="scripts/demos/modules/dashboard.js"></script>
<script src="scripts/demos/demos.js"></script>
<script src="scripts/app.js"></script>
<!-- endbuild -->
</body>
</html>
