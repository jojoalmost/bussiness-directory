<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
HTML::macro('clever_link', function($route, $text,$iconClass) {

    if(Request::is($route) || Request::is($route."/*")) { //
        $active = "class = 'active'";
    }
    else {
        $active = '';
    }
    /*<li class=""><a href=""><i class="fa fa-envelope-o"></i> <span>Email</span></a></li>*/
    return '<li ' . $active . '><a href="' . URL::route($route).'"><i class="'.$iconClass.'"></i><span>' . $text
        . '</span></a></li>';
});

Route::group(['domain' => '{projectSlug}.{tld}'], function()
{
    // Routes within each website

    Route::get('/', function($projectSlug,$tld) {
        $app = app();

        if(gettype($projectSlug)=='object'){
            $controller = $app->make('WebsiteController');
            return $controller->callAction('website', $parameters = array('id'=>$projectSlug->id));
        }else{
            $controller = $app->make('HomeController');
            return $controller->callAction('index', $parameters = array());
        }
    });



    Route::get('/page/{slug}', function($projectSlug,$tld,$slug) {
        $app = app();
        if(gettype($projectSlug)=='object'){

            $controller = $app->make('WebsiteController');
            return $controller->callAction('websitePage', $parameters = array('id'=>$projectSlug->id,'slug'=>$slug));
        }else{
            $controller = $app->make('HomeController');
            return $controller->callAction('index', $parameters = array('id'=>$projectSlug->id,'slug'=>$slug));
        }
    });
});
Route::group(array('namespace'=>'Admin', 'prefix'=>'admin'),function(){
    Route::get('/login',array('as'=>'admin/login','uses'=>'SiteController@login'));
    Route::get('/logout',array('as'=>'admin/logout','uses'=>'SiteController@logout'));
    Route::post('/login','SiteController@login');
    Route::group(array('before'=>'admin_auth'),function(){
        Route::get('/',function(){
            return Redirect::to('admin/dashboard');
        });
        Route::get('dashboard',array('as'=>'admin/dashboard','uses'=>'SiteController@index'));
        Route::resource('companies','CompaniesController',array('names' => array('index'=>'admin/companies')));
        Route::resource('categories','CategoriesController',array('names' => array('index'=>'admin/categories')));

        Route::resource('custom-website','CustomWebsitesController',array('names' => array('index'=>'admin/custom-website')));
        Route::get('custom-website/{id}/choose-templates',array('as'=>'custom-website.chooseTemplate','uses'=>'CustomWebsitesController@chooseTemplates'));
        Route::get('custom-website/{id}/builder/{templateId}',array('as'=>'custom-website.builder','uses'=>'CustomWebsitesController@builder'));
        Route::get('custom-website/{id}/builder-editor/{templateId}',array('as'=>'custom-website.builderEditor','uses'=>'CustomWebsitesController@builderEditor'));
        Route::post('custom-website/{id}/builder-save',array('as'=>'custom-website.builderSave','uses'=>'CustomWebsitesController@builderSave'));
        Route::get('custom-website/{id}/pages',array('as'=>'custom-website.pages','uses'=>'CustomWebsitesController@pages'));
        Route::get('custom-website/{id}/pages-delete/{pageId}',array('as'=>'custom-website.pages.delete','uses'=>'CustomWebsitesController@pagesDelete'));
        Route::resource('custom-template','CustomTemplatesController',array('names' => array('index'=>'admin/custom-template')));

        Route::group(array('prefix'=>'dt'),function(){
            Route::get('company',array('as'=>'dt.company','uses'=>'CompaniesController@getDatatableAll'));
            Route::get('category',array('as'=>'dt.category','uses'=>'CategoriesController@getDatatableAll'));
            Route::get('custom-website',array('as'=>'dt.custom-website','uses'=>'CustomWebsitesController@getDatatableAll'));
            Route::get('custom-website-pages',array('as'=>'dt.custom-website-pages','uses'=>'CustomWebsitesController@getWebsitePages'));
        });


    });

});


//Route::get('/website/{id}','WebsiteController@website');

//Route::get('/','HomeController@index');
Route::get('/contact-us','HomeController@contact');
Route::get('/about-us','HomeController@about');
Route::get('/price-listing','HomeController@pricelisting');
Route::group(array('prefix'=>'companies'),function(){
    //Route::get('/','CategoriesController@index');
    Route::get('/','CategoriesController@index');
    Route::get('{categorySlug}','CategoriesController@slug');
    Route::get('detail/{id}','CompaniesController@index');
});


