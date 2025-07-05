Documentação do Backend de Autenticação (API para Frontend)
Olá, equipe de Frontend!
Aqui está um guia completo sobre como o backend de autenticação foi configurado e coamo vocês podem interagir com ele para o cadastro, login e logout de usuários. Todo o ambiente de desenvolvimento está configurado via XAMPP/Apache/MariaDB.
1. Visão Geral do Ambiente e Estrutura
Servidor Web: Apache (parte do XAMPP).
Linguagem Backend: PHP.
Banco de Dados: MariaDB (compatível com MySQL), acessível via localhost.
Nome do Banco de Dados: QUIZ_IFSC
Localização do Projeto: C:\xampp\htdocs\Quiz-ADS (o seu projeto Quiz-ADS deve estar dentro da pasta htdocs do XAMPP para que o Apache possa executá-lo).
2. Estrutura do Banco de Dados
O banco de dados QUIZ_IFSC contém as seguintes tabelas principais para a autenticação e futuras funcionalidades do quiz:
users: Armazena informações dos usuários (administradores e comuns).
id (INT, Chave Primária, Auto-incremento)
email (VARCHAR(255), Único, Não Nulo)
password (VARCHAR(255), Não Nulo) - Armazena o hash da senha (NUNCA a senha em texto puro).
is_admin (BOOLEAN, Padrão FALSE) - Indica se o usuário tem privilégios de administrador.
score (DECIMAL(5, 2)) - Futuro para pontuação do quiz.
created_at, updated_at (TIMESTAMP)
questions: Armazena o texto de cada pergunta do quiz.
id (INT, Chave Primária, Auto-incremento)
question_text (TEXT, Não Nulo)
created_at, updated_at (TIMESTAMP)
options: Armazena as 4 opções de resposta para cada pergunta e indica qual é a correta.
id (INT, Chave Primária, Auto-incremento)
question_id (INT, Chave Estrangeira referenciando questions.id)
option_text (VARCHAR(255), Não Nulo)
is_correct (BOOLEAN, Padrão FALSE) - TRUE se esta for a opção correta.
3. Usuários Iniciais para Teste
Para facilitar o desenvolvimento e os testes iniciais, o banco de dados já foi populado com alguns usuários. A senha para todos os usuários abaixo é admin123:
Usuário Administrador:
Email: admin@quizads.com
Senha: admin123 (já hasheada no banco de dados)
Usuário Comum:
Email: usuario@quizads.com
Senha: admin123 (já hasheada no banco de dados)
Vocês também podem cadastrar novos usuários através do formulário de registro, e eles serão adicionados ao banco de dados com a flag is_admin como FALSE por padrão.
4. Endpoints da API para Autenticação (PHP via AJAX)
Para interagir com o backend de autenticação, vocês farão requisições AJAX para os seguintes URLs. É crucial que o frontend envie os dados no formato correto e espere as respostas JSON.
a) Cadastro de Usuário
URL: http://localhost/Quiz-ADS/ajax/register.php
Método HTTP: POST
Tipo de Dados (Body): x-www-form-urlencoded
Isso significa que os dados devem ser enviados como pares chave-valor, como em um formulário HTML padrão.
Parâmetros Esperados (no Body da requisição POST):
email (string, obrigatório)
password (string, obrigatório)
Exemplo de Resposta (JSON):
Sucesso:
{
    "success": true,
    "message": "Cadastro realizado com sucesso!"
}


Erro (email já cadastrado ou validação):
{
    "success": false,
    "message": "Este email já está cadastrado."
}


Erro (dados obrigatórios não fornecidos):
{
    "success": false,
    "message": "Email e senha são obrigatórios."
}


b) Login de Usuário
URL: http://localhost/Quiz-ADS/ajax/login.php
Método HTTP: POST
Tipo de Dados (Body): x-www-form-urlencoded
Parâmetros Esperados (no Body da requisição POST):
email (string, obrigatório)
password (string, obrigatório)
Exemplo de Resposta (JSON):
Sucesso (usuário comum):
{
    "success": true,
    "message": "Login realizado com sucesso!",
    "redirect": "quiz_page.php" // Sugestão de página para redirecionar o usuário comum
}


Sucesso (administrador):
{
    "success": true,
    "message": "Login realizado com sucesso!",
    "redirect": "admin_dashboard.php" // Sugestão de página para redirecionar o admin
}


Erro (email ou senha incorretos):
{
    "success": false,
    "message": "Email ou senha incorretos."
}


c) Logout de Usuário
URL: http://localhost/Quiz-ADS/ajax/logout.php
Método HTTP: GET (um POST também funcionaria, mas GET é comum para logout, pois não há dados sensíveis a serem enviados no corpo da requisição).
Parâmetros Esperados: Nenhum.
Exemplo de Resposta (JSON):
Sucesso:
{
    "success": true,
    "message": "Sessão encerrada com sucesso!",
    "redirect": "index.php" // Sugestão de página para redirecionar após o logout
}


5. Gerenciamento de Sessão (Para o Frontend)
Após um login bem-sucedido, o backend armazena informações do usuário na sessão PHP. Isso é transparente para o frontend, mas é importante entender:
O PHP usa cookies de sessão para manter o estado do usuário logado entre as requisições.
Quando o frontend recebe uma resposta de login bem-sucedida, ele deve redirecionar o navegador para a página indicada no campo redirect da resposta JSON. Isso garante que a nova requisição à página já tenha a sessão ativa e o PHP possa verificar o status do usuário (logado/admin).
Não é necessário que o JavaScript do frontend manipule diretamente a sessão; o PHP faz isso automaticamente.
6. Próximos Passos e Considerações
Todos os endpoints de autenticação foram exaustivamente testados via Postman e estão respondendo conforme o esperado.
A base de dados está pronta para receber e gerenciar usuários.
Qualquer dúvida sobre os formatos de requisição, respostas, ou comportamento dos endpoints, por favor, entrem em contato com o Pedro (responsável pelo backend).
Estou à disposição para colaborar e garantir uma integração suave entre o frontend e o backend!
