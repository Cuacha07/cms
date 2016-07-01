<?php

namespace Nhitrort90\CMS\Commands;

use Nhitrort90\CMS\Modules\Users\User;
use Nhitrort90\CMS\Providers\CMSServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use File;
use Schema;

class CreateModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:createmodule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Module of CMS.';

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
        $this->info('Creación de modulo para CMS');

        //Artisan::call('vendor:publish');
        //$this->info(__DIR__.'/..');
        //$this->asistentecrearmodulo();
        $this->asistentemodulos();
    }

    protected function asistentemodulos()
    {
        $data=[];
        $this->info('-------------Asistente para templates------------');
        $this->info('Los templates se muestran a continuacion');
        $this->info('');
        $this->info('1. Crear modulo vacio');
        $this->info('2. Crear publicacion de articulos (modulos articulos y categorias)');
        $this->info('3. Crear modulo calendario');
        $this->info('');
        $data['respuesta']=$this->ask('elige una de las opciones:');
        if($data['respuesta']==1)
        {
            $this->asistentecrearmodulo_vacio();
        }   
        else if($data['respuesta']==2)
        {
            $this->asistentecrearmodulo_ejemplo();
        }
        else if($data['respuesta']==3)
        {
            $this->asistentecrearmodulo_calendario();
        }
        else
        {
            $this->info("opcion no disponible");
        }
    }

    protected function asistentecrearmodulo_ejemplo()
    {
        $data = [];
        $data['nombremodulo'] = $this->ask('Cual es el nombre del modulo articles? (Escribelo en singular)');
        $data['nombremodulo2']=$this->ask('Cual es el nombre del modulo categories? (Escribelo en singular)');
        if(File::exists(__DIR__.'/../views/'.$data['nombremodulo']) && File::exists(_DIR_.'/../views/'.$data['nombremodulo2']))
        {
            $this->info('El nombre de alguno de los modulos ya existe. Intente de nuevo con otros nombres');
        }
        else
        {
            $data['nombremodulo']=strtolower($data['nombremodulo']);
            $data['nombremodulomayus']=ucfirst($data['nombremodulo']);
            $data['pnombremodulo']=$this->aplural(strtolower($data['nombremodulo']));
            $data['pnombremodulomayus']=$this->aplural(ucfirst($data['nombremodulo']));

            $data['nombremodulo2']=strtolower($data['nombremodulo2']);
            $data['nombremodulomayus2']=ucfirst($data['nombremodulo2']);
            $data['pnombremodulo2']=$this->aplural(strtolower($data['nombremodulo2']));
            $data['pnombremodulomayus2']=$this->aplural(ucfirst($data['nombremodulo2']));
            if (Schema::hasTable($data['nombremodulo'])===false && Schema::hasTable($data['nombremodulo2'])==false)
            {
                if($this->crearmodulo($data)==true)
                {
                    $this->info('Modulos creados exitosamente!!!, Para poder utilizar las tablas debes usar el comando migrate de artisan');
                }
                else
                {
                    $this->info('Ocurrio un error al crear los modulos');
                }
            }
            else
            {
                $this->info('Ya existen tablas con alguno de los nombres dados');
            }
        }
        
    }  

    protected function asistentecrearmodulo_vacio()
    {
        $data = [];
        $data['nombremodulo'] = $this->ask('Cual es el nombre del modulo? (Escribelo en singular)');
        if(File::exists(__DIR__.'/../views/'.$data['nombremodulo']) && File::exists(_DIR_.'/../views/'.$data['nombremodulo2']))
        {
            $this->info('El nombre de alguno de los modulos ya existe. Intente de nuevo con otros nombres');
        }
        else
        {
            $data['nombremodulo']=strtolower($data['nombremodulo']);
            $data['nombremodulomayus']=ucfirst($data['nombremodulo']);
            $data['pnombremodulo']=$this->aplural(strtolower($data['nombremodulo']));
            $data['pnombremodulomayus']=$this->aplural(ucfirst($data['nombremodulo']));

            if (Schema::hasTable($data['nombremodulo'])===false)
            {
                if($this->crearmodulo_vacio($data)==true)
                {
                    $this->info('Modulo creado exitosamente!!!, Para poder utilizar la tabla debes usar el comando migrate de artisan');
                }
                else
                {
                    $this->info('Ocurrio un error al crear los modulos');
                }
            }
            else
            {
                $this->info('Ya existen tablas con alguno de los nombres dados');
            }
        }
        
    }  

    protected function crearmodulo_vacio($datos)
    {
        $_directorio_principal=$this->creardirectorio_principal_vacio($datos);
        $_requests=$this->crear_requests_vacio($datos);
        $_modules=$this->crear_modules_vacio($datos);
        $_crear_migrations=$this->crear_migrations_vacio($datos);
        $_crear_language=$this->crear_language_vacio($datos);
        $_crear_controllers=$this->crear_controllers_vacio($datos);
        //$_crear_routes=true;
        $_crear_routes=$this->crear_routes_vacio($datos);
        $_crear_enlace_menu=$this->crear_enlace_menu_vacio($datos);
        if($_directorio_principal==true&&$_requests==true&&$_modules==true&&$_crear_migrations==true&&
            $_crear_language==true&&$_crear_controllers==true&&$_crear_routes==true&&$_crear_enlace_menu==true)
            {
                return true;
            }    
        return false;
    }


    protected function asistentecrearmodulo_calendario()
    {
        $data = [];
        $data['nombremodulo'] = $this->ask('Cual es el nombre del modulo? (Escribelo en singular)');
        if(File::exists(__DIR__.'/../views/'.$data['nombremodulo']))
        {
            $this->info('El nombre de modulo ya existe. Intente de nuevo con otro nombre');
        }
        else
        {
            $data['nombremodulo']=strtolower($data['nombremodulo']);
            $data['nombremodulomayus']=ucfirst($data['nombremodulo']);
            $data['pnombremodulo']=$this->aplural(strtolower($data['nombremodulo']));
            $data['pnombremodulomayus']=$this->aplural(ucfirst($data['nombremodulo']));
            if (Schema::hasTable($data['nombremodulo'])===false)
            {
                if($this->crearmodulo_calendario($data)==true)
                {
                    $this->info('Modulo creado exitosamente!!!, Para poder utilizar la tabla debes usar el comando migrate de artisan');
                }
                else
                {
                    $this->info('Ocurrio un error al crear el modulo');
                }
            }
            else
            {
                $this->info('Ya existe una tabla con el nombre del modulo especificado');
            }
        }
        
    }  

    protected function crearmodulo($datos)
    {
        $_directorio_principal=$this->creardirectorio_principal($datos);
        $_requests=$this->crear_requests($datos);
        $_modules=$this->crear_modules($datos);
        $_crear_migrations=$this->crear_migrations($datos);
        $_crear_language=$this->crear_language($datos);
        $_crear_controllers=$this->crear_controllers($datos);
        //$_crear_routes=true;
        $_crear_routes=$this->crear_routes($datos);
        $_crear_enlace_menu=$this->crear_enlace_menu($datos);
        if($_directorio_principal==true&&$_requests==true&&$_modules==true&&$_crear_migrations==true&&
            $_crear_language==true&&$_crear_controllers==true&&$_crear_routes==true&&$_crear_enlace_menu==true)
            {
                return true;
            }    
        return false;
    }

    protected function crearmodulo_calendario($datos)
    {
        $_directorio_principal=$this->creardirectorio_principal_calendario($datos);
        $_requests=$this->crear_requests_calendario($datos);
        $_modules=$this->crear_modules_calendario($datos);
        $_crear_migrations=$this->crear_migrations_calendario($datos);
        $_crear_language=$this->crear_language_calendario($datos);
        $_crear_controllers=$this->crear_controllers_calendario($datos);
        //$_crear_routes=true;
        $_crear_routes=$this->crear_routes_calendario($datos);
        $_crear_enlace_menu=$this->crear_enlace_menu_calendario($datos);
        if($_directorio_principal==true&&$_requests==true&&$_modules==true&&$_crear_migrations==true&&
            $_crear_language==true&&$_crear_controllers==true&&$_crear_routes==true&&$_crear_enlace_menu==true)
            {
                return true;
            }    
        return false;
    }

    protected function crear_enlace_menu($datos)
    {
        if(File::append(__DIR__.'/../views/partials/items_menu_lateral.blade.php',
                "{!! CMS::makeLinkForSidebarMenu('CMS::admin.".$datos['pnombremodulo'].".index', trans('CMS::".$datos['pnombremodulo'].".".$datos['pnombremodulo']."'), 'fa fa-file') !!}")!==false)
        {
            if(File::append(__DIR__.'/../views/partials/items_menu_lateral.blade.php',
                "{!! CMS::makeLinkForSidebarMenu('CMS::admin.".$datos['pnombremodulo2'].".index', trans('CMS::".$datos['pnombremodulo2'].".".$datos['pnombremodulo2']."'), 'fa fa-file') !!}")!==false)
            {
                return true;
            }
        }
        return false;
    }

    protected function crear_enlace_menu_calendario($datos)
    {
        if(File::append(__DIR__.'/../views/partials/items_menu_lateral.blade.php',
                "{!! CMS::makeLinkForSidebarMenu('CMS::admin.".$datos['pnombremodulo'].".index', trans('CMS::".$datos['pnombremodulo'].".".$datos['pnombremodulo']."'), 'fa fa-file') !!}")!==false)
        {
            return true;
        }
        return false;
    }

    protected function crear_enlace_menu_vacio($datos)
    {
        if(File::append(__DIR__.'/../views/partials/items_menu_lateral.blade.php',
                "{!! CMS::makeLinkForSidebarMenu('CMS::admin.".$datos['pnombremodulo'].".index', trans('CMS::".$datos['pnombremodulo'].".".$datos['pnombremodulo']."'), 'fa fa-file') !!}")!==false)
        {
            return true;
        }
        return false;
    }

    protected function crear_routes_calendario($datos)
    {
        if(File::put(__DIR__.'/../routes/'.$datos['nombremodulomayus'].'Routes.php',
            "<?php
            Route::resource('".$datos['pnombremodulo']."', '".$datos['pnombremodulomayus']."Controller');")!==false)
        {
                return true;
        }
        return false;
    }

    protected function crear_routes($datos)
    {
        if(File::put(__DIR__.'/../routes/'.$datos['nombremodulomayus'].'Routes.php',
            "<?php
            Route::resource('".$datos['pnombremodulo']."', '".$datos['pnombremodulomayus']."Controller');")!==false)
        {
            if($this->crear_routes_categories($datos)==true)
            {
                return true;
            }
        }
        return false;
    }

    protected function crear_routes_categories($datos)
    {
        if(File::put(__DIR__.'/../routes/'.$datos['nombremodulomayus2'].'Routes.php',
            "<?php
            Route::resource('".$datos['pnombremodulo2']."', '".$datos['pnombremodulomayus2']."Controller');")!==false)
        {
            return true;
        }
        return false;
    }

    protected function crear_routes_vacio($datos)
    {
        if(File::put(__DIR__.'/../routes/'.$datos['nombremodulomayus'].'Routes.php',
            "<?php
            Route::resource('".$datos['pnombremodulo']."', '".$datos['pnombremodulomayus']."Controller');")!==false)
        {
            return true;
        }
        return false;
    }

    protected function crear_controllers($datos)
    {
        if(File::put(__DIR__.'/../Controllers/'.$datos['pnombremodulomayus'].'Controller.php', $this->copiarContenido('Controller', $datos['nombremodulo'], $datos['nombremodulo2']))!==false)
        {
            if(File::put(__DIR__.'/../Controllers/'.$datos['pnombremodulomayus2'].'Controller.php', $this->copiarContenido_categories('Controller', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
            {            
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    protected function crear_controllers_calendario($datos)
    {
        if(File::put(__DIR__.'/../Controllers/'.$datos['pnombremodulomayus'].'Controller.php', $this->copiarContenido_calendario('Controller', $datos['nombremodulo']))!==false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    protected function crear_controllers_vacio($datos)
    {
        if(File::put(__DIR__.'/../Controllers/'.$datos['pnombremodulomayus'].'Controller.php', $this->copiarContenido_vacio('Controller', $datos['nombremodulo']))!==false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    protected function crear_language($datos)
    {
        if(File::put(__DIR__.'/../lang/en/'.$datos['pnombremodulo'].'.php',$this->copiarContenido('lang', $datos['nombremodulo'], $datos['nombremodulo2']))!==false && File::put(__DIR__.'/../lang/en/'.$datos['pnombremodulo2'].'.php',$this->copiarContenido_categories('lang', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
        {
            return true;
        }
        return false;
    }

    protected function crear_language_calendario($datos)
    {
        if(File::put(__DIR__.'/../lang/en/'.$datos['pnombremodulo'].'.php',$this->copiarContenido_calendario('lang', $datos['nombremodulo']))!==false)
        {
            return true;
        }
        return false;
    }

    protected function crear_language_vacio($datos)
    {
        if(File::put(__DIR__.'/../lang/en/'.$datos['pnombremodulo'].'.php',$this->copiarContenido_vacio('lang', $datos['nombremodulo']))!==false)
        {
            return true;
        }
        return false;
    }

    protected function crear_migrations($datos)
    {
        if(File::put(__DIR__.'/../stubs/'.$datos['pnombremodulo'].'.stub',$this->copiarContenido('migrations', $datos['nombremodulo'], $datos['nombremodulo2']))!==false)
        {
            if(File::put(database_path().'/migrations/'.date('y_m_d').'_000000_create_cms_'.$datos['pnombremodulo'].'_table.php',$this->copiarContenido('migrations', $datos['nombremodulo'], $datos['nombremodulo2']))!==false)
            {
                return true;
            }
        }
        return false;
    }

    protected function crear_migrations_calendario($datos)
    {
        if(File::put(__DIR__.'/../stubs/'.$datos['pnombremodulo'].'.stub',$this->copiarContenido_calendario('migrations', $datos['nombremodulo']))!==false)
        {
            if(File::put(database_path().'/migrations/'.date('y_m_d').'_000000_create_cms_'.$datos['pnombremodulo'].'_table.php',$this->copiarContenido_calendario('migrations', $datos['nombremodulo']))!==false)
            {
                return true;
            }
        }
        return false;
    }

    protected function crear_migrations_vacio($datos)
    {
        if(File::put(__DIR__.'/../stubs/'.$datos['pnombremodulo'].'.stub',$this->copiarContenido_vacio('migrations', $datos['nombremodulo']))!==false)
        {
            if(File::put(database_path().'/migrations/'.date('y_m_d').'_000000_create_cms_'.$datos['pnombremodulo'].'_table.php',$this->copiarContenido_vacio('migrations', $datos['nombremodulo']))!==false)
            {
                return true;
            }
        }
        return false;
    }

    protected function crear_modules($datos)
    {
        if(File::makedirectory(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'],0777)==1)
        {
            if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['nombremodulomayus'].'.php', $this->copiarContenido('Modules', $datos['nombremodulo'], $datos['nombremodulo2']))!==false)
            {
                if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['nombremodulomayus'].'Presenter.php', $this->copiarContenido('ModulesPresenter', $datos['nombremodulo'], $datos['nombremodulo2']))!==false)
                {
                    if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['pnombremodulomayus'].'Repo.php', $this->copiarContenido('ModulesRepo', $datos['nombremodulo'], $datos['nombremodulo2']))!==false)
                    {
                        if($this->crear_modules_categories($datos)==true)
                        {    
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    protected function crear_modules_categories($datos)
    {
        if(File::makedirectory(__DIR__.'/../Modules/'.$datos['pnombremodulomayus2'],0777)==1)
        {
            if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus2'].'/'.$datos['nombremodulomayus2'].'.php', $this->copiarContenido_categories('Modules', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
            {
                if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus2'].'/'.$datos['pnombremodulomayus2'].'Repo.php', $this->copiarContenido_categories('ModulesRepo', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
                {
                        return true;
                }
            }
        }
        return false;
    }

    protected function crear_modules_calendario($datos)
    {
        if(File::makedirectory(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'],0777)==1)
        {
            if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['nombremodulomayus'].'.php', $this->copiarContenido_calendario('Modules', $datos['nombremodulo']))!==false)
            {
                if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['nombremodulomayus'].'Presenter.php', $this->copiarContenido_calendario('ModulesPresenter', $datos['nombremodulo']))!==false)
                {
                    if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['pnombremodulomayus'].'Repo.php', $this->copiarContenido_calendario('ModulesRepo', $datos['nombremodulo']))!==false)
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    protected function crear_modules_vacio($datos)
    {
        if(File::makedirectory(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'],0777)==1)
        {
            if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['nombremodulomayus'].'.php', $this->copiarContenido_vacio('Modules', $datos['nombremodulo']))!==false)
            {
                if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['nombremodulomayus'].'Presenter.php', $this->copiarContenido_vacio('ModulesPresenter', $datos['nombremodulo']))!==false)
                {
                    if(File::put(__DIR__.'/../Modules/'.$datos['pnombremodulomayus'].'/'.$datos['pnombremodulomayus'].'Repo.php', $this->copiarContenido_vacio('ModulesRepo', $datos['nombremodulo']))!==false)
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    protected function crear_requests($datos)
    {
        if(File::put(__DIR__.'/../Requests/Create'.$datos['nombremodulomayus'].'.php', $this->copiarContenido('CreateRequest', $datos['nombremodulo'], $datos['nombremodulo2']))!==false)
        {
            if(File::put(__DIR__.'/../Requests/Update'.$datos['nombremodulomayus'].'.php', $this->copiarContenido('UpdateRequest', $datos['nombremodulo'], $datos['nombremodulo2']))!==false)
            {
                if(File::put(__DIR__.'/../Requests/'.$datos['nombremodulomayus'].'Request.php', $this->copiarContenido_categories('Request', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
                {
                    if($this->crear_requests_categories($datos)==true)
                    {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    protected function crear_requests_categories($datos)
    {
        if(File::put(__DIR__.'/../Requests/Create'.$datos['nombremodulomayus2'].'.php', $this->copiarContenido_categories('Request', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
        {
            return true;
        }
        return false;
    }

    protected function crear_requests_calendario($datos)
    {
        if(File::put(__DIR__.'/../Requests/Create'.$datos['nombremodulomayus'].'.php', $this->copiarContenido_calendario('CreateRequest', $datos['nombremodulo']))!==false)
        {
            if(File::put(__DIR__.'/../Requests/Update'.$datos['nombremodulomayus'].'.php', $this->copiarContenido_calendario('UpdateRequest', $datos['nombremodulo']))!==false)
            {
                if(File::put(__DIR__.'/../Requests/Delete'.$datos['nombremodulomayus'].'.php', $this->copiarContenido_calendario('DeleteRequest', $datos['nombremodulo']))!==false)
                {
                    return true;
                }
            }
        }
        return false;
    }

    protected function crear_requests_vacio($datos)
    {
        if(File::put(__DIR__.'/../Requests/Create'.$datos['nombremodulomayus'].'.php', $this->copiarContenido_vacio('CreateRequest', $datos['nombremodulo']))!==false)
        {
            if(File::put(__DIR__.'/../Requests/Update'.$datos['nombremodulomayus'].'.php', $this->copiarContenido_vacio('UpdateRequest', $datos['nombremodulo']))!==false)
            {
                return true;
            }
        }
        return false;
    }

    protected function creardirectorio_principal($datos)
    {
        if(File::makedirectory(__DIR__.'/../views/'.$datos['pnombremodulo'],0777)==1)
        {
            if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/index.blade.php', $this->copiarContenido('index.blade', $datos['nombremodulo'],$datos['nombremodulo2']))!==false)
            {
                if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/edit.blade.php', $this->copiarContenido('edit.blade', $datos['nombremodulo'],$datos['nombremodulo2']))!==false)
                {
                    if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/create.blade.php', $this->copiarContenido('create.blade', $datos['nombremodulo'],$datos['nombremodulo2']))!==false)
                    {
                        if(File::makedirectory(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials',0777)==1)
                        {
                            if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials/inputs.blade.php', $this->copiarContenido('inputs.blade', $datos['nombremodulo'],$datos['nombremodulo2']))!==false)
                            {
                                if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials/scripts.blade.php', $this->copiarContenido('scripts.blade', $datos['nombremodulo'],$datos['nombremodulo2']))!==false)
                                {
                                    if($this->crearcategories($datos)==true)
                                    {
                                        return true;
                                    }
                                }                                   
                            }                
                        }
                    }
                }
            }
        } 
        return false;
    }

    protected function crearcategories($datos)
    {
        if(File::makedirectory(__DIR__.'/../views/'.$datos['pnombremodulo2'],0777)==1)
        {
            if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo2'].'/index.blade.php', $this->copiarContenido_categories('index.blade', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
            {
                if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo2'].'/edit.blade.php', $this->copiarContenido_categories('edit.blade', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
                {
                    if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo2'].'/create.blade.php', $this->copiarContenido_categories('create.blade', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
                    {
                        if(File::makedirectory(__DIR__.'/../views/'.$datos['pnombremodulo2'].'/partials',0777)==1)
                        {
                            if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo2'].'/partials/inputs.blade.php', $this->copiarContenido_categories('inputs.blade', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
                            {
                                if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo2'].'/partials/scripts.blade.php', $this->copiarContenido_categories('scripts.blade', $datos['nombremodulo2'], $datos['nombremodulo']))!==false)
                                {
                                    return true;
                                }                                   
                            }                
                        }
                    }
                }
            }
        } 
        return false;
    }


    protected function creardirectorio_principal_calendario($datos)
    {
        if(File::makedirectory(__DIR__.'/../views/'.$datos['pnombremodulo'],0777)==1)
        {
            if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/index.blade.php', $this->copiarContenido_calendario('index.blade', $datos['nombremodulo']))!==false)
            {
                if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/edit.blade.php', $this->copiarContenido_calendario('edit.blade', $datos['nombremodulo']))!==false)
                {
                    if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/create.blade.php', $this->copiarContenido_calendario('create.blade', $datos['nombremodulo']))!==false)
                    {
                        if(File::makedirectory(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials',0777)==1)
                        {
                            if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials/inputs.blade.php', $this->copiarContenido_calendario('inputs.blade', $datos['nombremodulo']))!==false)
                            {
                                if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials/scripts.blade.php', $this->copiarContenido_calendario('scripts.blade', $datos['nombremodulo']))!==false)
                                {
                                    return true;
                                }                                   
                            }                
                        }
                    }
                }
            }
        } 
        return false;
    }


    protected function creardirectorio_principal_vacio($datos)
    {
        if(File::makedirectory(__DIR__.'/../views/'.$datos['pnombremodulo'],0777)==1)
        {
            if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/index.blade.php',$this->copiarContenido_vacio('index.blade', $datos['nombremodulo']))!==false)
            {
                if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/edit.blade.php',$this->copiarContenido_vacio('edit.blade', $datos['nombremodulo']))!==false)
                {
                    if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/create.blade.php', $this->copiarContenido_vacio('create.blade', $datos['nombremodulo']))!==false)
                    {
                        if(File::makedirectory(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials',0777)==1)
                        {
                            if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials/inputs.blade.php',$this->copiarContenido_vacio('inputs.blade', $datos['nombremodulo']))!==false)
                            {
                                if(File::put(__DIR__.'/../views/'.$datos['pnombremodulo'].'/partials/scripts.blade.php',$this->copiarContenido_vacio('scripts.blade', $datos['nombremodulo']))!==false)
                                {
                                    return true;
                                }                                   
                            }                
                        }
                    }
                }
            }
        } 
        return false;
    }    

    private function copiarContenido($tipo, $nombreModulo, $nombreModulo2)
    {
        $plural=$this->aplural($nombreModulo);
        $plural2=$this->aplural($nombreModulo2);
        $contenido="";
        switch ($tipo) 
        {
            case 'Controller':
                $contenido = File::get(__DIR__.'/../molds/articles/controller.txt');
                break;
            case 'lang':
                $contenido = File::get(__DIR__.'/../molds/articles/lang.txt');
                break;
            case 'Modules':
                $contenido = File::get(__DIR__.'/../molds/articles/module.txt');
                break;
            case 'ModulesPresenter':
                $contenido = File::get(__DIR__.'/../molds/articles/modulepresenter.txt');
                break;
            case 'ModulesRepo':
                $contenido = File::get(__DIR__.'/../molds/articles/modulerepo.txt');
                break;
            case 'CreateRequest':
                $contenido = File::get(__DIR__.'/../molds/articles/createrequest.txt');
                break;
            case 'UpdateRequest':
                $contenido = File::get(__DIR__.'/../molds/articles/updaterequest.txt');
                break;
            case 'create.blade':
                $contenido = File::get(__DIR__.'/../molds/articles/create.blade.txt');
                break;
            case 'edit.blade':
                $contenido = File::get(__DIR__.'/../molds/articles/edit.blade.txt');
                break;
            case 'index.blade':
                $contenido = File::get(__DIR__.'/../molds/articles/index.blade.txt');
                break;
            case 'inputs.blade':
                $contenido = File::get(__DIR__.'/../molds/articles/inputs.blade.txt');
                break;
            case 'scripts.blade':
                $contenido = File::get(__DIR__.'/../molds/articles/scripts.blade.txt');
                break;
            case 'migrations':
                $contenido = File::get(__DIR__.'/../molds/articles/table.txt');
                break;           
        }
        $contenido = str_replace("articles", lcfirst($plural), $contenido);
        $contenido = str_replace("Articles", ucfirst($plural), $contenido);
        $contenido = str_replace("article", lcfirst($nombreModulo), $contenido);
        $contenido = str_replace("Article", ucfirst($nombreModulo), $contenido);

        $contenido = str_replace("categories", lcfirst($plural2), $contenido);
        $contenido = str_replace("Categories", ucfirst($plural2), $contenido);
        $contenido = str_replace("category", lcfirst($nombreModulo2), $contenido);
        $contenido = str_replace("Category", ucfirst($nombreModulo2), $contenido);        
        return $contenido;
    }


    private function copiarContenido_vacio($tipo, $nombreModulo)
    {
        $plural=$this->aplural($nombreModulo);
        $contenido="";
        switch ($tipo) 
        {
            case 'Controller':
                $contenido = File::get(__DIR__.'/../molds/vacio/controller.txt');
                break;
            case 'lang':
                $contenido = File::get(__DIR__.'/../molds/vacio/lang.txt');
                break;
            case 'Modules':
                $contenido = File::get(__DIR__.'/../molds/vacio/module.txt');
                break;
            case 'ModulesPresenter':
                $contenido = File::get(__DIR__.'/../molds/vacio/modulepresenter.txt');
                break;
            case 'ModulesRepo':
                $contenido = File::get(__DIR__.'/../molds/vacio/modulerepo.txt');
                break;
            case 'CreateRequest':
                $contenido = File::get(__DIR__.'/../molds/vacio/createrequest.txt');
                break;
            case 'UpdateRequest':
                $contenido = File::get(__DIR__.'/../molds/vacio/updaterequest.txt');
                break;
            case 'create.blade':
                $contenido = File::get(__DIR__.'/../molds/vacio/create.blade.txt');
                break;
            case 'edit.blade':
                $contenido = File::get(__DIR__.'/../molds/vacio/edit.blade.txt');
                break;
            case 'index.blade':
                $contenido = File::get(__DIR__.'/../molds/vacio/index.blade.txt');
                break;
            case 'inputs.blade':
                $contenido = File::get(__DIR__.'/../molds/vacio/inputs.blade.txt');
                break;
            case 'scripts.blade':
                $contenido = File::get(__DIR__.'/../molds/vacio/scripts.blade.txt');
                break;
            case 'migrations':
                $contenido = File::get(__DIR__.'/../molds/vacio/table.txt');
                break;           
        }
        $contenido = str_replace("articles", lcfirst($plural), $contenido);
        $contenido = str_replace("Articles", ucfirst($plural), $contenido);
        $contenido = str_replace("article", lcfirst($nombreModulo), $contenido);
        $contenido = str_replace("Article", ucfirst($nombreModulo), $contenido);
        return $contenido;
    }

    private function copiarContenido_categories($tipo, $nombreModulo, $nombreModulo2)
    {
        $plural=$this->aplural($nombreModulo);
        $plural2=$this->aplural($nombreModulo2);
        $contenido="";
        switch ($tipo) 
        {
            case 'Controller':
                $contenido = File::get(__DIR__.'/../molds/categories/controller.txt');
                break;
            case 'lang':
                $contenido = File::get(__DIR__.'/../molds/categories/lang.txt');
                break;
            case 'Modules':
                $contenido = File::get(__DIR__.'/../molds/categories/module.txt');
                break;
            case 'ModulesRepo':
                $contenido = File::get(__DIR__.'/../molds/categories/modulerepo.txt');
                break;
            case 'Request':
                $contenido = File::get(__DIR__.'/../molds/categories/request.txt');
                break;
            case 'create.blade':
                $contenido = File::get(__DIR__.'/../molds/categories/create.blade.txt');
                break;
            case 'edit.blade':
                $contenido = File::get(__DIR__.'/../molds/categories/edit.blade.txt');
                break;
            case 'index.blade':
                $contenido = File::get(__DIR__.'/../molds/categories/index.blade.txt');
                break;
            case 'inputs.blade':
                $contenido = File::get(__DIR__.'/../molds/categories/inputs.blade.txt');
                break;
            case 'migrations':
                $contenido = File::get(__DIR__.'/../molds/categories/table.txt');
                break;           
        }
        $contenido = str_replace("categories", lcfirst($plural), $contenido);
        $contenido = str_replace("Categories", ucfirst($plural), $contenido);
        $contenido = str_replace("category", lcfirst($nombreModulo), $contenido);
        $contenido = str_replace("Category", ucfirst($nombreModulo), $contenido);

        $contenido = str_replace("articles", lcfirst($plural2), $contenido);
        $contenido = str_replace("Articles", ucfirst($plural2), $contenido);
        $contenido = str_replace("article", lcfirst($nombreModulo2), $contenido);
        $contenido = str_replace("Article", ucfirst($nombreModulo2), $contenido);        
        return $contenido;
    }

    private function copiarContenido_calendario($tipo, $nombreModulo)
    {
        $plural=$this->aplural($nombreModulo);
        $contenido="";
        switch ($tipo) 
        {
            case 'Controller':
                $contenido = File::get(__DIR__.'/../molds/calendario/controller.txt');
                break;
            case 'lang':
                $contenido = File::get(__DIR__.'/../molds/calendario/lang.txt');
                break;
            case 'Modules':
                $contenido = File::get(__DIR__.'/../molds/calendario/module.txt');
                break;
            case 'ModulesPresenter':
                $contenido = File::get(__DIR__.'/../molds/calendario/modulepresenter.txt');
                break;
            case 'ModulesRepo':
                $contenido = File::get(__DIR__.'/../molds/calendario/modulerepo.txt');
                break;
            case 'CreateRequest':
                $contenido = File::get(__DIR__.'/../molds/calendario/createrequest.txt');
                break;
            case 'UpdateRequest':
                $contenido = File::get(__DIR__.'/../molds/calendario/updaterequest.txt');
                break;
            case 'create.blade':
                $contenido = File::get(__DIR__.'/../molds/calendario/create.blade.txt');
                break;
            case 'edit.blade':
                $contenido = File::get(__DIR__.'/../molds/calendario/edit.blade.txt');
                break;
            case 'index.blade':
                $contenido = File::get(__DIR__.'/../molds/calendario/index.blade.txt');
                break;
            case 'inputs.blade':
                $contenido = File::get(__DIR__.'/../molds/calendario/inputs.blade.txt');
                break;
            case 'scripts.blade':
                $contenido = File::get(__DIR__.'/../molds/calendario/scripts.blade.txt');
                break;
            case 'migrations':
                $contenido = File::get(__DIR__.'/../molds/calendario/table.txt');
                break;
            case 'DeleteRequest':
                $contenido = File::get(__DIR__.'/../molds/calendario/deleterequest.txt');
                break;           
        }
        $contenido = str_replace("calendarios", lcfirst($plural), $contenido);
        $contenido = str_replace("Calendarios", ucfirst($plural), $contenido);
        $contenido = str_replace("calendario", lcfirst($nombreModulo), $contenido);
        $contenido = str_replace("Calendario", ucfirst($nombreModulo), $contenido);        
        return $contenido;
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
