<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    @include('layouts.head')

</head>

<body class="layout-default">

    <div class="preloader"></div>

    <!-- Header Layout -->
    <div class="mdk-header-layout js-mdk-header-layout">

        <!-- Header -->

        @include('layouts.header')

        <!-- // END Header -->

        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content">

            @yield('content')

        </div>
        <!-- // END header-layout__content -->

    </div>
    <!-- // END header-layout -->

    <!-- App Settings FAB -->
    <div id="app-settings">
        <app-settings layout-active="default"
            :layout-location="{
                'default': 'index.html',
                'fixed': 'fixed-dashboard.html',
                'fluid': 'fluid-dashboard.html',
                'mini': 'mini-dashboard.html'
            }"></app-settings>
    </div>

    @include('layouts.script')
</body>

</html>
