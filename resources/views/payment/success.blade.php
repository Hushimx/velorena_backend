<?php
$source = request()->get('source', 'web');
$testMode = request()->get('test_mode', 'false') === 'true';
$chargeId = request()->get('charge_id') ?? request()->get('tap_id') ?? request()->get('id');

// For mobile apps, return JSON response
if ($source === 'mobile') {
    header('Content-Type: application/json');
    
    // Check actual payment status if charge_id is provided
    $paymentStatus = 'unknown';
    $isSuccess = false;
    $message = 'Payment status unknown';
    
    if ($chargeId) {
        try {
            // Get payment record from database
            $payment = \App\Models\Payment::where('charge_id', $chargeId)->first();
            
            if ($payment) {
                $paymentStatus = $payment->status;
                
                // Check TAP status from gateway response
                $gatewayResponse = $payment->gateway_response;
                if ($gatewayResponse && isset($gatewayResponse['status'])) {
                    $tapStatus = $gatewayResponse['status'];
                    
                    // Only consider it successful if TAP says CAPTURED
                    if ($tapStatus === 'CAPTURED') {
                        $isSuccess = true;
                        $message = 'Payment completed successfully';
                    } elseif ($tapStatus === 'DECLINED') {
                        $isSuccess = false;
                        $message = 'Payment was declined by the bank';
                    } elseif ($tapStatus === 'FAILED') {
                        $isSuccess = false;
                        $message = 'Payment failed';
                    } else {
                        $isSuccess = false;
                        $message = 'Payment status: ' . $tapStatus;
                    }
                } else {
                    // Fallback to local status
                    if ($paymentStatus === 'completed') {
                        $isSuccess = true;
                        $message = 'Payment completed successfully';
                    } else {
                        $isSuccess = false;
                        $message = 'Payment status: ' . $paymentStatus;
                    }
                }
            } else {
                $isSuccess = false;
                $message = 'Payment record not found';
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Payment success page error', [
                'error' => $e->getMessage(),
                'charge_id' => $chargeId
            ]);
            $isSuccess = false;
            $message = 'Error checking payment status';
        }
    }
    
    echo json_encode([
        'success' => $isSuccess,
        'message' => $message,
        'payment_status' => $paymentStatus,
        'test_mode' => $testMode,
        'timestamp' => now()->toISOString(),
        'charge_id' => $chargeId
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - Qaads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .success-animation {
            animation: bounceIn 0.6s ease-out;
        }
        @keyframes bounceIn {
            0% { transform: scale(0.3); opacity: 0; }
            50% { transform: scale(1.05); }
            70% { transform: scale(0.9); }
            100% { transform: scale(1); opacity: 1; }
        }
        .success-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .test-mode-badge {
            background: linear-gradient(45deg, #ff6b6b, #feca57);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card success-card success-animation">
                    <div class="card-body text-center p-5">
                        <?php if ($testMode): ?>
                        <div class="test-mode-badge">
                            <i class="fas fa-flask"></i> TEST MODE - No Real Payment
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="text-success mb-3">Payment Successful!</h2>
                        <p class="text-muted mb-4">
                            Thank you for your payment. Your transaction has been completed successfully.
                            <?php if ($testMode): ?>
                            <br><small class="text-warning">This was a test payment - no real money was charged.</small>
                            <?php endif; ?>
                        </p>
                        
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle"></i> What's Next?</h6>
                            <ul class="list-unstyled mb-0 text-start">
                                <li><i class="fas fa-check text-success"></i> You will receive a confirmation email</li>
                                <li><i class="fas fa-check text-success"></i> Your order is being processed</li>
                                <li><i class="fas fa-check text-success"></i> You can track your order in your dashboard</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="/" class="btn btn-primary">
                                <i class="fas fa-home"></i> Back to Home
                            </a>
                            <a href="/orders" class="btn btn-outline-primary">
                                <i class="fas fa-list"></i> View Orders
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-question-circle"></i> Need Help?</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">If you have any questions about your payment or order, please contact us:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="fas fa-envelope"></i> <strong>Email:</strong> support@Qaads.com</p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="fas fa-phone"></i> <strong>Phone:</strong> +965 1234 5678</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
