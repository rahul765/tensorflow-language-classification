<?php

namespace App\Modules;

use Slim\App;
use App\Models\Pages;
use App\Source\RouteSystem\PageResource;
use App\Source\RouteSystem\PageRouteCollection;


class PublicModule extends AModule
{
    const MODULE_NAME = 'public_cms';

    public function initialization(App $app)
    {
        parent::initialization($app);
    }

    public function registerRoute()
    {
        $pages = Pages::where('active', 1)->orderBy('id', 'asc')->get()->toArray();

        if( empty($pages) )
            return;
        $routers = $this->container->get('router');
        $home = $routers->getNamedRoute('home');
        $routers->removeRoute($home->getIdentifier());

        while ($page = array_shift($pages)) {
            $url = $page['url_prefix'].'/'.$page['code'];
            $controller = 'homeAction';
            if( $page['id']>1 )
                $controller = 'detailAction';

            PageRouteCollection::add(new PageResource($url, $controller, $page['id']));
        }

        PageRouteCollection::register($this->app);
    }
}
