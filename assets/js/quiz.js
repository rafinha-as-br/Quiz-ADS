$(document).ready(function () {
    let questions = [];

    function loadQuiz() {
        $.get("../ajax/get_quiz.php", function (data) {
            questions = JSON.parse(data);
            renderQuestions();
        });
    }

    function renderQuestions() {
        let html = '<form id="quiz-form">';
        questions.forEach((q, i) => {
            html += `<div class="question-block">
                        <p><strong>${i + 1}. ${q.question_text}</strong></p>`;
            q.options.forEach(opt => {
                html += `
                    <label>
                        <input type="radio" name="question_${q.question_id}" value="${opt.id}" required>
                        ${opt.text}
                    </label><br>`;
            });
            html += '</div><br>';
        });
        html += '<button type="submit">Enviar Respostas</button></form>';
        $('#quiz-area').html(html);
    }

    $('#quiz-area').on('submit', '#quiz-form', function (e) {
        e.preventDefault();
        const answers = [];

        questions.forEach(q => {
            const selected = $(`input[name="question_${q.question_id}"]:checked`).val();
            if (selected) answers.push(selected);
        });

        $.post("../ajax/submit_quiz.php", { answers }, function (data) {
            const res = JSON.parse(data);
            if (res.success) {
                $('#quiz-area').hide();
                $('#result-area').html(`
                    <h3>Resultado:</h3>
                    <p>Você acertou <strong>${res.score}</strong> de <strong>${res.total}</strong> perguntas!</p>
                    <button id="try-again">Tentar Novamente</button>
                `).show();
            }
        });
    });

    $('#result-area').on('click', '#try-again', function () {
        $('#result-area').hide();
        $('#quiz-area').show();
        loadQuiz();
    });

    $('#logout-btn').click(function () {
        window.location.href = "../ajax/logout.php";
    });

    loadQuiz();
});
