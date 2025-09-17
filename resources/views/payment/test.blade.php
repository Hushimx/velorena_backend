<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tap Payment Test - Qaads</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .payment-card {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        .payment-card:hover {
            border-color: #007bff;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.1);
        }
        .test-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .loading {
            display: none;
        }
        .result {
            display: none;
        }
        .test-info {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center mb-5">
                    <h1 class="display-4 text-primary">
                        <i class="fas fa-credit-card"></i> Tap Payment Test
                    </h1>
                    <p class="lead text-muted">Test Tap Payment Gateway Integration</p>
                </div>

                <!-- Test Information -->
                <div class="alert test-info mb-4">
                    <h5><i class="fas fa-info-circle"></i> Test Information</h5>
                    <p class="mb-0">This is a test environment. Use the test card numbers below to simulate payments. No real money will be charged.</p>
                </div>

                <!-- Test Cards -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5><i class="fas fa-credit-card"></i> Test Card Numbers</h5>
                        <div class="row" id="testCards">
                            <!-- Test cards will be loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="card payment-card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-shopping-cart"></i> Create Test Payment</h4>
                    </div>
                    <div class="card-body">
                        <form id="paymentForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Amount (KWD)</label>
                                    <input type="number" class="form-control" id="amount" name="amount" 
                                           value="10.00" min="0.01" step="0.01" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="currency" class="form-label">Currency</label>
                                    <select class="form-select" id="currency" name="currency" required>
                                        <option value="KWD">KWD - Kuwaiti Dinar</option>
                                        <option value="SAR">SAR - Saudi Riyal</option>
                                        <option value="AED">AED - UAE Dirham</option>
                                        <option value="BHD">BHD - Bahraini Dinar</option>
                                        <option value="EGP">EGP - Egyptian Pound</option>
                                        <option value="USD">USD - US Dollar</option>
                                        <option value="EUR">EUR - Euro</option>
                                        <option value="GBP">GBP - British Pound</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" 
                                           value="John" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" 
                                           value="Doe" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="test@example.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="+96512345678" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" class="form-control" id="description" name="description" 
                                       value="Test Payment for Qaads" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-credit-card"></i> Create Payment
                                </button>
                            </div>
                        </form>

                        <!-- Loading -->
                        <div class="loading text-center mt-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Creating payment...</p>
                        </div>

                        <!-- Result -->
                        <div class="result mt-4">
                            <div class="alert alert-success" id="successAlert" style="display: none;">
                                <h5><i class="fas fa-check-circle"></i> Payment Created Successfully!</h5>
                                <p id="successMessage"></p>
                                <a href="#" id="paymentUrl" class="btn btn-success" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> Complete Payment
                                </a>
                            </div>
                            <div class="alert alert-danger" id="errorAlert" style="display: none;">
                                <h5><i class="fas fa-exclamation-triangle"></i> Payment Failed</h5>
                                <p id="errorMessage"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status Check -->
                <div class="card payment-card mt-4" id="statusCard" style="display: none;">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-search"></i> Check Payment Status</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="chargeId" placeholder="Enter Charge ID">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-info w-100" onclick="checkPaymentStatus()">
                                    <i class="fas fa-search"></i> Check Status
                                </button>
                            </div>
                        </div>
                        <div id="statusResult" class="mt-3"></div>
                    </div>
                </div>

                <!-- API Documentation -->
                <div class="card payment-card mt-4">
                    <div class="card-header bg-dark text-white">
                        <h4 class="mb-0"><i class="fas fa-book"></i> API Endpoints</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Test Payment Endpoints (No Auth):</h6>
                                <ul class="list-unstyled">
                                    <li><code>POST /api/test/payments/create-charge</code></li>
                                    <li><code>GET /api/test/payments/status</code></li>
                                    <li><code>GET /api/test/payments/test-cards</code></li>
                                </ul>
                                <h6>Production Endpoints (Auth Required):</h6>
                                <ul class="list-unstyled">
                                    <li><code>POST /api/payments/create-charge</code></li>
                                    <li><code>GET /api/payments/status</code></li>
                                    <li><code>POST /api/payments/refund</code></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Webhook Endpoint:</h6>
                                <ul class="list-unstyled">
                                    <li><code>POST /api/webhooks/tap</code></li>
                                </ul>
                                <h6>Success/Cancel URLs:</h6>
                                <ul class="list-unstyled">
                                    <li><code>GET /payment/success</code></li>
                                    <li><code>GET /payment/cancel</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load test cards on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTestCards();
        });

        // Load test cards
        async function loadTestCards() {
            try {
                const response = await fetch('/api/test/payments/test-cards');
                const data = await response.json();
                
                if (data.success) {
                    const testCardsContainer = document.getElementById('testCards');
                    const cards = data.data;
                    
                    Object.keys(cards).forEach(cardType => {
                        const card = cards[cardType];
                        const cardHtml = `
                            <div class="col-md-4 mb-3">
                                <div class="card test-card">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">${cardType.toUpperCase()}</h6>
                                        <p class="card-text small">
                                            <strong>Number:</strong> ${card.number}<br>
                                            <strong>CVV:</strong> ${card.cvv}<br>
                                            <strong>Exp:</strong> ${card.exp_month}/${card.exp_year}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        `;
                        testCardsContainer.innerHTML += cardHtml;
                    });
                }
            } catch (error) {
                console.error('Error loading test cards:', error);
            }
        }

        // Handle form submission
        document.getElementById('paymentForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const paymentData = {
                order_id: 1, // Test order ID
                amount: parseFloat(formData.get('amount')),
                currency: formData.get('currency'),
                customer: {
                    first_name: formData.get('firstName'),
                    last_name: formData.get('lastName'),
                    email: formData.get('email'),
                    phone: formData.get('phone')
                },
                redirect_url: window.location.origin + '/payment/success',
                post_url: window.location.origin + '/api/webhooks/tap'
            };

            // Show loading
            document.querySelector('.loading').style.display = 'block';
            document.querySelector('.result').style.display = 'none';
            document.getElementById('statusCard').style.display = 'none';

            try {
                const response = await fetch('/api/test/payments/create-charge', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(paymentData)
                });

                const result = await response.json();
                
                // Hide loading
                document.querySelector('.loading').style.display = 'none';
                document.querySelector('.result').style.display = 'block';

                if (result.success) {
                    // Show success
                    document.getElementById('successAlert').style.display = 'block';
                    document.getElementById('errorAlert').style.display = 'none';
                    document.getElementById('successMessage').textContent = 
                        `Payment created successfully! Charge ID: ${result.data.charge_id}`;
                    document.getElementById('paymentUrl').href = result.data.payment_url;
                    
                    // Show status check card
                    document.getElementById('statusCard').style.display = 'block';
                    document.getElementById('chargeId').value = result.data.charge_id;
                } else {
                    // Show error
                    document.getElementById('successAlert').style.display = 'none';
                    document.getElementById('errorAlert').style.display = 'block';
                    document.getElementById('errorMessage').textContent = result.message || 'Payment creation failed';
                }
            } catch (error) {
                // Hide loading
                document.querySelector('.loading').style.display = 'none';
                document.querySelector('.result').style.display = 'block';
                
                // Show error
                document.getElementById('successAlert').style.display = 'none';
                document.getElementById('errorAlert').style.display = 'block';
                document.getElementById('errorMessage').textContent = 'Network error: ' + error.message;
            }
        });

        // Check payment status
        async function checkPaymentStatus() {
            const chargeId = document.getElementById('chargeId').value;
            if (!chargeId) {
                alert('Please enter a charge ID');
                return;
            }

            const statusResult = document.getElementById('statusResult');
            statusResult.innerHTML = '<div class="text-center"><div class="spinner-border spinner-border-sm"></div> Checking...</div>';

            try {
                const response = await fetch(`/api/test/payments/status?charge_id=${chargeId}`);
                const result = await response.json();

                if (result.success) {
                    const status = result.data.status;
                    const statusClass = status === 'CAPTURED' ? 'success' : 
                                      status === 'FAILED' ? 'danger' : 'warning';
                    
                    statusResult.innerHTML = `
                        <div class="alert alert-${statusClass}">
                            <h6><i class="fas fa-info-circle"></i> Payment Status</h6>
                            <p><strong>Status:</strong> ${status}</p>
                            <p><strong>Amount:</strong> ${result.data.amount} ${result.data.currency}</p>
                            <p><strong>Created:</strong> ${new Date(result.data.created * 1000).toLocaleString()}</p>
                        </div>
                    `;
                } else {
                    statusResult.innerHTML = `
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-triangle"></i> Error</h6>
                            <p>${result.message}</p>
                        </div>
                    `;
                }
            } catch (error) {
                statusResult.innerHTML = `
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle"></i> Error</h6>
                        <p>Network error: ${error.message}</p>
                    </div>
                `;
            }
        }
    </script>
</body>
</html>
