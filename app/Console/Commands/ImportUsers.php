<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class ImportUsers extends Command
{
    protected $signature = 'import:users {file}';
    protected $description = 'Import users from an Excel or CSV file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error('File does not exist.');
            return 1;
        }

        // Import the file using the UsersImport class
        Excel::import(new UsersImport, $file);

        $this->info('Data imported successfully!');
        return 0;
    }
}
