<?php

namespace App\Console\Commands;

use App\Models\Permission;
use Illuminate\Console\Command;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;


class GeneratePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating Permissions .....');

        $models = modelCollection()['final_output'];

        foreach($models as $key=>$name){
            $array_value = ['list','create','update','delete'];

            foreach($array_value as $value){
                Permission::firstOrCreate(
                    ['name'=>$value . ' ' . $key,
                    'guard_name'=>'backpack'],
                );
            }
            // $inflector = new Inflector(new NoopWordInflector(), new NoopWordInflector());

            // $link = $inflector->tableize($name);
            // $link = \str_replace('_','-',$link);

            // MenuItem::firstOrCreate(
            //     [
            //         'model_name' => $key,
            //     ],
            //     [
            //         'name_en' => $name,
            //         'name_lc' => $name,
            //         'type' => 'internal_link',
            //         'depth' => '1',
            //         'link' => $link,
            //         'created_by'=>1
            //     ],
            // );

        }

        $this->info("\n\nPermissions successfully created !!!!");


    }
}
