<?php

namespace App\Console\Commands;

use App\Repositories\AutomaticRegistrationRepository;
use Illuminate\Console\Command;

class AutomaticRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'automatic:registration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register users from source file.';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $automaticRegister = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AutomaticRegistrationRepository $automaticRegister)
    {
        parent::__construct();

    	$this->automaticRegister = $automaticRegister;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$this->automaticRegister->contract();
    }
}
