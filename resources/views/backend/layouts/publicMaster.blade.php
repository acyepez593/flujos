<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title', 'Laravel Role Admin')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('backend.layouts.partials.styles')
    @yield('styles')
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    
    <!-- page container area start -->
    <div class="public-page-container offset-md-3 col-md-6 col-sm-12">

        <!--<div class="sidebar-menu">
            <div class="sidebar-header">
                
            </div>
            <div class="main-menu">
                <div class="menu-inner">
                </div>
            </div>
        </div>-->

        <!-- main content area start -->
        <div class="main-content">
            
            @yield('admin-content')
        </div>
        <!-- main content area end -->
        @include('backend.layouts.partials.footer')
    </div>
    <!-- page container area end -->

    @include('backend.layouts.partials.scripts')
    @yield('scripts')
</body>

</html>
