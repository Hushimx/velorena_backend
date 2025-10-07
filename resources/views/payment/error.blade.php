<?php
$source = request()->get('source', 'web');
$testMode = request()->get('test_mode', 'false') === 'true';
$error = request()->get('error', 'Payment failed');

// For mobile apps, return JSON response
if ($source === 'mobile') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => $error,
        'test_mode' => $testMode,
        'timestamp' => now()->toISOString()
    ]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error - Qaads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .error-animation {
            animation: shake 0.6s ease-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .error-card {
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
        .error-details {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card error-card error-animation">
                    <div class="card-body text-center p-5">
                        <?php if ($testMode): ?>
                        <div class="test-mode-badge">
                            <i class="fas fa-flask"></i> TEST MODE - No Real Payment
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="text-danger mb-3">Payment Failed</h2>
                        <p class="text-muted mb-4">
                            Unfortunately, your payment could not be processed. Please try again or contact support.
                            <?php if ($testMode): ?>
                            <br><small class="text-info">This was a test payment - no real money was involved.</small>
                            <?php endif; ?>
                        </p>
                        
                        <div class="error-details">
                            <h6><i class="fas fa-info-circle"></i> Error Details</h6>
                            <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-lightbulb"></i> What You Can Do</h6>
                            <ul class="list-unstyled mb-0 text-start">
                                <li><i class="fas fa-check text-warning"></i> Check your card details</li>
                                <li><i class="fas fa-check text-warning"></i> Ensure sufficient funds</li>
                                <li><i class="fas fa-check text-warning"></i> Try a different payment method</li>
                                <li><i class="fas fa-check text-warning"></i> Contact your bank if issues persist</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                            <a href="/payment-test" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> Try Payment Again
                            </a>
                            <a href="/" class="btn btn-outline-secondary">
                                <i class="fas fa-home"></i> Back to Home
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-question-circle"></i> Need Help?</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">If you continue to experience payment issues, please contact our support team:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-headset"></i> Contact Support</h6>
                                <ul class="list-unstyled">
                                    <li>• Email: support@qaads.com</li>
                                    <li>• Phone: +966 11 234 5678</li>
                                    <li>• Live chat available</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-credit-card"></i> Payment Methods</h6>
                                <ul class="list-unstyled">
                                    <li>• Visa, Mastercard, Amex</li>
                                    <li>• Mada (Saudi Arabia)</li>
                                    <li>• Apple Pay, Google Pay</li>
                                </ul>
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
