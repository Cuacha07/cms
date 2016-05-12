# Komvac cms

This is a PHP CMS powered by Laravel 5.1, it's based on Reactor CMS by gmlo89 (link: https://github.com/gmlo89/reactor-cms)

Modules included:

Users (CRUD, Auth)
Categories (CRUD)
Articles (CRUD)


Differences between Komvac-cms and Reactor-CMS:

- added new commands: php artisan cms:createmodule and cms:deletemodule are included
    - cms:createmodule: create a new module and migration file for the cms.
    - cms:deletemodule: delete a selected module (and migration file).
- new module templates to be added soon.


Installation

First, pull in the package through Composer.

“require”: {
    ...
    "nhitrort90/cms": "dev-master”
}
And run composer:

$ composer update
And then, include the service provider within config/app.php.

'providers' => [
    ...
    // own
    Nhitrort90\CMS\Providers\CMSServiceProvider::class,
    // Required
    Collective\Html\HtmlServiceProvider::class,
],

....

'aliases' => [
    ...
    // Custom
    'CMS'    => Nhitrort90\CMS\Facades\CMS::class,
    'Field'  => Nhitrort90\CMS\Facades\FieldBuilder::class,
    'Alert'  => Nhitrort90\CMS\Facades\Alert::class,
    'MediaManager' => Nhitrort90\CMS\Facades\MediaManager::class,
    // Required
    'Form' => Collective\Html\FormFacade::class,
    'Html' => Collective\Html\HtmlFacade::class,
],
Configure your preference database.

Configure the CMS

$ php artisan cms:start
Run this command and type the required data.

Make sure update the auth.php config file with the User Model of the CMS.

    'model' => \Nhitrort90\CMS\Modules\Users\User::class,
Also you can set more configurations on config/cms.php.

Enjoy it!

Go to the web browser and put your-domain/admin.

Credits

This package uses a number of open source projects to work properly:

Laravel 5.1 - The PHP Framework For Web Artisans
AdminLTE - Dashboard & Control Panel Template
VueJS - Intuitive, Fast and Composable MVVM for building interactive interfaces.
TinyMCE - HTML WYSIWYG editor
gmlo89's Reactor CMS
And others...
