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
    <link href="src/assets/less/styles.less" rel="stylesheet/less" media="all">
    <!-- /prochtml -->

    <link href='//fonts.googleapis.com/css?family=RobotoDraft:300,400,400italic,500,700' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700' rel='stylesheet' type='text/css'>

    <!-- The following CSS are included as plugins and can be removed if unused-->

    <!-- build:css assets/css/vendor.css -->
    <!-- bower:css -->
    <link rel="stylesheet" href="src/bower_components/chartist/dist/chartist.min.css" />
    <link rel="stylesheet" href="src/bower_components/angular-meditor/dist/meditor.min.css" />
    <link rel="stylesheet" href="src/bower_components/nanoscroller/bin/css/nanoscroller.css" />
    <link rel="stylesheet" href="src/bower_components/angular-ui-grid/ui-grid.css" />
    <link rel="stylesheet" href="src/bower_components/angular-ui-select/dist/select.css" />
    <link rel="stylesheet" href="src/bower_components/angular-ui-tree/dist/angular-ui-tree.min.css" />
    <link rel="stylesheet" href="src/bower_components/angular-xeditable/dist/css/xeditable.css" />
    <link rel="stylesheet" href="src/bower_components/nvd3/src/nv.d3.css" />
    <link rel="stylesheet" href="src/bower_components/animate.css/animate.css" />
    <link rel="stylesheet" href="src/bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" href="src/bower_components/bower-jvectormap/jquery-jvectormap-1.2.2.css" />
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="src/bower_components/fullcalendar/dist/fullcalendar.css" />
    <link rel="stylesheet" href="src/bower_components/ng-grid/ng-grid.css" />
    <link rel="stylesheet" href="src/bower_components/switchery/dist/switchery.css" />
    <link rel="stylesheet" href="src/bower_components/pnotify/pnotify.core.css" />
    <link rel="stylesheet" href="src/bower_components/pnotify/pnotify.buttons.css" />
    <link rel="stylesheet" href="src/bower_components/pnotify/pnotify.history.css" />
    <link rel="stylesheet" href="src/bower_components/seiyria-bootstrap-slider/dist/css/bootstrap-slider.min.css" />
    <link rel="stylesheet" href="src/bower_components/skylo/vendor/styles/skylo.css" />
    <link rel="stylesheet" href="src/bower_components/textAngular/src/textAngular.css" />
    <link rel="stylesheet" href="src/bower_components/themify-icons/themify-icons.css" />
    <link rel="stylesheet" href="src/bower_components/weather-icons/css/weather-icons.min.css" />
    <!-- endbower -->
    <link rel='stylesheet' type="text/css" href="src/assets/plugins/form-nestable/jquery.nestable.css" />
    <link rel='stylesheet' type='text/css' href='src/assets/plugins/form-fseditor/fseditor.css' />
    <link rel='stylesheet' type='text/css' href='src/assets/plugins/jcrop/css/jquery.Jcrop.min.css' />
    <link rel="stylesheet" type='text/css' href="src/assets/plugins/iCheck/skins/all.css" />
    <link rel="stylesheet" type='text/css' href="src/assets/plugins/google-code-prettify/prettify.css" />
    <!-- endbuild -->

    <!-- build:css({.tmp,app}) assets/css/main.css -->
      <link rel="stylesheet" href="src/assets/css/styles.css">
    <!-- endbuild -->

    <!-- prochtml:remove:dist -->
    <script type="text/javascript">less = { env: 'development'};</script>
    <script type="text/javascript" src="src/assets/plugins/misc/less.js"></script>
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

    <div ng-include="'admin-app/views/index.html'"></div>

    <!--[if lt IE 9]>
    <script src="src/bower_components/es5-shim/es5-shim.js"></script>
    <script src="src/bower_components/json3/lib/json3.min.js"></script>
    <![endif]-->

    <script type='text/javascript' src='//maps.google.com/maps/api/js?sensor=true'></script>

    <!-- build:js scripts/vendor.js -->
    <!-- bower:js -->
    <script src="src/bower_components/modernizr/modernizr.js"></script>
    <script src="src/bower_components/jquery/dist/jquery.js"></script>
    <script src="src/bower_components/angular/angular.js"></script>
    <script src="src/bower_components/angular-animate/angular-animate.js"></script>
    <script src="src/bower_components/angular-bootstrap/ui-bootstrap-tpls.js"></script>
    <script src="src/bower_components/chartist/dist/chartist.min.js"></script>
    <script src="src/bower_components/angular-chartist.js/dist/angular-chartist.js"></script>
    <script src="src/bower_components/angular-cookies/angular-cookies.js"></script>
    <script src="src/bower_components/angular-meditor/dist/meditor.min.js"></script>
    <script src="src/bower_components/nanoscroller/bin/javascripts/jquery.nanoscroller.js"></script>
    <script src="src/bower_components/angular-nanoscroller/scrollable.js"></script>
    <script src="src/bower_components/angular-resource/angular-resource.js"></script>
    <script src="src/bower_components/angular-route/angular-route.js"></script>
    <script src="src/bower_components/angular-sanitize/angular-sanitize.js"></script>
    <script src="src/bower_components/skycons/skycons.js"></script>
    <script src="src/bower_components/angular-skycons/angular-skycons.js"></script>
    <script src="src/bower_components/angular-ui-grid/ui-grid.js"></script>
    <script src="src/bower_components/angular-ui-select/dist/select.js"></script>
    <script src="src/bower_components/angular-ui-tree/dist/angular-ui-tree.js"></script>
    <script src="src/bower_components/angular-xeditable/dist/js/xeditable.js"></script>
    <script src="src/bower_components/d3/d3.js"></script>
    <script src="src/bower_components/nvd3/nv.d3.js"></script>
    <script src="src/bower_components/angularjs-nvd3-directives/dist/angularjs-nvd3-directives.js"></script>
    <script src="src/bower_components/bootstrap/dist/js/bootstrap.js"></script>
    <script src="src/bower_components/bootbox.js/bootbox.js"></script>
    <script src="src/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="src/bower_components/moment/moment.js"></script>
    <script src="src/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="src/bower_components/bower-jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="src/bower_components/bower-jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="src/bower_components/card/lib/js/jquery.card.js"></script>
    <script src="src/bower_components/enquire/dist/enquire.js"></script>
    <script src="src/bower_components/flot/jquery.flot.js"></script>
    <script src="src/bower_components/flot/jquery.flot.stack.js"></script>
    <script src="src/bower_components/flot/jquery.flot.pie.js"></script>
    <script src="src/bower_components/flot/jquery.flot.resize.js"></script>
    <script src="src/bower_components/flot.tooltip/js/jquery.flot.tooltip.js"></script>
    <script src="src/bower_components/gmaps/gmaps.js"></script>
    <script src="src/bower_components/google-code-prettify/src/prettify.js"></script>
    <script src="src/bower_components/iCheck/icheck.min.js"></script>
    <script src="src/bower_components/jquery-autosize/jquery.autosize.js"></script>
    <script src="src/bower_components/jquery.easing/js/jquery.easing.js"></script>
    <script src="src/bower_components/jquery.easy-pie-chart/dist/angular.easypiechart.js"></script>
    <script src="src/bower_components/jquery.knob/js/jquery.knob.js"></script>
    <script src="src/bower_components/jquery.pulsate/jquery.pulsate.js"></script>
    <script src="src/bower_components/jquery.sparkline/index.js"></script>
    <script src="src/bower_components/jquery.ui/ui/jquery.ui.core.js"></script>
    <script src="src/bower_components/jquery.ui/ui/jquery.ui.widget.js"></script>
    <script src="src/bower_components/jquery.ui/ui/jquery.ui.mouse.js"></script>
    <script src="src/bower_components/jquery.ui/ui/jquery.ui.draggable.js"></script>
    <script src="src/bower_components/jquery.ui/ui/jquery.ui.sortable.js"></script>
    <script src="src/bower_components/jquery.ui/ui/jquery.ui.resizable.js"></script>
    <script src="src/bower_components/flow.js/dist/flow.js"></script>
    <script src="src/bower_components/ng-flow/dist/ng-flow.js"></script>
    <script src="src/bower_components/ng-grid/build/ng-grid.js"></script>
    <script src="src/bower_components/transitionize/dist/transitionize.js"></script>
    <script src="src/bower_components/fastclick/lib/fastclick.js"></script>
    <script src="src/bower_components/switchery/dist/switchery.js"></script>
    <script src="src/bower_components/ng-switchery/src/ng-switchery.js"></script>
    <script src="src/bower_components/oclazyload/dist/ocLazyLoad.min.js"></script>
    <script src="src/bower_components/pnotify/pnotify.core.js"></script>
    <script src="src/bower_components/pnotify/pnotify.buttons.js"></script>
    <script src="src/bower_components/pnotify/pnotify.callbacks.js"></script>
    <script src="src/bower_components/pnotify/pnotify.confirm.js"></script>
    <script src="src/bower_components/pnotify/pnotify.desktop.js"></script>
    <script src="src/bower_components/pnotify/pnotify.history.js"></script>
    <script src="src/bower_components/pnotify/pnotify.nonblock.js"></script>
    <script src="src/bower_components/seiyria-bootstrap-slider/js/bootstrap-slider.js"></script>
    <script src="src/bower_components/shufflejs/dist/jquery.shuffle.js"></script>
    <script src="src/bower_components/skylo/vendor/scripts/skylo.js"></script>
    <script src="src/bower_components/rangy/rangy-core.js"></script>
    <script src="src/bower_components/rangy/rangy-classapplier.js"></script>
    <script src="src/bower_components/rangy/rangy-highlighter.js"></script>
    <script src="src/bower_components/rangy/rangy-selectionsaverestore.js"></script>
    <script src="src/bower_components/rangy/rangy-serializer.js"></script>
    <script src="src/bower_components/rangy/rangy-textrange.js"></script>
    <script src="src/bower_components/textAngular/src/textAngular.js"></script>
    <script src="src/bower_components/textAngular/src/textAngular-sanitize.js"></script>
    <script src="src/bower_components/textAngular/src/textAngularSetup.js"></script>
    <script src="src/bower_components/rangy/rangy-selectionsaverestore.js"></script>
    <script src="src/bower_components/underscore/underscore.js"></script>
    <script src="src/bower_components/velocity/velocity.js"></script>
    <script src="src/bower_components/velocity/velocity.ui.js"></script>
    <!-- endbower -->

    <script type="text/javascript" src="src/assets/plugins/form-nestable/jquery.nestable.min.js"></script>
    <script type='text/javascript' src='src/assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js'></script>
    <script type='text/javascript' src='src/assets/plugins/form-fseditor/jquery.fseditor-min.js'></script>
    <script type='text/javascript' src='src/assets/plugins/form-jasnyupload/fileinput.min.js'></script>
    <script type='text/javascript' src='src/assets/plugins/flot/jquery.flot.spline.js'></script>
    <script type='text/javascript' src='src/assets/plugins/flot/jquery.flot.orderBars.js'></script>
    <script type='text/javascript' src='src/assets/plugins/wijets/wijets.js'></script>
    <script type='text/javascript' src="src/assets/plugins/bootstrap-tabdrop/js/bootstrap-tabdrop.js"></script>
    <script type='text/javascript' src="src/assets/plugins/chartist-plugin-tooltip/chartist-plugin-tooltip.min.js"></script>
    
    <!-- endbuild -->

      <!-- build:js({.tmp,app}) scripts/scripts.js -->
    <script src="src/scripts/core/controllers/mainController.js"></script>
    <script src="src/scripts/core/controllers/navigationController.js"></script>
    <script src="src/scripts/core/controllers/notificationsController.js"></script>
    <script src="src/scripts/core/controllers/messagesController.js"></script>
    <script src="src/scripts/core/directives/directives.js"></script>
    <script src="src/scripts/core/directives/form.js"></script>
    <script src="src/scripts/core/directives/ui.js"></script>
    <script src="src/scripts/core/modules/templateOverrides.js"></script>
    <script src="src/scripts/core/modules/templates.js"></script>
    <script src="src/scripts/core/services/services.js"></script>
    <script src="src/scripts/core/services/theme.js"></script>
    <script src="src/scripts/core/theme.js"></script>
    <script src="src/scripts/calendar/calendar.js"></script>
    <script src="src/scripts/chart/canvas.js"></script>
    <script src="src/scripts/chart/flot.js"></script>
    <script src="src/scripts/chart/morris.js"></script>
    <script src="src/scripts/chart/sparklines.js"></script>
    <script src="src/scripts/gallery/gallery.js"></script>
    <script src="src/scripts/map/googleMaps.js"></script>
    <script src="src/scripts/map/vectorMaps.js"></script>
    <script src="src/scripts/demos/modules/basicTables.js"></script>
    <script src="src/scripts/demos/modules/boxedLayout.js"></script>
    <script src="src/scripts/demos/modules/calendar.js"></script>
    <script src="src/scripts/demos/modules/canvasCharts.js"></script>
    <script src="src/scripts/demos/modules/chartistCharts.js"></script>
    <script src="src/scripts/demos/modules/nvd3Charts.js"></script>
    <script src="src/scripts/demos/modules/chatBox.js"></script>
    <script src="src/scripts/demos/modules/editableTable.js"></script>
    <script src="src/scripts/demos/modules/flotCharts.js"></script>
    <script src="src/scripts/demos/modules/form/form.js"></script>
    <script src="src/scripts/demos/modules/form/controllers/angularFormValidationController.js"></script>
    <script src="src/scripts/demos/modules/form/controllers/datepickerDemoController.js"></script>
    <script src="src/scripts/demos/modules/form/controllers/dateRangePickerDemoController.js"></script>
    <script src="src/scripts/demos/modules/form/controllers/formComponentsController.js"></script>
    <script src="src/scripts/demos/modules/form/controllers/imageCropController.js"></script>
    <script src="src/scripts/demos/modules/form/controllers/inlineEditableController.js"></script>
    <script src="src/scripts/demos/modules/form/controllers/timepickerDemoController.js"></script>
    <script src="src/scripts/demos/modules/gallery.js"></script>
    <script src="src/scripts/demos/modules/googleMaps.js"></script>
    <script src="src/scripts/demos/modules/horizontalLayout.js"></script>
    <script src="src/scripts/demos/modules/mail/controllers/composeController.js"></script>
    <script src="src/scripts/demos/modules/mail/controllers/inboxController.js"></script>
    <script src="src/scripts/demos/modules/mail/mail.js"></script>
    <script src="src/scripts/demos/modules/morrisCharts.js"></script>
    <script src="src/scripts/demos/modules/sparklineCharts.js"></script>
    <script src="src/scripts/demos/modules/ngGrid.js"></script>
    <script src="src/scripts/demos/modules/signupPage.js"></script>
    <script src="src/scripts/demos/modules/notFoundController.js"></script>
    <script src="src/scripts/demos/modules/errorPageController.js"></script>
    <script src="src/scripts/demos/modules/apps.js"></script>
    <script src="src/scripts/demos/modules/ui-components/uiComponents.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/alertsController.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/carouselController.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/modalsController.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/paginationsController.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/progressbarsController.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/ratingsController.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/slidersController.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/tabsController.js"></script>
    <script src="src/scripts/demos/modules/ui-components/controllers/tilesController.js"></script>
    <script src="src/scripts/demos/modules/vectorMaps.js"></script>
    <script src="src/scripts/demos/modules/dashboard.js"></script>
    <script src="src/scripts/demos/demos.js"></script>
    <script src="src/scripts/app.js"></script>
      <!-- endbuild -->
</body>
</html>