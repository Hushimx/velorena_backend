<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Cancelled - Qaads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .cancel-animation {
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .cancel-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card cancel-card cancel-animation">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-times-circle text-warning" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="text-warning mb-3">Payment Cancelled</h2>
                        <p class="text-muted mb-4">
                            Your payment has been cancelled. No charges have been made to your account.
                        </p>
                        
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> What Happened?</h6>
                            <ul class="list-unstyled mb-0 text-start">
                                <li><i class="fas fa-info-circle text-info"></i> You cancelled the payment process</li>
                                <li><i class="fas fa-info-circle text-info"></i> No money has been charged</li>
                                <li><i class="fas fa-info-circle text-info"></i> Your order is still pending</li>
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
                        <p class="mb-3">If you're having trouble with payments, here are some common solutions:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-credit-card"></i> Payment Issues</h6>
                                <ul class="list-unstyled">
                                    <li>• Check your card details</li>
                                    <li>• Ensure sufficient funds</li>
                                    <li>• Try a different card</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-headset"></i> Contact Support</h6>
                                <ul class="list-unstyled">
                                    <li>• Email: support@Qaads.com</li>
                                    <li>• Phone: +965 1234 5678</li>
                                    <li>• Live chat available</li>
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
