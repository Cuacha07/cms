<?php

namespace Nhitrort90\CMS\Providers;


use App\Http\Kernel;
use Nhitrort90\CMS\MediaManager;
use Nhitrort90\CMS\Modules\Articles\Article;
use Nhitrort90\CMS\Modules\Categories\Category;
use Nhitrort90\CMS\Modules\Users\User;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Nhitrort90\CMS\CMS;
use Blade;
use Nhitrort90\CMS\Alert;
use Nhitrort90\CMS\FieldBuilder;

class CMSServiceProvider extends ServiceProvider
{

    protected $file_config;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../helpers.php';

        $this->file_config = $file_config = __DIR__ . '/../config/cms.php';

        $this->mergeConfigFrom($this->file_config, 'cms');

        $this->publishFiles();

        // Translations
        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'CMS');

        // Load our views
        $this->loadViewsFrom(__DIR__ . '/../views', 'CMS');
        $router->middleware('CMSAuthenticate', 'Nhitrort90\CMS\Middleware\CMSAuthenticate');

        $this->app['config']->set('auth.model', 'Nhitrort90\CMS\Modules\Users\User');

        $this->extendBlade();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__ . '/../routes.php';

        $this->app['cms'] = $this->app->share(function($app){
           return new CMS();
        });


        $this->app['alert'] = $this->app->share(function($app)
        {
            $alertBuilder = new Alert($app['view'], $app['session.store']);
            return $alertBuilder;
        });


        $this->app['field'] = $this->app->share(function($app)
        {
            $fieldBuilder = new FieldBuilder($app['form'], $app['view'], $app['session.store']);
            return $fieldBuilder;
        });

        $this->app['media_manager'] = $this->app->share(function($app)
        {
            return new MediaManager();
        });


        $this->app->singleton('command.cms.start', function ($app) {
            return $app['Nhitrort90\CMS\Commands\StartCommand'];
        });
        $this->commands('command.cms.start');
        
        $this->app->singleton('command.cms.createmodule', function ($app) {
            return $app['Nhitrort90\CMS\Commands\CreateModule'];
        });
        
        $this->commands('command.cms.createmodule');

        $this->app->singleton('command.cms.deletemodule', function ($app) {
            return $app['Nhitrort90\CMS\Commands\DeleteModule'];
        });
        
        $this->commands('command.cms.deletemodule');

        $this->registerFakers();
    }

    protected function registerFakers()
    {

        // Users
        $factory = app('Illuminate\Database\Eloquent\Factory');
        $factory->defineAs(User::class, 'cms_site_demo', function ($faker) {
            return [
                'name' => $faker->name,
                'email' => $faker->email,
                'password' => str_random(10),
                'type' => $faker->randomElement(['admin', 'editor']),
                'remember_token' => str_random(10),
            ];
        });


        // Categories
        $factory->defineAs( Category::class, 'cms_site_demo', function ($faker) {
            $title = $faker->sentence;
            return [
                'title'         => $title,
            ];
        });

        // Articles
        $factory->defineAs( Article::class, 'cms_site_demo', function ($faker) {
            $sumary = $faker->paragraph;
            $title = $faker->sentence;
            return [
                'title'         => $title,
                'slug_url'      => str_slug($title),
                'primary_img'   => null,
                'sumary'        => $sumary,
                'body'          => $faker->text,
                'title_seo'     => $title,
                'meta_keywords'     => implode(', ', $faker->words(10)),
                'meta_description'  => $sumary,
                'category_id'       => $faker->numberBetween(1, 5),
                'created_by'        => $faker->numberBetween(1, 5),
                'views'             => $faker->numberBetween(0, 200),
                'published_at'      => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            ];
        });
    }

    protected function publishFiles()
    {
        // Config
        $this->publishes([
            $this->file_config => base_path('config/cms.php'),
        ]);

        // Assets
        $this->publishes([
            __DIR__.'/../assets' => public_path('vendor/nhitrort90/cms'),
        ], 'public');

        $this->shareGlobalVariables();
    }

    protected function shareGlobalVariables()
    {
        //view()->share('cms_current_user', \Auth::user());
    }

    protected function extendBlade()
    {
        /*Blade::directive('linkSidebarMenu', function($route, $text, $icon) {
            return "<?php echo ''; ?>";
        });*/
    }
}
