<?php

namespace Douma\RecordLocks\Commands;

use Douma\RecordLocks\Repositories\RecordLockRepository;
use Illuminate\Console\Command;

class RemoveLocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locks:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var RecordLockRepository
     */
    private $lockRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(RecordLockRepository $lockRepository)
    {
        parent::__construct();
        $this->lockRepository = $lockRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->lockRepository->clear();
        $this->info("Locks cleared");
    }
}
