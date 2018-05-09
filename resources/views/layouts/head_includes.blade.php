<meta charset="UTF-8" />
<title>@yield('title') - {{ Config::get('client.web_title') }}</title>
@if (App::environment('local','staging'))
	<meta name="robots" content="noindex" />
@endif
<meta name="description" content="{{ Config::get('client.web_description') }}" />
<meta name="keywords" content="{{ Config::get('client.web_keywords') }}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="icon" type="image/png" href="{{ Config::get('client.logo') }}" />
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,700italic,800" type="text/css"/>
<link href="//fonts.googleapis.com/css?family=RobotoDraft:300,400,400italic,500,700" rel='stylesheet' type='text/css'>
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,400italic,600,700" rel='stylesheet' type='text/css'>

<link media="all" rel="stylesheet/less" href="{!! url('src/assets/less/base/styles.less') !!}" />
<link rel="stylesheet" href="{!! url('src/bower_components/font-awesome/css/font-awesome.css') !!}" />
<link rel="stylesheet" href="{!! url('admin/assets/css/styles.css')!!}" />