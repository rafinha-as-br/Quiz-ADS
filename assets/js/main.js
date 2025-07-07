// assets/js/main.js
$(document).ready(function() {
    $('#show-login').click(function() {
        $('#login-form').show();
        $('#register-form').hide();
    });

    $('#show-register').click(function() {
        $('#login-form').hide();
        $('#register-form').show();
    });

    // LOGIN
    $('#form-login').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'ajax/login.php',
            data: $(this).serialize(),
            success: function(response) {
                let res = JSON.parse(response);
                $('#login-msg').text(res.message);
                if (res.success) {
                    setTimeout(() => window.location.href = res.redirect, 1000);
                }
            }
        });
    });

    // REGISTRO
    $('#form-register').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'ajax/register.php',
            data: $(this).serialize(),
            success: function(response) {
                let res = JSON.parse(response);
                $('#register-msg').text(res.message);
                if (res.success) {
                    $('#form-register')[0].reset();
                }
            }
        });
    });
});

// Carregar perguntas quando estiver no dashboard
if (window.location.pathname.includes("user/dashboard.php")) {
    $.get("../ajax/load_questions.php", function(data) {
        let questions = JSON.parse(data);
        let html = '';
        questions.forEach((q, i) => {
            html += `<div class="question">
                        <p><strong>${i + 1}.</strong> ${q.question}</p>`;
            q.options.forEach(opt => {
                html += `<label>
                            <input type="radio" name="q${i}" value="${opt.id}" required>
                            ${opt.text}
                         </label><br>`;
            });
            html += '</div><hr>';
        });
        $('#questions-container').html(html);
    });
}

// Enviar respostas
$('#quiz-form').submit(function(e) {
    e.preventDefault();
    let answers = {};
    $('#quiz-form input[type="radio"]:checked').each(function() {
        let questionIndex = $(this).attr('name').replace('q', '');
        answers[questionIndex] = $(this).val();
    });

    $.ajax({
        url: '../ajax/submit_answers.php',
        type: 'POST',
        data: { answers: answers },
        success: function(response) {
            let res = JSON.parse(response);
            $('#result-msg').html("Sua pontuação: " + res.score + "%");
        }
    });
});

