// Carregar perguntas
function loadQuestions() {
    $.get("../ajax/questions_list.php", function(data) {
        let questions = JSON.parse(data);
        let html = '';
        Object.entries(questions).forEach(([id, q], i) => {
            html += `<div class="question-card">
                        <strong>${i + 1}.</strong> ${q.question}
                        <ul>`;
            q.options.forEach(opt => {
                html += `<li ${opt.is_correct == 1 ? 'style="color:green;"' : ''}>
                            ${opt.text}
                         </li>`;
            });
            html += `</ul>
                     <button class="delete-btn" data-id="${id}">Deletar</button>
                     </div><hr>`;
        });
        $('#questions-list').html(html);
    });
}

$(document).ready(function() {
    loadQuestions();

    $('#add-question-form').submit(function(e) {
        e.preventDefault();
        $.post("../admin/add_question.php", $(this).serialize(), function(response) {
            let res = JSON.parse(response);
            alert(res.message);
            if (res.success) {
                $('#add-question-form')[0].reset();
                loadQuestions();
            }
        });
    });

    $(document).on('click', '.delete-btn', function() {
        if (!confirm("Tem certeza que deseja deletar esta pergunta?")) return;
        const questionId = $(this).data('id');
        $.post("../admin/delete_question.php", { id: questionId }, function(response) {
            let res = JSON.parse(response);
            alert(res.message);
            if (res.success) loadQuestions();
        });
    });
});
