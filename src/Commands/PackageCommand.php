<?php 

namespace Generators\Commands;

use Generators\Helpers\FileHelper;
use Generators\Exceptions\ArgumentParserException;

class PackageCommand extends BaseCommand 
{

	protected $signature = 'package
        {vendor : Vendor of the package.}
        {name : Name of the package.}
    ';

	protected $description = 'Generates a new develop package as a tool resource';

    public function handle()
    {
        $this->vendor = $this->argument('vendor');
        $this->raw_name = $this->argument('name');
        $this->name = $this->handleName($this->raw_name);
        $this->generateDocument();
        $this->generateConfig();
        $this->generateComposer();
        $this->generateProviderService();
        $this->generateRoutes();
        $this->generateArguments();
        $this->generateTemplates();
        $this->generateExceptions();
        $this->generateHelpers();
        $this->generateCommands();
        $this->generateHandlers();
        $this->generateModels();
        
        // $this->generateReadme();

        $this->updateAppServeiceProvider();
        $this->updateAppBootstrap();
        $this->updateRootComposer();

    }

    protected function handleName($name = '')
    {
        //1.判断特殊字符是否只有-和_
        if (preg_match("/^[a-zA-Z0-9-_]*$/", $name))
        {
            //转换为驼峰
            return preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches) {
                    return strtoupper($matches[2]);
            }, $name);
        } else {
            //输出错误
            ArgumentParserException::Error("name can only accept numbers, alphabets, '—' and '_'");
        }
    }

    protected function generateDocument()
    {
        $vendor = ucfirst($this->vendor);
        $name = ucfirst($this->name);
        $this->package_path = str_replace("\\", "/", base_path())."/packages/{$vendor}/{$name}";
        FileHelper::createDir($this->package_path);
    }

    protected function generateConfig()
    {
        $main_content = $this->getTemplate('MainConfig')
                             ->with(
                                array(
                                    'vendor' => $this->vendor,
                                    'name' => ucfirst($this->name)
                                )
                               )
                             ->get();

        $this->save($main_content, "{$this->package_path}/config/main.config.php", "MainConfig", true);

        $database_content = $this->getTemplate('DataBaseConfig')
                                 ->with(
                                    array(
                                        'name' => $this->raw_name
                                    )
                                   )
                                 ->get();

        $this->save($database_content, "{$this->package_path}/config/database.config.php", "DatabaseConfig", true);

        $const_content = $this->getTemplate('ConstConfig')
                ->with([
                    'name' => $this->name,
                    'raw_name' => strtoupper($this->raw_name)."TOOL_NAME"
                ])
            ->get();

        $this->save($const_content, "{$this->package_path}/config/CONST.php", "CONST", true);
    }

    protected function generateComposer()
    {
        $content = $this->getTemplate('Composer')
                        ->with(array(
                            'vendor' => strtolower($this->vendor),
                            'name' => strtolower($this->name),
                            'psr4' => ucfirst($this->vendor)."\\\\".ucfirst($this->name)
                            ))
                        ->get();

        $this->save($content, "{$this->package_path}/composer.json", "composer", true);
    }

    protected function generateProviderService()
    {
        $content = $this->getTemplate('Provider')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                    'vendor' => ucfirst($this->vendor),
                    'name' => ucfirst($this->name)
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/CommandsServiceProvider.php", "CommandsServiceProvider", true);
    }

    protected function generateArguments()
    {
        $content = $this->getTemplate('ArgumentFormat')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Argument/ArgumentFormat.php", "ArgumentFormat", true);

        $content = $this->getTemplate('ArgumentFormatLoader')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Argument/ArgumentFormatLoader.php", "ArgumentFormatLoader", true);

        $content = $this->getTemplate('ArgumentParser')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Argument/ArgumentParser.php", "ArgumentParser", true);
    }

    protected function generateTemplates()
    {
        $content = $this->getTemplate('Template')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Template/Template.php", "Template", true);

        $content = $this->getTemplate('TemplateLoader')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Template/TemplateLoader.php", "TemplateLoader", true);
    }

    protected function generateExceptions()
    {
        $content = $this->getTemplate('ArgumentFormatException')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Exceptions/ArgumentFormatException.php", "ArgumentFormatException", true);

        $content = $this->getTemplate('ArgumentParserException')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Exceptions/ArgumentParserException.php", "ArgumentParserException", true);

        $content = $this->getTemplate('TemplateException')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Exceptions/TemplateException.php", "TemplateException", true);

        $content = $this->getTemplate('DefaultException')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Exceptions/DefaultException.php", "DefaultException", true);
    }

    protected function generateHelpers()
    {
        $content = $this->getTemplate('FileHelper')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Helpers/FileHelper.php", "FileHelper", true);

        $content = $this->getTemplate('LogHelper')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                    'name' => strtoupper($this->raw_name)
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Helpers/LogHelper.php", "LogHelper", true);

        $content = $this->getTemplate('SystemConfig')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Helpers/SystemConfig.php", "SystemConfig", true);
    }

    protected function generateCommands()
    {
        $content = $this->getTemplate('BaseCommand')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Commands/BaseCommand.php", "BaseCommand", true);

        $content = $this->getTemplate('ExampleCommand')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Commands/ExampleCommand.php", "ExampleCommand", true);
    }

    protected function generateRoutes()
    {

        $content = $this->getTemplate('Route')
                ->with([
                    'name' => ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/route/web.php", "Route", true);
    }

    protected function generateHandlers()
    {

        $content = $this->getTemplate('ExampleHandler')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Handlers/ExampleHandler.php", "ExampleHandler", true);
    }

    protected function generateModels()
    {

        $content = $this->getTemplate('ExampleModel')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Models/exampleModel.php", "exampleModel", true);

        $content = $this->getTemplate('DBModel')
                ->with([
                    'psr4' => ucfirst($this->vendor)."\\".ucfirst($this->name),
                ])
            ->get();

        $this->save($content, "{$this->package_path}/src/Models/dbModel.php", "dbModel", true);
    }

    protected function updateRootComposer()
    {
        $origin_composer = FileHelper::getFileContent(base_path().'\composer.json');
        $flag = false;
        foreach ($origin_composer["repositories"] as $key => $value) 
        {
            if ($value['url'] === $this->package_path)
            {
                $flag = true;
            }
        }
        if (!$flag) 
        {
            $origin_composer["repositories"][] = array('type'=>'path','url'=>$this->package_path);
            $origin_composer["require"][strtolower($this->vendor)."/".strtolower($this->name)] = 'dev-master';
            if (
                !FileHelper::writeToFile(
                base_path().'\composer.json', 
                json_encode($origin_composer, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES),
                false
             )
            ) {
                ArgumentParserException::Error("System Error, try again");
            } 
            exec("cd ".base_path(), $out);
            exec("composer update", $out);
        }
    }

    protected function updateAppServeiceProvider()
    {
        $app_serveice_provider_path = base_path()."/app/Providers/AppServiceProvider.php";
        $find_preg = "public function register()\n    {";
        $file = FileHelper::getFileContent($app_serveice_provider_path);
        $content = "\n        ".'$this->app->register('."'".ucfirst($this->vendor)."\\".ucfirst($this->name)."\CommandsServiceProvider');\n";
        if (!strpos($file, $content))
        {
            $index = strpos($file, $find_preg) + strlen($find_preg);
            $new_file = substr_replace($file, $content, $index, 0);
            FileHelper::writeToFile($app_serveice_provider_path, $new_file, false);
        }
        return;
    }

    protected function updateAppBootstrap()
    {   
        $app_bootstrap_path = base_path()."/bootstrap/app.php";
        $find_preg = '$app->register(';
        $file = FileHelper::getFileContent($app_bootstrap_path);
        $content = '$app->register('.ucfirst($this->vendor)."\\".ucfirst($this->name).'\commandsServiceProvider::class);'."\n";
        if (!strpos($file, $content))
        {
            $index = strpos($file, $find_preg);
            $new_file = substr_replace($file, $content, $index, 0);
            FileHelper::writeToFile($app_bootstrap_path, $new_file, false);
        }
    }

    protected function getAsArrayFields($arg, $isOption = true)
    {
    	$arg = ($isOption) ? $this->option($arg) : $this->argument($arg);
        if(is_string($arg)){
        	$arg = explode(',', $arg);
        } else if(! is_array($arg)) {
            $arg = array();
        }
        return implode(', ', array_map(function($item){
            return '"' . $item . '"';
        }, $arg));
    }

    protected function getNamespace()
    {
    	return str_replace(' ', '\\', ucwords(str_replace('/', ' ', $this->option('path'))));
    }

    protected function getRelations()
    {
        $relations = array_merge(array(),
            $this->getRelationsByType('hasOne', 'has-one'),
            $this->getRelationsByType('hasMany', 'has-many'),
            $this->getRelationsByType('belongsTo', 'belongs-to'),
            $this->getRelationsByType('belongsToMany', 'belongs-to-many', true)
        );

        if (empty($relations))
        {
            return "    // Relationships";
        }

        return implode(PHP_EOL, $relations);
    }

    protected function getRelationsByType($type, $option, $withTimestamps = false)
    {
        $relations = array();
        $option = $this->option($option);
        if ($option)
        {
            $items = $this->getArgumentParser('relations')->parse($option);

            $template = ($withTimestamps) ? 'model/relation-with-timestamps' : 'model/relation';
            $template = $this->getTemplate($template);
            foreach ($items as $item) 
            {
                $item['type'] = $type;
                if (!$item['model'])
                {
                    $item['model'] = $this->getNamespace() . '\\' . ucwords(str_singular($item['name']));
                } elseif (strpos($item['model'], '\\') === false) {
                    $item['model'] = $this->getNamespace() . '\\' . $item['model'];
                }
                $relations[] = $template->with($item)->get();
            }
        }
        return $relations;
    }

    protected function getRules()
    {
        $rules = $this->option('rules');
        if (!$rules)
        {
            return "        // Validation rules";
        }
        $items = $rules;
        if (!$this->option('parsed'))
        {
            $items = $this->getArgumentParser('rules')->parse($rules);
        }
        $rules = array();
        $template = $this->getTemplate('model/rule');
        foreach ($items as $item) 
        {
            $rules[] = $template->with($item)->get();
        }

        return implode(PHP_EOL, $rules);
    }

    protected function getAdditional()
    {
        return $this->option('timestamps') == 'false'
            ? "    public \$timestamps = false;" . PHP_EOL . PHP_EOL
            : '';
    }

    protected function getUses()
    {
        return $this->option('soft-deletes') == 'true'
            ? '    use \Illuminate\Database\Eloquent\SoftDeletes;' . PHP_EOL . PHP_EOL
            : '';
    }

}
