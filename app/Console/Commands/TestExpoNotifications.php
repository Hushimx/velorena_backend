<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\ExpoPushNotification;
use App\Services\ExpoPushService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestExpoNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expo:test 
                            {--user-id= : User ID to send test notification to}
                            {--token= : Expo push token to test with}
                            {--direct : Test Expo service directly}
                            {--status : Check Expo service status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Expo push notifications system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔔 Expo Push Notifications Test');
        $this->line('================================');

        // Check Expo service status
        if ($this->option('status')) {
            $this->testExpoServiceStatus();
            return;
        }

        // Test Expo service directly
        if ($this->option('direct')) {
            $this->testDirectExpoService();
            return;
        }

        // Test with specific token
        if ($token = $this->option('token')) {
            $this->testWithToken($token);
            return;
        }

        // Test with user
        if ($userId = $this->option('user-id')) {
            $this->testWithUser($userId);
            return;
        }

        // Run all tests
        $this->runAllTests();
    }

    /**
     * Test Expo service status
     */
    private function testExpoServiceStatus(): void
    {
        $this->info('📡 Checking Expo service status...');
        
        $expoService = app(ExpoPushService::class);
        $isAvailable = $expoService->isServiceAvailable();
        
        if ($isAvailable) {
            $this->info('✅ Expo push service is available');
        } else {
            $this->error('❌ Expo push service is not available');
        }
    }

    /**
     * Test Expo service directly
     */
    private function testDirectExpoService(): void
    {
        $this->info('🚀 Testing Expo service directly...');
        
        $token = $this->ask('Enter Expo push token (or press Enter to use test token)') 
            ?: 'ExponentPushToken[test-token-12345]';
        
        $expoService = app(ExpoPushService::class);
        
        $notification = $expoService->createNotification(
            'Direct Test',
            'This is a direct test from Laravel command',
            ['type' => 'direct_test', 'timestamp' => now()->toISOString()]
        );
        
        $result = $expoService->sendToDevice($token, $notification);
        
        if ($result['success']) {
            $this->info('✅ Direct test notification sent successfully');
            $this->line('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
        } else {
            $this->error('❌ Direct test notification failed');
            $this->error('Error: ' . $result['message']);
        }
    }

    /**
     * Test with specific token
     */
    private function testWithToken(string $token): void
    {
        $this->info('🔔 Testing with specific token...');
        
        $expoService = app(ExpoPushService::class);
        
        $notification = $expoService->createNotification(
            'Token Test',
            'This notification was sent to a specific token',
            ['type' => 'token_test', 'timestamp' => now()->toISOString()]
        );
        
        $result = $expoService->sendToDevice($token, $notification);
        
        if ($result['success']) {
            $this->info('✅ Token test notification sent successfully');
            $this->line('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
        } else {
            $this->error('❌ Token test notification failed');
            $this->error('Error: ' . $result['message']);
        }
    }

    /**
     * Test with specific user
     */
    private function testWithUser(int $userId): void
    {
        $this->info("🔔 Testing with user ID: {$userId}");
        
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ User with ID {$userId} not found");
            return;
        }
        
        if (!method_exists($user, 'expoPushTokens')) {
            $this->error('❌ User model does not support Expo push tokens');
            return;
        }
        
        $tokenCount = $user->expoPushTokens()->active()->count();
        
        if ($tokenCount === 0) {
            $this->warn("⚠️  User has no active Expo push tokens");
            $this->line('Total tokens: ' . $user->expoPushTokens()->count());
            return;
        }
        
        $this->info("📱 User has {$tokenCount} active token(s)");
        
        // Send notification
        $user->notify(new ExpoPushNotification(
            title: 'User Test Notification',
            body: "This is a test notification for user {$user->full_name}",
            data: ['type' => 'user_test', 'user_id' => $userId, 'timestamp' => now()->toISOString()]
        ));
        
        $this->info('✅ Test notification sent to user');
    }

    /**
     * Run all tests
     */
    private function runAllTests(): void
    {
        $this->info('🧪 Running comprehensive tests...');
        
        // Test 1: Service status
        $this->line('');
        $this->testExpoServiceStatus();
        
        // Test 2: Database connection
        $this->line('');
        $this->info('🗄️  Testing database connection...');
        try {
            $tokenCount = DB::table('expo_push_tokens')->count();
            $this->info("✅ Database connected. Total tokens: {$tokenCount}");
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
        }
        
        // Test 3: User with tokens
        $this->line('');
        $this->info('👤 Testing users with tokens...');
        $usersWithTokens = User::whereHas('expoPushTokens')->count();
        $this->info("Users with Expo tokens: {$usersWithTokens}");
        
        // Test 4: Recent tokens
        $this->line('');
        $this->info('📊 Recent token activity...');
        $recentTokens = DB::table('expo_push_tokens')
            ->where('created_at', '>=', now()->subDays(7))
            ->count();
        $this->info("Tokens created in last 7 days: {$recentTokens}");
        
        // Test 5: Active tokens
        $activeTokens = DB::table('expo_push_tokens')
            ->where('is_active', true)
            ->count();
        $this->info("Active tokens: {$activeTokens}");
        
        $this->line('');
        $this->info('🎯 Test Summary:');
        $this->line('• Use --status to check Expo service');
        $this->line('• Use --direct to test Expo service directly');
        $this->line('• Use --token=TOKEN to test specific token');
        $this->line('• Use --user-id=ID to test specific user');
    }
}
