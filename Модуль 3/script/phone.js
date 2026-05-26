// phone.js - маска для поля телефона
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.querySelector('input[name="phone"]');
    
    if(phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = this.value.replace(/\D/g, ''); // Удаляем все не-цифры
            
            // Ограничиваем длину (11 цифр для +7XXXXXXXXXX)
            if(value.length > 11) {
                value = value.slice(0, 11);
            }
            
            // Форматируем как +7(XXX)XXX-XX-XX
            let formatted = '';
            if(value.length > 0) {
                formatted = '+7';
                if(value.length > 1) {
                    formatted += '(' + value.slice(1, 4);
                }
                if(value.length > 4) {
                    formatted += ')' + value.slice(4, 7);
                }
                if(value.length > 7) {
                    formatted += '-' + value.slice(7, 9);
                }
                if(value.length > 9) {
                    formatted += '-' + value.slice(9, 11);
                }
            }
            
            this.value = formatted;
        });
        
        // Обработка клавиши Backspace
        phoneInput.addEventListener('keydown', function(e) {
            if(e.key === 'Backspace' && this.value.length > 0) {
                // Позволяем удалять по одному символу
                setTimeout(() => {
                    let clean = this.value.replace(/\D/g, '');
                    if(clean.length > 0) {
                        let formatted = '';
                        if(clean.length > 0) {
                            formatted = '+7';
                            if(clean.length > 1) {
                                formatted += '(' + clean.slice(1, 4);
                            }
                            if(clean.length > 4) {
                                formatted += ')' + clean.slice(4, 7);
                            }
                            if(clean.length > 7) {
                                formatted += '-' + clean.slice(7, 9);
                            }
                            if(clean.length > 9) {
                                formatted += '-' + clean.slice(9, 11);
                            }
                        }
                        this.value = formatted;
                    }
                }, 10);
            }
        });
    }
});