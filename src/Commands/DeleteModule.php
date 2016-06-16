<?php

namespace Nhitrort90\CMS\Commands;

use Nhitrort90\CMS\Modules\Articles\Article;
use Nhitrort90\CMS\Modules\Categories\Category;
use Nhitrort90\CMS\Modules\Users\User;
use Nhitrort90\CMS\Providers\CMSServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use File;
use Schema;

class DeleteModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:deletemodule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Module of CMS.';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Eliminación de modulo de CMS');

        //Artisan::call('vendor:publish');
        //$this->info(__DIR__.'/..');
        $this->asistenteeliminarmodulo();

        $this->info('Finalizado, adios!!!');
    }

    protected function asistenteeliminarmodulo()
    {
        $data = [];
        $data['nombremodulo'] = $this->ask('Cual es el nombre del modulo? (Escribelo en singular)');
        if(!File::exists(__DIR__.'/../views/'.$data['nombremodulo']))
        {
            $this->info('El nombre de modulo no existe');
        }
        else
        {
            $data['nombremodulo']=strtolower($data['nombremodulo']);
            $data['nombremodulomayus']=ucfirst($data['nombremodulo']);
            $data['pnombremodulo']=$this->aplural(strtolower($data['nombremodulo']));
            $data['pnombremodulomayus']=$this->aplural(ucfirst($data['nombremodulo']));
            //if (Schema::hasTable($data['nombremodulo'])===false)
            if($this->eliminarmodulo($data)==true)
            {
                $this->info('Modulo eliminado correctamente');
            }
            else
            {
                $this->info('Ocurrio un error al eliminar el modulo');
            }
        }
        
    }  

    protected function eliminarmodulo($datos)
    {
        $_directorio_principal=true;
        $_requests=true;
        $_modules=true;
        $_eliminar_migrations=true;
        $_eliminar_language=true;
        $_eliminar_controllers=true;
        $_eliminar_routes=true;
        $_eliminar_enlace_menu=true;
        $_directorio_principal=$this->eliminardirectorio_principal($datos);
        //$_requests=$this->eliminar_requests($datos);
        //$_modules=$this->eliminar_modules($datos);
        //$_eliminar_migrations=$this->eliminar_migrations($datos);
        //$_eliminar_language=$this->eliminar_language($datos);
        //$_eliminar_controllers=$this->eliminar_controllers($datos);
        //$_eliminar_routes=$this->eliminar_routes($datos);
        //$_eliminar_enlace_menu=$this->eliminar_enlace_menu($datos);
        if($_directorio_principal==true&&$_requests==true&&$_modules==true&&$_eliminar_migrations==true&&
            $_eliminar_language==true&&$_eliminar_controllers==true&&$_eliminar_routes==true&&$_eliminar_enlace_menu==true)
            {
                return true;
            }    
        return false;
    }

    protected function eliminar_enlace_menu($datos)
    {
        if(File::append(__DIR__.'/../views/partials/items_menu_lateral.blade.php',
                "{!! CMS::makeLinkForSidebarMenu('CMS::admin.".$datos['pnombremodulo'].".index', trans('CMS::".$datos['pnombremodulo'].".".$datos['pnombremodulo']."'), 'fa fa-file') !!}")!==false)
        {
            return true;
        }
        return false;
    }

    protected function eliminar_routes($datos)
    {
        if(File::put(__DIR__.'/../routes/'.$datos['nombremodulomayus'].'Routes.php',
            "<?php
            Route::resource('".$datos['pnombremodulo']."', '".$datos['pnombremodulomayus']."Controller');")==true)
        {
            return true;
        }
        return false;
    }

    protected function eliminar_controllers($datos)
    {
        if(File::delete(__DIR__.'/../Controllers/'.$datos['pnombremodulomayus'].'Controller.php')==true)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    protected function eliminar_language($datos)
    {
        if(File::delete(__DIR__.'/../lang/en/'.$datos['pnombremodulo'].'.php')==true)
        {
            return true;
        }
        return false;
    }

    protected function eliminar_migrations($datos)
    {
        if(File::delete(__DIR__.'/../stubs/'.$datos['pnombremodulo'].'.stub')==true)
        {
            return true;
        }
        return false;
    }

    protected function eliminar_modules($datos)
    {
        if(File::deleteDirectory(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'])==true)
        {
            return true;
        }
        return false;
    }

    protected function eliminar_requests($datos)
    {
        if(File::delete(__DIR__.'/../Requests/Create'.$datos['nombremodulomayus'].'.php')==true)
        {
            if(File::delete(__DIR__.'/../Requests/Update'.$datos['nombremodulomayus'].'.php')==true)
            {
                return true;
            }
        }
        return false;
    }

    protected function eliminardirectorio_principal($datos)
    {
        if(File::deleteDirectory(__DIR__.'/../views/'.$datos['pnombremodulo'])==true)
        {
            return true;
        } 
        return false;
    }

    public function aplural($cadena)
    {
        $muestra=substr($cadena, -1);
        if($muestra=="a"||$muestra=="e"||$muestra=="i"||$muestra=="o"||$muestra=="u")
        {
            return $cadena."s";
        }
        else
        {
            return $cadena."es";
        }
    }
}
