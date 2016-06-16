<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header text-uppercase">@lang('CMS::core.primary_menu_title')</li>
            {!! CMS::makeLinkForSidebarMenu('CMS::admin.users.index', trans('CMS::users.users'), 'fa fa-users') !!}
            @include('CMS::partials.items_menu_lateral')
        </ul>
    </section>
</aside>