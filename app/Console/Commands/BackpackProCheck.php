<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class BackpackProCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disable:backpack_pro';

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
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;

    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = base_path('vendor/backpack/crud/src/app/Library/CrudPanel/CrudFilter.php');
        $file = $this->files->get($path);
        $file = str_replace('if (! backpack_pro()) {', 'if (backpack_pro()) {', $file);
        $this->files->put($path, $file);
        $this->info('Done !!');


        $path = base_path('vendor/backpack/crud/src/app/Library/CrudPanel/Traits/Read.php');
        $file = $this->files->get($path);
        $file = str_replace('if (! backpack_pro()) {', 'if (backpack_pro()) {', $file);
        $this->files->put($path, $file);
        $this->info('Done !!');

        $file = base_path('vendor/backpack/crud/src/app/Library/CrudPanel/CrudFilter.php');
        $content=file_get_contents($file);
        $lines = file($file); 
        $repalce_value = $lines[28];
        $string_to_replace = str_replace($repalce_value, "{" , $repalce_value);
        $content_chunks=explode($repalce_value, $content);
        $content=implode($string_to_replace, $content_chunks);
        file_put_contents($file, $content);
         $this->info('Done !!');

        $repalce_value = $lines[29];
        $string_to_replace = "//" . $repalce_value;
        $content_chunks=explode($repalce_value, $content);
        $content=implode($string_to_replace, $content_chunks);
        file_put_contents($file, $content);


        return 0;
    }
}
