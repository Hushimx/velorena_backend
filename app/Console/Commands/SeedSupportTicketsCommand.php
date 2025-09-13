<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\SupportTicketSeeder;

class SeedSupportTicketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:support-tickets {--fresh : Clear existing support tickets first}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the database with sample support tickets and replies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('fresh')) {
            $this->info('Clearing existing support tickets...');
            
            // Disable foreign key checks temporarily
            \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \App\Models\SupportTicketReply::truncate();
            \App\Models\SupportTicket::truncate();
            \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            $this->info('Existing support tickets cleared.');
        }

        $this->info('Seeding support tickets...');
        
        $seeder = new SupportTicketSeeder();
        $seeder->setCommand($this);
        $seeder->run();

        $this->info('✅ Support tickets seeded successfully!');
        $this->info('You can now visit the admin panel to see the support tickets in action.');
        $this->info('Admin URL: /admin/support-tickets');
        $this->info('Statistics URL: /admin/support-tickets-statistics');
    }
}
