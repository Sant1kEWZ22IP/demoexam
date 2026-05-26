// filter.js - клиентская фильтрация заявок (дополнительно к серверной)

document.addEventListener('DOMContentLoaded', function() {
    
    // Автоматическое обновление фильтра при изменении полей (опционально)
    const filterLogin = document.querySelector('input[name="filter_login"]');
    const filterStatus = document.querySelector('select[name="filter_status"]');
    
    if(filterLogin) {
        // Можно включить автоматическую фильтрацию при вводе (раскомментировать при необходимости)
        /*
        let typingTimer;
        filterLogin.addEventListener('input', function() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                this.form.submit();
            }, 500);
        });
        */
    }
    
    if(filterStatus) {
        // Автоматическая фильтрация при смене статуса
        filterStatus.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    // Подсветка активных фильтров
    highlightActiveFilters();
    
    function highlightActiveFilters() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if(urlParams.has('filter_login') && urlParams.get('filter_login') !== '') {
            const loginInput = document.querySelector('input[name="filter_login"]');
            if(loginInput) loginInput.style.borderColor = '#007bff';
        }
        
        if(urlParams.has('filter_status') && urlParams.get('filter_status') !== '') {
            const statusSelect = document.querySelector('select[name="filter_status"]');
            if(statusSelect) statusSelect.style.borderColor = '#007bff';
        }
    }
});