<div class="booking-container">
    <form wire:submit.prevent="bookAppointment">
        <div class="booking-grid">
        <!-- Left Column - Form -->
        <div class="form-column">

            <!-- Date Selection -->
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-calendar"></i>
                    </div>
                    <div class="section-title">
                        <h3>اختر التاريخ</h3>
                        <span class="section-subtitle">اختر التاريخ المناسب لك</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <input type="date" 
                        wire:model.live="selectedDate"
                        min="{{ $this->minDate }}" 
                        max="{{ $this->maxDate }}" 
                        id="selectedDate"
                        class="form-input">
                    @error('selectedDate')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Time Slots Selection -->
            @if($selectedDate)
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    <div class="section-title">
                        <h3>الأوقات المتاحة</h3>
                        <span class="section-subtitle">اختر الوقت المناسب لك</span>
                    </div>
                    </div>
                    
                    <div class="time-slots">
                        @if(!empty($availableSlots))
                            <div class="slots-grid">
                                @foreach($availableSlots as $slot)
                                    <button type="button" 
                                        wire:click="selectTimeSlot('{{ $slot }}')"
                                        class="time-slot {{ $selectedTime === $slot ? 'selected' : '' }}">
                                        <span class="slot-time">{{ $slot }}</span>
                                        @if($selectedTime === $slot)
                                            <i class="fas fa-check slot-check"></i>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <div class="no-slots">
                                <div class="no-slots-icon">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <h4>لا توجد أوقات متاحة</h4>
                                <p>جميع الأوقات لهذا التاريخ محجوزة. يرجى اختيار تاريخ آخر.</p>
                            </div>
                        @endif
                        @error('selectedTime')
                            <div class="error-message">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Notes Section -->
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="section-title">
                        <h3>ملاحظات إضافية</h3>
                        <span class="section-subtitle">أي متطلبات خاصة (اختياري)</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <textarea wire:model="notes" 
                        rows="4" 
                        placeholder="أي متطلبات خاصة أو ملاحظات لموعدك..."
                        class="form-textarea"></textarea>
                    @error('notes')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Right Column - Summary & Actions -->
        <div class="summary-column">
            <!-- Appointment Summary -->
            @if ($selectedDate)
                <div class="summary-card">
                    <div class="summary-header">
                        <div class="summary-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3>ملخص الموعد</h3>
                    </div>
                    
                    <div class="summary-content">
                        <div class="summary-item">
                            <div class="summary-label">
                                <i class="fas fa-user"></i>
                                العميل
                            </div>
                            <div class="summary-value">{{ Auth::user()->name }}</div>
                        </div>
                        
                        <div class="summary-item">
                            <div class="summary-label">
                                <i class="fas fa-calendar"></i>
                                التاريخ
                            </div>
                            <div class="summary-value">{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</div>
                        </div>
                        
                        <div class="summary-item">
                            <div class="summary-label">
                                <i class="fas fa-clock"></i>
                                الوقت
                            </div>
                            <div class="summary-value">
                                @if($selectedTime)
                                    {{ \Carbon\Carbon::parse($selectedTime)->format('g:i A') }} - {{ \Carbon\Carbon::parse($selectedTime)->addMinutes(15)->format('g:i A') }}
                                @else
                                    اختر الوقت أولاً
                                @endif
                            </div>
                        </div>
                        
                        <div class="summary-item">
                            <div class="summary-label">
                                <i class="fas fa-stopwatch"></i>
                                المدة
                            </div>
                            <div class="summary-value">15 دقيقة</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Button -->
            @if ($selectedDate && $selectedTime && !$cartEmpty)
                <div class="action-card">
                    <button type="submit" 
                        wire:loading.attr="disabled" 
                        wire:target="bookAppointment"
                        class="submit-button">
                        <div wire:loading.remove wire:target="bookAppointment" class="button-content">
                            <i class="fas fa-calendar-check"></i>
                            <span>حجز الموعد</span>
                        </div>
                        <div wire:loading wire:target="bookAppointment" class="button-loading">
                            <div class="spinner"></div>
                            <span>جاري الحجز...</span>
                        </div>
                    </button>
                </div>
            @elseif($cartEmpty)
                <div class="empty-cart-card">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h4>سلة التسوق فارغة</h4>
                    <p>يرجى إضافة منتجات إلى سلة التسوق قبل حجز موعد.</p>
                    <a href="{{ route('user.products.index') }}" class="browse-button">
                        <i class="fas fa-shopping-bag"></i>
                        تصفح المنتجات
                    </a>
                </div>
            @endif

        </div>
    </form>
</div>

<style>
    /* Brand Colors */
    :root {
        --brand-yellow: #ffde9f;
        --brand-yellow-dark: #f5d182;
        --brand-brown: #2a1e1e;
        --brand-brown-light: #3a2e2e;
        --brand-yellow-light: #fff4e6;
        --brand-yellow-hover: #f0d4a0;
        --brand-brown-dark: #1a1414;
        --brand-brown-hover: #4a3e3e;
    }

    /* Container */
    .booking-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
        animation: containerSlideIn 0.8s ease-out 0.5s both;
    }

    @keyframes containerSlideIn {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Grid Layout */
    .booking-grid {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 2rem;
        align-items: start;
    }

    /* Form Column */
    .form-column {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Section Cards */
    .section-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(42, 30, 30, 0.08);
        border: 1px solid rgba(255, 222, 159, 0.2);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
        overflow: hidden;
        animation: cardSlideIn 0.6s ease-out both;
    }

    .section-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 222, 159, 0.1), transparent);
        transition: left 0.6s ease;
    }

    .section-card:hover::before {
        left: 100%;
    }

    .section-card:hover {
        box-shadow: 0 16px 64px rgba(42, 30, 30, 0.15);
        transform: translateY(-5px) scale(1.02);
        border-color: var(--brand-yellow);
    }

    .section-card:nth-child(1) { animation-delay: 0.1s; }
    .section-card:nth-child(2) { animation-delay: 0.2s; }
    .section-card:nth-child(3) { animation-delay: 0.3s; }
    .section-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes cardSlideIn {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Section Header */
    .section-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .section-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .section-icon i {
        font-size: 1.3rem;
        color: var(--brand-brown);
    }

    .section-title h3 {
        font-family: 'Cairo', cursive;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--brand-brown);
        margin: 0;
    }

    .section-subtitle {
        font-size: 0.9rem;
        color: var(--brand-brown-light);
        font-weight: 500;
    }

    .item-count {
        background: var(--brand-yellow);
        color: var(--brand-brown);
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Cart Items */
    .cart-items {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .cart-item {
        background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(255, 222, 159, 0.1) 100%);
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid var(--brand-yellow);
    }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .item-name {
        font-family: 'Cairo', cursive;
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--brand-brown);
        margin: 0;
    }

    .item-price {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--brand-brown-dark);
    }

    .item-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 8px;
    }

    .detail-label {
        font-size: 0.9rem;
        color: var(--brand-brown-light);
        font-weight: 500;
    }

    .detail-value {
        font-size: 0.9rem;
        color: var(--brand-brown);
        font-weight: 600;
    }

    /* Designs Section */
    .designs-section {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 222, 159, 0.3);
    }

    .designs-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--brand-brown);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .designs-title i {
        color: var(--brand-brown-dark);
    }

    .designs-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .design-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--brand-yellow);
        padding: 0.5rem 0.75rem;
        border-radius: 10px;
        border: 1px solid var(--brand-brown);
    }

    .design-thumbnail {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--brand-brown);
    }

    .design-placeholder {
        width: 30px;
        height: 30px;
        background: var(--brand-brown);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--brand-yellow);
        font-size: 0.8rem;
    }

    .design-title {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--brand-brown);
    }

    /* Item Notes */
    .item-notes {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 8px;
        margin-top: 1rem;
        font-size: 0.9rem;
        color: var(--brand-brown);
    }

    .item-notes i {
        color: var(--brand-brown-dark);
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 0;
    }

    .form-input,
    .form-textarea {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid var(--brand-yellow);
        border-radius: 12px;
        font-size: 1rem;
        font-family: 'Cairo', sans-serif;
        background: #ffffff;
        color: var(--brand-brown);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
    }

    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--brand-brown);
        box-shadow: 0 0 0 4px rgba(255, 222, 159, 0.3), 0 4px 15px rgba(42, 30, 30, 0.1);
        transform: translateY(-2px);
        background: linear-gradient(135deg, #ffffff 0%, var(--brand-yellow-light) 100%);
    }

    .form-input:hover,
    .form-textarea:hover {
        border-color: var(--brand-yellow-dark);
        box-shadow: 0 2px 8px rgba(42, 30, 30, 0.08);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* Time Slots */
    .slots-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
    }

    .time-slot {
        background: #ffffff;
        border: 2px solid var(--brand-yellow);
        border-radius: 12px;
        padding: 1rem 0.75rem;
        font-weight: 600;
        color: var(--brand-brown);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        overflow: hidden;
        animation: slotFadeIn 0.5s ease-out both;
    }

    .time-slot::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 222, 159, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .time-slot:hover::before {
        left: 100%;
    }

    .time-slot:hover {
        background: var(--brand-yellow);
        border-color: var(--brand-brown);
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 8px 25px rgba(42, 30, 30, 0.2);
    }

    .time-slot.selected {
        background: var(--brand-brown);
        border-color: var(--brand-brown-dark);
        color: var(--brand-yellow);
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 30px rgba(42, 30, 30, 0.3);
        animation: selectedPulse 0.6s ease-out;
    }

    .time-slot:nth-child(1) { animation-delay: 0.1s; }
    .time-slot:nth-child(2) { animation-delay: 0.15s; }
    .time-slot:nth-child(3) { animation-delay: 0.2s; }
    .time-slot:nth-child(4) { animation-delay: 0.25s; }
    .time-slot:nth-child(5) { animation-delay: 0.3s; }
    .time-slot:nth-child(6) { animation-delay: 0.35s; }

    @keyframes slotFadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes selectedPulse {
        0% {
            transform: translateY(-5px) scale(1.1);
        }
        50% {
            transform: translateY(-5px) scale(1.15);
        }
        100% {
            transform: translateY(-5px) scale(1.1);
        }
    }

    .slot-check {
        position: absolute;
        top: 6px;
        right: 6px;
        font-size: 0.8rem;
    }

    /* No Slots */
    .no-slots {
        text-align: center;
        padding: 3rem 1rem;
        background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(255, 222, 159, 0.1) 100%);
        border-radius: 15px;
        border: 1px solid var(--brand-yellow);
    }

    .no-slots-icon {
        width: 80px;
        height: 80px;
        background: var(--brand-yellow);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 2px solid var(--brand-brown);
    }

    .no-slots-icon i {
        font-size: 2rem;
        color: var(--brand-brown);
    }

    .no-slots h4 {
        font-family: 'Cairo', cursive;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--brand-brown);
        margin-bottom: 0.75rem;
    }

    .no-slots p {
        color: var(--brand-brown-light);
        font-size: 1rem;
        line-height: 1.6;
    }

    /* Summary Column */
    .summary-column {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* Summary Card */
    .summary-card {
        background: linear-gradient(135deg, var(--brand-yellow) 0%, var(--brand-yellow-dark) 100%);
        border-radius: 20px;
        padding: 2rem;
        border: 2px solid var(--brand-brown);
        box-shadow: 0 8px 32px rgba(42, 30, 30, 0.15);
        position: sticky;
        top: 2rem;
    }

    .summary-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .summary-icon {
        width: 50px;
        height: 50px;
        background: var(--brand-brown);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .summary-icon i {
        font-size: 1.3rem;
        color: var(--brand-yellow);
    }

    .summary-header h3 {
        font-family: 'Cairo', cursive;
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--brand-brown);
        margin: 0;
    }

    .summary-content {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.6);
    }

    .summary-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--brand-brown-light);
        font-weight: 600;
    }

    .summary-label i {
        color: var(--brand-brown);
    }

    .summary-value {
        font-size: 1rem;
        font-weight: 700;
        color: var(--brand-brown);
    }

    /* Action Card */
    .action-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 32px rgba(42, 30, 30, 0.08);
        border: 1px solid rgba(255, 222, 159, 0.2);
    }

    .submit-button {
        width: 100%;
        background: linear-gradient(135deg, var(--brand-brown) 0%, var(--brand-brown-dark) 100%);
        color: var(--brand-yellow);
        border: none;
        border-radius: 15px;
        padding: 1.25rem 2rem;
        font-size: 1.1rem;
        font-weight: 700;
        font-family: 'Cairo', sans-serif;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
        overflow: hidden;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        animation: buttonGlow 2s ease-in-out infinite;
    }

    .submit-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.6s ease;
    }

    .submit-button::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, transparent, rgba(255, 222, 159, 0.1), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .submit-button:hover::before {
        left: 100%;
    }

    .submit-button:hover::after {
        opacity: 1;
    }

    .submit-button:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 12px 35px rgba(42, 30, 30, 0.5);
        background: linear-gradient(135deg, var(--brand-brown-dark) 0%, var(--brand-brown) 100%);
    }

    .submit-button:active {
        transform: translateY(-2px) scale(0.98);
    }

    .submit-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
        animation: none;
    }

    @keyframes buttonGlow {
        0%, 100% {
            box-shadow: 0 4px 15px rgba(42, 30, 30, 0.2);
        }
        50% {
            box-shadow: 0 4px 25px rgba(42, 30, 30, 0.4);
        }
    }

    .button-content,
    .button-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 222, 159, 0.3);
        border-top: 2px solid var(--brand-yellow);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Empty Cart */
    .empty-cart-card {
        background: linear-gradient(135deg, var(--brand-yellow-light) 0%, rgba(255, 222, 159, 0.1) 100%);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        border: 2px solid var(--brand-yellow);
    }

    .empty-cart-icon {
        width: 80px;
        height: 80px;
        background: var(--brand-yellow);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        border: 2px solid var(--brand-brown);
    }

    .empty-cart-icon i {
        font-size: 2rem;
        color: var(--brand-brown);
    }

    .empty-cart-card h4 {
        font-family: 'Cairo', cursive;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--brand-brown);
        margin-bottom: 1rem;
    }

    .empty-cart-card p {
        color: var(--brand-brown-light);
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .browse-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--brand-brown);
        color: var(--brand-yellow);
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .browse-button:hover {
        background: var(--brand-brown-dark);
        transform: translateY(-2px);
        text-decoration: none;
        color: var(--brand-yellow);
    }


    /* Error Messages */
    .error-message {
        color: #dc2626;
        font-size: 0.9rem;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 8px;
    }

    .error-message i {
        font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .booking-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .summary-column {
            order: -1;
        }

        .summary-card {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .booking-container {
            padding: 0 0.5rem;
        }

        .section-card,
        .summary-card,
        .action-card,
        .empty-cart-card,
        .features-card {
            padding: 1.5rem;
        }

        .slots-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 0.75rem;
        }

        .time-slot {
            padding: 0.75rem 0.5rem;
            font-size: 0.9rem;
        }

        .item-details {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .section-card,
        .summary-card,
        .action-card,
        .empty-cart-card,
        .features-card {
            padding: 1rem;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .slots-grid {
            grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
            gap: 0.5rem;
        }

        .time-slot {
            padding: 0.5rem 0.25rem;
            font-size: 0.8rem;
        }

        .submit-button {
            padding: 1rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>