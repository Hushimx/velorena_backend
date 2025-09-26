<?php

return [
    // Phone validation
    'phone' => [
        'required' => 'رقم الهاتف مطلوب.',
        'regex' => 'يرجى إدخال رقم هاتف صحيح.',
    ],
    
    // Items validation
    'items' => [
        'required' => 'عنصر واحد على الأقل مطلوب.',
        'min' => 'عنصر واحد على الأقل مطلوب.',
        'max' => 'الحد الأقصى 50 عنصر لكل طلب.',
    ],
    
    // Product validation
    'product_id' => [
        'required' => 'معرف المنتج مطلوب.',
        'exists' => 'المنتج المحدد غير موجود.',
    ],
    
    // Quantity validation
    'quantity' => [
        'required' => 'الكمية مطلوبة.',
        'min' => 'يجب أن تكون الكمية 1 على الأقل.',
        'max' => 'لا يمكن أن تتجاوز الكمية 100.',
    ],
    
    // Option validation
    'option' => [
        'exists' => 'الخيار المحدد غير موجود.',
    ],
];
