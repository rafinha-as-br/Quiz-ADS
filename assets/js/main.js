console.log("main.js carregado com sucesso");

$(document).ready(function () {
    // Alternar entre Login e Cadastro
    $('#show-login').click(function () {
        $('#login-form').show();
        $('#register-form').hide();
    });

    $('#show-register').click(function () {
        $('#login-form').hide();
        $('#register-form').show();
    });

    // LOGIN
    $('#form-login').submit(function (e) {
        e.preventDefault();
        console.log("Formulário de login enviado via JS");

        $.ajax({
            type: 'POST',
            url: 'ajax/login.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                $('#login-msg').text(res.message);
                if (res.success) {
                    setTimeout(() => window.location.href = res.redirect, 1000);
                }
            },
            error: function (xhr, status, error) {
                console.error("Erro na requisição:", status, error);
                $('#login-msg').text("Erro ao tentar logar.");
            }
        });
    });

    // REGISTRO
    $('#form-register').submit(function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: 'ajax/register.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                $('#register-msg').text(res.message);
                if (res.success) {
                    $('#form-register')[0].reset();
                }
            },
            error: function () {
                $('#register-msg').text("Erro ao tentar cadastrar.");
            }
        });
    });

    // LOGOUT
    $('#logout-btn').click(function (e) {
    e.preventDefault(); // <-- ESSENCIAL para <button>
    
    const logoutUrl = window.location.pathname.includes('/admin/')
        ? '../ajax/logout.php'
        : 'ajax/logout.php';

    $.ajax({
        url: logoutUrl,
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                window.location.href = res.redirect;
            } else {
                alert("Erro ao sair: " + res.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("Erro no logout:", status, error);
            alert("Erro inesperado ao tentar sair.");
        }
    });
});

    // Carregar perguntas no dashboard do usuário
    if (window.location.pathname.includes("user/dashboard.php")) {
        $.get("../ajax/load_questions.php", function (data) {
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

    // Enviar respostas do quiz
    $('#quiz-form').submit(function (e) {
        e.preventDefault();
        let answers = {};
        $('#quiz-form input[type="radio"]:checked').each(function () {
            let questionIndex = $(this).attr('name').replace('q', '');
            answers[questionIndex] = $(this).val();
        });

        $.ajax({
            url: '../ajax/submit_answers.php',
            type: 'POST',
            data: { answers: answers },
            success: function (response) {
                let res = JSON.parse(response);
                $('#result-msg').html("Sua pontuação: " + res.score + "%");
            },
            error: function () {
                $('#result-msg').text("Erro ao enviar respostas.");
            }
        });
    });
});
