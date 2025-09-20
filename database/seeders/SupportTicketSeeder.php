<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Storage;

class SupportTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and admins to assign tickets to
        $users = User::take(10)->get();
        $admins = Admin::take(3)->get();

        if ($users->isEmpty() || $admins->isEmpty()) {
            $this->command->warn('No users or admins found. Please run UserSeeder and AdminSeeder first.');
            return;
        }

        $this->command->info('Creating support tickets...');

        // Sample ticket data
        $ticketData = [
            [
                'subject' => 'Unable to access my account after password reset',
                'description' => 'I reset my password yesterday but I still cannot log into my account. I keep getting an error message saying "Invalid credentials" even though I\'m using the new password I just set. Please help me regain access to my account.',
                'priority' => 'high',
                'category' => 'technical',
                'status' => 'open',
            ],
            [
                'subject' => 'Billing issue - charged twice for same service',
                'description' => 'I noticed that I was charged twice for the premium subscription this month. The first charge was on the 1st for $29.99, and then again on the 3rd for the same amount. I only signed up once and should only be charged once. Please refund the duplicate charge.',
                'priority' => 'urgent',
                'category' => 'billing',
                'status' => 'in_progress',
            ],
            [
                'subject' => 'Feature request: Dark mode for mobile app',
                'description' => 'I would love to see a dark mode option added to the mobile application. The current bright white interface is hard on the eyes, especially when using the app at night. Many other apps have this feature and it would greatly improve the user experience.',
                'priority' => 'low',
                'category' => 'feature_request',
                'status' => 'pending',
            ],
            [
                'subject' => 'App crashes when uploading large files',
                'description' => 'Every time I try to upload a file larger than 10MB, the app crashes and I have to restart it. This happens consistently on both Android and iOS devices. The files I need to upload are work documents that are typically 15-20MB in size.',
                'priority' => 'high',
                'category' => 'bug_report',
                'status' => 'resolved',
            ],
            [
                'subject' => 'How to change my profile picture?',
                'description' => 'I can\'t figure out how to change my profile picture. I\'ve looked through all the settings but I don\'t see an option to upload a new photo. Could you please guide me through the process or let me know if this feature is available?',
                'priority' => 'medium',
                'category' => 'general',
                'status' => 'closed',
            ],
            [
                'subject' => 'Email notifications not working',
                'description' => 'I\'m not receiving any email notifications for new messages or updates. I\'ve checked my spam folder and confirmed that my email address is correct in my profile. The notification settings show that emails are enabled, but I haven\'t received any emails in the past week.',
                'priority' => 'medium',
                'category' => 'technical',
                'status' => 'open',
            ],
            [
                'subject' => 'Request for account deletion',
                'description' => 'I would like to permanently delete my account and all associated data. I no longer use this service and want to ensure my personal information is removed from your systems. Please confirm the deletion process and timeline.',
                'priority' => 'medium',
                'category' => 'general',
                'status' => 'pending',
            ],
            [
                'subject' => 'Payment method update not saving',
                'description' => 'I tried to update my credit card information in the billing section, but the changes are not being saved. I\'ve tried multiple times and even cleared my browser cache, but the old card information keeps showing up. This is preventing me from making payments.',
                'priority' => 'high',
                'category' => 'billing',
                'status' => 'in_progress',
            ],
            [
                'subject' => 'Slow loading times on dashboard',
                'description' => 'The dashboard has been loading very slowly for the past few days. It takes over 30 seconds to load, which is much slower than usual. This is affecting my productivity as I use the dashboard multiple times throughout the day.',
                'priority' => 'medium',
                'category' => 'technical',
                'status' => 'open',
            ],
            [
                'subject' => 'Bug: Search function returns no results',
                'description' => 'When I search for any term in the search box, it always returns "No results found" even for terms that I know exist in the system. This started happening after the last update. The search function was working perfectly before.',
                'priority' => 'high',
                'category' => 'bug_report',
                'status' => 'resolved',
            ],
            [
                'subject' => 'Request for API access',
                'description' => 'I\'m a developer and would like to integrate your service with my application. Could you provide information about API access, documentation, and any associated costs? I need to retrieve user data and create new entries programmatically.',
                'priority' => 'low',
                'category' => 'feature_request',
                'status' => 'pending',
            ],
            [
                'subject' => 'Data export feature not working',
                'description' => 'I tried to export my data using the export feature, but the download never starts and I get an error message. I need to export my data for backup purposes. This is urgent as I need the data for an important presentation next week.',
                'priority' => 'urgent',
                'category' => 'technical',
                'status' => 'in_progress',
            ],
            [
                'subject' => 'Mobile app layout issues on tablet',
                'description' => 'The mobile app layout is broken when used on a tablet device. The buttons are overlapping and some text is cut off. The app works fine on phones but needs to be optimized for tablet screens.',
                'priority' => 'medium',
                'category' => 'bug_report',
                'status' => 'open',
            ],
            [
                'subject' => 'Request for bulk operations',
                'description' => 'It would be very helpful to have bulk operations available, such as selecting multiple items and deleting them at once, or applying changes to multiple records simultaneously. This would save a lot of time for power users.',
                'priority' => 'low',
                'category' => 'feature_request',
                'status' => 'closed',
            ],
            [
                'subject' => 'Account locked due to suspicious activity',
                'description' => 'My account has been locked due to "suspicious activity" but I haven\'t done anything unusual. I was just using the service normally when I was suddenly logged out and can\'t log back in. Please help me regain access.',
                'priority' => 'urgent',
                'category' => 'technical',
                'status' => 'resolved',
            ],
        ];

        $createdTickets = [];

        // Create support tickets
        foreach ($ticketData as $index => $data) {
            $user = $users->random();
            $assignedAdmin = $admins->random();

            // Simulate different creation times (spread over last 30 days)
            $createdAt = now()->subDays(rand(1, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $ticket = SupportTicket::create([
                'user_id' => $user->id,
                'subject' => $data['subject'],
                'description' => $data['description'],
                'priority' => $data['priority'],
                'status' => $data['status'],
                'category' => $data['category'],
                'assigned_to' => $assignedAdmin->id,
                'admin_notes' => $this->getAdminNotes($data['status']),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $createdTickets[] = $ticket;

            // Set resolved_at and closed_at for resolved/closed tickets
            if (in_array($data['status'], ['resolved', 'closed'])) {
                $resolvedAt = $createdAt->copy()->addDays(rand(1, 7))->addHours(rand(1, 23));
                $ticket->update([
                    'resolved_at' => $resolvedAt,
                    'updated_at' => $resolvedAt,
                ]);

                if ($data['status'] === 'closed') {
                    $closedAt = $resolvedAt->copy()->addDays(rand(1, 3));
                    $ticket->update([
                        'closed_at' => $closedAt,
                        'updated_at' => $closedAt,
                    ]);
                }
            }

            $this->command->info("Created ticket: {$ticket->ticket_number} - {$ticket->subject}");
        }

        // Create replies for some tickets
        $this->createReplies($createdTickets, $admins);

        $this->command->info('Support tickets seeded successfully!');
        $this->command->info('Created ' . count($createdTickets) . ' support tickets with replies.');
    }

    /**
     * Create replies for tickets
     */
    private function createReplies(array $tickets, $admins): void
    {
        $this->command->info('Creating ticket replies...');

        $replyTemplates = [
            'admin' => [
                'Thank you for contacting us. I have received your ticket and will investigate this issue promptly.',
                'I understand your concern. Let me look into this matter and get back to you with a solution.',
                'Thank you for bringing this to our attention. I am currently working on resolving this issue.',
                'I have reviewed your request and will implement the necessary changes. Please allow 2-3 business days for completion.',
                'I have resolved this issue. Please try the suggested solution and let me know if you need any further assistance.',
                'This issue has been fixed in our latest update. Please update your application and try again.',
            ],
            'user' => [
                'Thank you for your response. I will try the suggested solution and let you know if it works.',
                'I appreciate your help. The issue seems to be resolved now. Thank you!',
                'Could you please provide more details about this? I\'m still experiencing the same problem.',
                'Is there an estimated timeline for when this will be fixed? This is affecting my work.',
                'I have tried the suggested solution but it didn\'t work. Do you have any other recommendations?',
                'Thank you for the update. I will wait for the next release to test this fix.',
            ]
        ];

        foreach ($tickets as $ticket) {
            // Create 1-4 replies per ticket
            $replyCount = rand(1, 4);
            $lastReplyTime = $ticket->created_at;

            for ($i = 0; $i < $replyCount; $i++) {
                // Determine if this is an admin or user reply
                $isAdminReply = ($i % 2 === 0) || ($i === $replyCount - 1 && $ticket->status !== 'open');
                
                if ($isAdminReply) {
                    $admin = $admins->random();
                    $message = $replyTemplates['admin'][array_rand($replyTemplates['admin'])];
                    $isInternal = rand(0, 1) === 1; // 50% chance of being internal
                    
                    $reply = SupportTicketReply::create([
                        'ticket_id' => $ticket->id,
                        'admin_id' => $admin->id,
                        'message' => $message,
                        'is_internal' => $isInternal,
                        'created_at' => $lastReplyTime->copy()->addHours(rand(1, 48)),
                        'updated_at' => $lastReplyTime->copy()->addHours(rand(1, 48)),
                    ]);
                } else {
                    $message = $replyTemplates['user'][array_rand($replyTemplates['user'])];
                    
                    $reply = SupportTicketReply::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $ticket->user_id,
                        'message' => $message,
                        'is_internal' => false,
                        'created_at' => $lastReplyTime->copy()->addHours(rand(1, 48)),
                        'updated_at' => $lastReplyTime->copy()->addHours(rand(1, 48)),
                    ]);
                }

                $lastReplyTime = $reply->created_at;
            }

            // Update ticket's updated_at to the last reply time
            $ticket->update(['updated_at' => $lastReplyTime]);
        }

        $this->command->info('Created replies for all tickets.');
    }

    /**
     * Get admin notes based on ticket status
     */
    private function getAdminNotes(string $status): ?string
    {
        $notes = [
            'open' => 'Ticket assigned to support team. Initial investigation in progress.',
            'in_progress' => 'Working on resolution. Customer has been contacted for additional information.',
            'pending' => 'Waiting for customer response or external vendor resolution.',
            'resolved' => 'Issue has been resolved. Solution implemented and tested successfully.',
            'closed' => 'Ticket closed after successful resolution and customer confirmation.',
        ];

        return $notes[$status] ?? null;
    }
}




