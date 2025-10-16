<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:db {db_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mysql database schema based on the database config file';

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
        $db_name = $this->argument('db_name')
        ?: env('DB_DATABASE');
        $charset = env('DB_CHARSET')
        ?: config('database.connections.mysql.charset', 'utf8mb4');

        $collation = env('DB_COLLATION') ?: config('database.connections.mysql.collation', 'utf8mb4_unicode_ci');

        config(['database.connections.mysql.database' => null]);
        $query = "CREATE DATABASE IF NOT EXISTS $db_name CHARACTER SET $charset COLLATE $collation;";

        DB::statement($query);

        config(['database.connections.mysql.database' => $db_name]);
        $this->info("Database $db_name created successfully");
    }
}
