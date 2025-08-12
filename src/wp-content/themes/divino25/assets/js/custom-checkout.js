/**
 * Кастомизация блочного чекаута WooCommerce
 */
wp.hooks.addFilter(
    'woocommerce.checkout.fields',
    'custom-checkout',
    (fields) => {
        const updatedFields = { ...fields };
        // Удаление всех полей платежного адреса
        delete updatedFields.billing.billing_company;
        delete updatedFields.billing.billing_address_1;
        delete updatedFields.billing.billing_address_2;
        delete updatedFields.billing.billing_city;
        delete updatedFields.billing.billing_postcode;
        delete updatedFields.billing.billing_country;
        delete updatedFields.billing.billing_state;

        // Добавление поля WhatsApp
        updatedFields.billing.billing_whatsapp = {
            label: 'WhatsApp',
            type: 'tel',
            required: false,
            placeholder: 'Введите номер WhatsApp (например, +1234567890)',
            validate: (value) => {
                if (!value) return true; // Поле необязательное
                const regex = /^\+[0-9]{10,15}$/;
                return regex.test(value) ? true : 'Пожалуйста, введите корректный номер WhatsApp (например, +1234567890).';
            },
        };

        // Убедимся, что номер телефона обязателен
        updatedFields.billing.billing_phone.required = true;

        return updatedFields;
    }
);

// Скрытие блока способов оплаты
wp.hooks.addAction(
    'woocommerce.checkout.payment-methods',
    'custom-checkout',
    () => {
        document.querySelector('.wc-block-components-payment-methods')?.remove();
    }
);

// Скрытие блока платежного адреса
wp.hooks.addAction(
    'woocommerce.checkout.render',
    'custom-checkout',
    () => {
        // Скрываем весь блок адреса
        const addressBlock = document.querySelector('.wc-block-components-address-form');
        if (addressBlock) {
            addressBlock.style.display = 'none';
        }
    }
);

// Динамическая валидация поля WhatsApp
document.addEventListener('DOMContentLoaded', () => {
    const whatsappInput = document.querySelector('input[name="billing_whatsapp"]');
    if (whatsappInput) {
        whatsappInput.addEventListener('input', (e) => {
            const value = e.target.value;
            const errorDiv = document.createElement('div');
            errorDiv.className = 'whatsapp-error';
            errorDiv.style.color = 'red';
            errorDiv.style.marginTop = '5px';

            const regex = /^\+[0-9]{10,15}$/;
            if (value && !regex.test(value)) {
                errorDiv.textContent = 'Пожалуйста, введите корректный номер WhatsApp (например, +1234567890).';
                if (!whatsappInput.nextElementSibling?.classList.contains('whatsapp-error')) {
                    whatsappInput.parentNode.appendChild(errorDiv);
                }
            } else {
                const existingError = whatsappInput.nextElementSibling;
                if (existingError?.classList.contains('whatsapp-error')) {
                    existingError.remove();
                }
            }
        });
    }
});