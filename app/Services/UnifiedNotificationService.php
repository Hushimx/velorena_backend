<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Appointment;
use Illuminate\Support\Facades\Log;

class UnifiedNotificationService
{
    protected ExpoPushService $expoPushService;
    protected WhatsAppService $whatsAppService;

    public function __construct(
        ExpoPushService $expoPushService,
        WhatsAppService $whatsAppService
    ) {
        $this->expoPushService = $expoPushService;
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Send order status change notification
     */
    public function sendOrderStatusNotification(Order $order, string $oldStatus, string $newStatus): void
    {
        if (!$order->user) {
            Log::warning('Order has no user, skipping notification', ['order_id' => $order->id]);
            return;
        }

        $user = $order->user;
        
        // Prepare notification data
        $notificationData = $this->getOrderNotificationData($order, $oldStatus, $newStatus);
        
        // Send Expo Push Notification
        $this->sendExpoPushNotification(
            $user,
            $notificationData['title'],
            $notificationData['body'],
            [
                'type' => 'order',
                'orderId' => $order->id,
                'status' => $newStatus,
                'screen' => 'orders/' . $order->id,
            ]
        );
        
        // Send WhatsApp Message
        if ($this->shouldSendWhatsApp($user)) {
            $this->sendWhatsAppMessage(
                $user->phone,
                $notificationData['whatsappMessage']
            );
        }
    }

    /**
     * Send appointment status change notification
     */
    public function sendAppointmentStatusNotification(
        Appointment $appointment,
        string $oldStatus,
        string $newStatus
    ): void {
        if (!$appointment->user) {
            Log::warning('Appointment has no user, skipping notification', ['appointment_id' => $appointment->id]);
            return;
        }

        $user = $appointment->user;
        
        // Prepare notification data
        $notificationData = $this->getAppointmentNotificationData($appointment, $oldStatus, $newStatus);
        
        // Send Expo Push Notification
        $this->sendExpoPushNotification(
            $user,
            $notificationData['title'],
            $notificationData['body'],
            [
                'type' => 'appointment',
                'appointmentId' => $appointment->id,
                'status' => $newStatus,
                'screen' => 'appointment/' . $appointment->id,
            ]
        );
        
        // Send WhatsApp Message
        if ($this->shouldSendWhatsApp($user)) {
            $this->sendWhatsAppMessage(
                $user->phone,
                $notificationData['whatsappMessage']
            );
        }
    }

    /**
     * Get order notification data based on status
     */
    protected function getOrderNotificationData(Order $order, string $oldStatus, string $newStatus): array
    {
        $orderNumber = $order->order_number;
        
        $data = match ($newStatus) {
            'confirmed' => [
                'title' => 'تم تأكيد طلبك',
                'body' => "تم تأكيد طلبك رقم {$orderNumber}",
                'whatsappMessage' => "✅ تم تأكيد طلبك!\n\n"
                    . "رقم الطلب: {$orderNumber}\n"
                    . "المبلغ الإجمالي: " . number_format($order->total, 2) . " ريال\n\n"
                    . "سيتم معالجة طلبك قريباً.\n"
                    . "شكراً لثقتك بنا! 🎨"
            ],
            'processing' => [
                'title' => 'جاري معالجة طلبك',
                'body' => "طلبك رقم {$orderNumber} قيد المعالجة الآن",
                'whatsappMessage' => "⚙️ جاري معالجة طلبك!\n\n"
                    . "رقم الطلب: {$orderNumber}\n"
                    . "الحالة: قيد المعالجة\n\n"
                    . "سنقوم بإعلامك عند شحن الطلب.\n"
                    . "شكراً لصبرك! 📦"
            ],
            'shipped' => [
                'title' => 'تم شحن طلبك',
                'body' => "طلبك رقم {$orderNumber} تم شحنه",
                'whatsappMessage' => "🚚 تم شحن طلبك!\n\n"
                    . "رقم الطلب: {$orderNumber}\n"
                    . ($order->tracking_number ? "رقم التتبع: {$order->tracking_number}\n" : "")
                    . ($order->courier_company ? "شركة الشحن: {$order->courier_company}\n" : "")
                    . "\nسيصلك طلبك قريباً! 📦✨"
            ],
            'delivered' => [
                'title' => 'تم توصيل طلبك',
                'body' => "طلبك رقم {$orderNumber} تم توصيله بنجاح",
                'whatsappMessage' => "🎉 تم توصيل طلبك بنجاح!\n\n"
                    . "رقم الطلب: {$orderNumber}\n\n"
                    . "نأمل أن تكون راضياً عن خدمتنا.\n"
                    . "شكراً لاختيارك لنا! ❤️"
            ],
            'cancelled' => [
                'title' => 'تم إلغاء طلبك',
                'body' => "طلبك رقم {$orderNumber} تم إلغاؤه",
                'whatsappMessage' => "❌ تم إلغاء طلبك\n\n"
                    . "رقم الطلب: {$orderNumber}\n\n"
                    . "إذا كان لديك أي استفسار، يرجى التواصل معنا."
            ],
            default => [
                'title' => 'تحديث على طلبك',
                'body' => "تم تحديث حالة طلبك رقم {$orderNumber}",
                'whatsappMessage' => "📢 تحديث على طلبك\n\n"
                    . "رقم الطلب: {$orderNumber}\n"
                    . "الحالة الجديدة: {$newStatus}"
            ]
        };

        return $data;
    }

    /**
     * Get appointment notification data based on status
     */
    protected function getAppointmentNotificationData(
        Appointment $appointment,
        string $oldStatus,
        string $newStatus
    ): array {
        $appointmentDate = $appointment->appointment_date->format('Y-m-d');
        $appointmentTime = $appointment->appointment_time->format('H:i');
        $designerName = $appointment->designer ? $appointment->designer->name : 'المصمم';
        
        $data = match ($newStatus) {
            'accepted' => [
                'title' => 'تم قبول موعدك',
                'body' => "تم قبول موعدك مع {$designerName} في {$appointmentDate}",
                'whatsappMessage' => "✅ تم قبول موعدك!\n\n"
                    . "المصمم: {$designerName}\n"
                    . "📅 التاريخ: {$appointmentDate}\n"
                    . "⏰ الوقت: {$appointmentTime}\n"
                    . "⏱ المدة: {$appointment->duration_minutes} دقيقة\n\n"
                    . "سيتم إرسال رابط الاجتماع عند بدء الموعد.\n\n"
                    . "نتطلع لرؤيتك! 🎨"
            ],
            'started' => [
                'title' => 'بدأ موعدك',
                'body' => "موعدك مع {$designerName} بدأ الآن",
                'whatsappMessage' => "🎥 تم بدء اجتماعك مع المصمم\n\n"
                    . "يمكنك الانضمام للاجتماع من خلال الرابط التالي:\n"
                    . ($appointment->getMeetingUrl() ?: 'سيتم إرسال الرابط قريباً') . "\n\n"
                    . "📅 تاريخ الموعد: {$appointmentDate}\n"
                    . "⏰ وقت الموعد: {$appointmentTime}\n\n"
                    . "نحن في انتظارك! 😊"
            ],
            'rejected' => [
                'title' => 'تم رفض موعدك',
                'body' => "موعدك في {$appointmentDate} تم رفضه",
                'whatsappMessage' => "❌ عذراً، تم رفض موعدك\n\n"
                    . "📅 التاريخ: {$appointmentDate}\n"
                    . "⏰ الوقت: {$appointmentTime}\n\n"
                    . ($appointment->designer_notes ? "السبب: {$appointment->designer_notes}\n\n" : "")
                    . "يرجى حجز موعد آخر أو التواصل معنا."
            ],
            'cancelled' => [
                'title' => 'تم إلغاء موعدك',
                'body' => "موعدك في {$appointmentDate} تم إلغاؤه",
                'whatsappMessage' => "❌ تم إلغاء موعدك\n\n"
                    . "📅 التاريخ: {$appointmentDate}\n"
                    . "⏰ الوقت: {$appointmentTime}\n\n"
                    . ($appointment->cancellation_reason ? "السبب: {$appointment->cancellation_reason}\n\n" : "")
                    . "يمكنك حجز موعد جديد في أي وقت."
            ],
            'completed' => [
                'title' => 'تم إنهاء موعدك',
                'body' => "تم إنهاء موعدك مع {$designerName} بنجاح",
                'whatsappMessage' => "✅ تم إنهاء موعدك بنجاح!\n\n"
                    . "المصمم: {$designerName}\n"
                    . "📅 التاريخ: {$appointmentDate}\n\n"
                    . "نشكرك على ثقتك بنا! 🎨"
            ],
            default => [
                'title' => 'تحديث على موعدك',
                'body' => "تم تحديث حالة موعدك في {$appointmentDate}",
                'whatsappMessage' => "📢 تحديث على موعدك\n\n"
                    . "📅 التاريخ: {$appointmentDate}\n"
                    . "الحالة الجديدة: {$newStatus}"
            ]
        };

        return $data;
    }

    /**
     * Send Expo Push Notification to user
     */
    protected function sendExpoPushNotification(
        User $user,
        string $title,
        string $body,
        array $data = []
    ): void {
        try {
            // Get user's active push tokens
            $tokens = $user->activeExpoPushTokens()->pluck('token')->toArray();
            
            if (empty($tokens)) {
                Log::info('No active push tokens for user', ['user_id' => $user->id]);
                return;
            }

            // Prepare notification payload
            $notification = $this->expoPushService->createNotification(
                $title,
                $body,
                $data,
                'default',
                null
            );

            // Send notification
            $result = $this->expoPushService->sendToDevices($tokens, $notification);

            if ($result['success']) {
                Log::info('Expo push notification sent successfully', [
                    'user_id' => $user->id,
                    'tokens_count' => count($tokens),
                    'sent' => $result['data']['total_sent'] ?? 0,
                    'failed' => $result['data']['total_failed'] ?? 0,
                ]);
            } else {
                Log::error('Expo push notification failed', [
                    'user_id' => $user->id,
                    'error' => $result['message'] ?? 'Unknown error'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Exception sending Expo push notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Send WhatsApp message
     */
    protected function sendWhatsAppMessage(string $phoneNumber, string $message): void
    {
        try {
            // Check if WhatsApp service is configured
            if (!$this->whatsAppService->isConfigured()) {
                Log::info('WhatsApp service not configured, skipping message');
                return;
            }

            // Format phone number
            $formattedPhone = $this->whatsAppService->formatPhoneNumber($phoneNumber);

            // Send message
            $result = $this->whatsAppService->sendTextMessage($formattedPhone, $message);

            Log::info('WhatsApp message sent successfully', [
                'phone' => $formattedPhone,
                'result' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Exception sending WhatsApp message', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check if WhatsApp notification should be sent
     */
    protected function shouldSendWhatsApp(User $user): bool
    {
        // Check user's WhatsApp notification preference
        if (isset($user->whatsapp_notifications) && !$user->whatsapp_notifications) {
            return false;
        }

        // Check if user has a phone number
        if (empty($user->phone)) {
            return false;
        }

        return true;
    }
}

