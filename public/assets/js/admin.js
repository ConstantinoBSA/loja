toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-bottom-right",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

document.addEventListener('DOMContentLoaded', function() {
    // Seleciona todos os elementos com a classe 'descricao'
    const descriptions = document.querySelectorAll('.descricao');

    // Define o número máximo de caracteres antes de truncar
    const maxChars = 180;

    descriptions.forEach(description => {
        // Obter o texto completo da descrição
        const fullText = description.textContent.trim();

        // Verifica se o texto excede o máximo de caracteres permitidos
        if (fullText.length > maxChars) {
            // Trunca o texto e adiciona "..."
            const truncatedText = fullText.substring(0, maxChars) + '...';
            // Define o texto truncado de volta no elemento
            description.textContent = truncatedText;
        }
    });
});
