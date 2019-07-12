# gmail-api-reademail
Reading e-mail using Google-API

Projeto baseado no 
<a href="https://developers.google.com/gmail/api/quickstart/php">tutorial</a> da <a href="https://developers.google.com/gmail/api/">documentação da Google-API</a>.

Primeiramente deve-se acessar a pagina de tutorial da API, e seguir o "passo 1", clicando no botão <b>"Enable Gmail API"</b> e fazendo o dowload do arquivo <i>credentials.json</i>. Este arquivo deve ser colocado dentro da pasta raiz do projeto.

Para a utilização deve-se executar o comando <code>composer install</code> após o clone do repositório.

O comando <code>php quickstart.php</code> deve ser executado, ele gera uma URL que deve ser acessada por um web browser para gerar um código de verificação, o qual é pedido ao fim dessa execução.

Por fim pode-se fazer acesso dos e-mails através da página index.php pelo servidor(XAMPP ou Servidor web).
