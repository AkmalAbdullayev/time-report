<?php $rName = Route::currentRouteName()?>
<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto">
                <a class="navbar-brand" href="{{ route("home") }}">
                    <div class="brand-logo">
                        <img class="logo" src="{{ asset('app-assets/images/logo/logo.png') }}"/>
                    </div>
                    <h2 class="brand-text mb-0">{{ config("app.name") }}</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse">
                    <i class="bx bx-x d-block d-xl-none font-medium-4 primary"></i>
                    <i class="toggle-icon bx bx-disc font-medium-4 d-none d-xl-block primary"
                       data-ticon="bx-disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation"
            data-icon-style="lines">

            <li class="nav-item {{$rName == 'home' ? 'active': ''}}">
                <a href="{{route('home')}}">
                    <i class="menu-livicon" data-icon="home"></i>
                    <span class="menu-title">Главная</span>
                </a>
            </li>

            <?php $menu = ['roles.', 'users.']?>
            <li class="nav-item {{ (in_array($rName, $menu)) ? 'open' : '' }}">
                <a href="#">
                    <i class="menu-livicon" data-icon="users"></i>
                    <span class="menu-title" data-i18n="Invoice">Пользователи</span>
                </a>
                <ul class="menu-content">
                    <li class="{{(str_contains($rName, 'permission.')) ? 'active' : ''}}">
                        <a href="{{route("permission.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Permissions</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'roles.')) ? 'active' : ''}}">
                        <a href="{{route("roles.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Роли</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'users.')) ? 'active' : ''}}">
                        <a href="{{route("users.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Пользователи</span>
                        </a>
                    </li>
                </ul>
            </li>

            <?php $menu = ['devices.', 'doors.']?>
            <li class="nav-item {{ (in_array($rName, $menu)) ? 'open' : '' }}">
                <a href="#">
                    <i class="menu-livicon" data-icon="morph-orientation-tablet"></i>
                    <span class="menu-title" data-i18n="Invoice">Устройства</span>
                </a>
                <ul class="menu-content">
                    <li class="{{(str_contains($rName, 'door.crud')) ? 'active' : ''}}">
                        <a href="{{route("door.crud")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Двери</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'devices.')) ? 'active' : ''}}">
                        <a href="{{route("devices.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Устройство</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'doors.')) ? 'active' : ''}}">
                        <a href="{{route("doors.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Шаблоны доступа</span>
                        </a>
                    </li>
                </ul>
            </li>

            <?php $menu = ['company.', 'branches.', 'department.', 'position.', 'schedule', 'employees.']?>
            <li class="nav-item {{ (in_array($rName, $menu)) ? 'open' : '' }}">
                <a href="#">
                    <i class="menu-livicon" data-icon="users"></i>
                    <span class="menu-title" data-i18n="Invoice">Сотрудники</span>
                </a>
                <ul class="menu-content">
                    <li class="{{(str_contains($rName, 'company.')) ? 'active' : ''}}">
                        <a href="{{route("company.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Организация</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'branches.')) ? 'active' : ''}}">
                        <a href="{{route("branches.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Здание</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'department.')) ? 'active' : ''}}">
                        <a href="{{route("department.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Подразделения</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'position.')) ? 'active' : ''}}">
                        <a href="{{route("position.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Должность</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'schedule')) ? 'active' : ''}}">
                        <a href="{{route("schedule.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">График работы</span>
                        </a>
                    </li>
                    <li class="{{(str_contains($rName, 'employees.')) ? 'active' : ''}}">
                        <a href="{{route("employees.index")}}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Сотрудник</span>
                        </a>
                    </li>
                </ul>
            </li>

            <?php $menu = ['personal-report', 'full-report', 'manual-report', 'temporary-report']?>
            <li class="nav-item {{ (in_array($rName, $menu)) ? 'open' : '' }}">
                <a href="#">
                    <i class="menu-livicon" data-icon="notebook"></i>
                    <span class="menu-title" data-i18n="Invoice">Отчеты</span>
                </a>
                <ul class="menu-content">
                    <li class="{{(str_contains($rName, 'personal-report')) ? 'active' : ''}}">
                        <a href="{{ route("personal-report") }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Опоздания и ранние уходы</span>
                        </a>
                    </li>
                    {{--                    <li class="{{(str_contains($rName, 'full-report')) ? 'active' : ''}}">--}}
                    {{--                        <a href="{{route("full-report")}}">--}}
                    {{--                            <i class="bx bx-right-arrow-alt"></i>--}}
                    {{--                            <span class="menu-item" data-i18n="Invoice List">Общий Отчет</span>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}
                    {{--                    <li class="{{(str_contains($rName, 'manual-report')) ? 'active' : ''}}">--}}
                    {{--                        <a href="{{route("manual-report")}}">--}}
                    {{--                            <i class="bx bx-right-arrow-alt"></i>--}}
                    {{--                            <span class="menu-item" data-i18n="Invoice List">Ручной Отчет</span>--}}
                    {{--                        </a>--}}
                    {{--                    </li>--}}

                    <li class="{{ (str_contains($rName, 'temporary-report')) ? 'active' : '' }}">
                        <a href="{{ route("temporary-report") }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice">Посещения сотрудников</span>
                        </a>
                    </li>

                    <li class="{{ (str_contains($rName, 'presences')) ? 'active' : '' }}">
                        <a href="{{ route("presences") }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Присутствующие на данный момент</span>
                        </a>
                    </li>
                    <li class="{{ (str_contains($rName, 'come.out.report')) ? 'active' : '' }}">
                        <a href="{{ route("come.out.report") }}">
                            <i class="bx bx-right-arrow-alt"></i>
                            <span class="menu-item" data-i18n="Invoice List">Отчеть</span>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route("client.index") }}">
                    <i class="menu-livicon" data-icon="users"></i>
                    <span class="menu-title" data-i18n="Invoice List">Клиенты</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route("weekend") }}">
                    <i class="menu-livicon" data-icon="users"></i>
                    <span class="menu-title" data-i18n="Invoice List">Выходные</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route("come-out") }}">
                    <i class="bx bx-disc" data-icon="notebook"></i>
                    <span class="menu-title" data-i18n="Invoice">LIVE</span>
                </a>
            </li>
        </ul>
    </div>
</div>
