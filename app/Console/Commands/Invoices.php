<?php

namespace App\Console\Commands;

use App\Models\FTPFolder;
use App\Models\FTPSetting;
use Faker\Provider\DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use App\Models\Util;
use App\Services\ReportsService;
use App\Services\InvoiceService;
use Carbon\Carbon;
use GrahamCampbell\Flysystem\Facades\Flysystem;

class Invoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans the source folder for invoices that exist in the database, merges them with a 
        matching receiving report, copies to destination directories, and updates the file path in the database.';

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
     * @return mixed
     */
    public function handle(InvoiceService $InvoiceService)
    {
        $InvoiceService->runArtisan();
        return;

    }
}
