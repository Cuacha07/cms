<?php

namespace Nhitrort90\CMS;


/**
 * Class CMS
 * @package Nhitrort90\CMS
 */
class CMS
{

    protected $current_route_name;

    public function __construct()
    {
        $this->current_route_name = \Route::current()->getName();
    }


    /**
     * Generate the path to CMS assets
     * @param string $file_name
     * @return string
     */
    public function assetPath($file_name = '')
    {
        return asset('vendor/nhitrort90/cms/' . $file_name);
    }


    /**
     * Make a link to the Sidebar Menu
     * @param $route_name
     * @param $text
     * @param $icon
     * @return \Illuminate\View\View
     */
    public function makeLinkForSidebarMenu($route_name, $text, $icon)
    {
        $class  = '';
        $prefix = explode('.', $this->current_route_name);
        array_pop($prefix);
        $prefix = implode('.', $prefix);

        if($route_name == $this->current_route_name or $prefix . '.index' == $route_name)
        {
            $class = 'active';
        }

        return view('CMS::partials._link_sidebar_menu', compact ('route_name', 'text', 'icon', 'class'));
    }

}