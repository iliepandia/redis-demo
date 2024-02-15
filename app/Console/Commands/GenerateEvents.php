<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class GenerateEvents extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-events {count} {delay=10}  --verbose';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate random events that will be then pushed into the queue.';

    protected function generatePayload(): string
    {
        $words = ['Ilie', 'Laravel', 'Redis', 'Queue', 'Stress', 'PHP', 'Event', 'Monitor', 'the', 'and', 'because', 'works'];
        $wordCount = rand( 10, 200);
        $phrase = [];
        while($wordCount){
            $phrase[] = $words[rand(0, count($words)-1)];
            $wordCount--;
        }
        return implode( " ", $phrase );
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //this will help me link an event to particular client
        $clientTag = time() . '-' . rand( 100000, 200000 );
        $count = $this->argument('count');
        $total = $count;
        $delay = $this->argument('delay');
        if($delay < 1 ){
            $this->line("Delay will default to 1ms.");
            $delay = 1;
        }

        $this->line( "Client Tag is: $clientTag");

        $bar = $this->output->createProgressBar($count);
        $bar->start();
        while($count){
            $event = [
                'client' => $clientTag,
                'index' => $total-$count+1,
                'score' => rand( 1, 10000 ),
                'payload' => $this->generatePayload(),
            ];

            if( $this->option('verbose')){
                $this->newLine(2);
                $this->line(print_r($event, 1));
            }
            $count--;
            usleep($delay * 1000 );
            $bar->advance();
        }
        $bar->finish();
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'count' => [ 'How many events do you want to generate?', 'E.g. 10'],
        ];
    }
}
