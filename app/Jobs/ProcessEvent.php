<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $event
    ){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        echo "Working on the event: " . $this->event['index']. "\n";

        //simulate some failures
        if(rand(1, 30) < 10 ){
            //Throwing an exception allows the queue:worker to continue
            throw new \RuntimeException("Failed");
        }
    }
}
