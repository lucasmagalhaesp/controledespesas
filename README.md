# API Controle de Despesas

## Sobre a API
Uma API que te auxilia no gerenciamento de suas despesas de uma forma muito simples

## Tecnologias Utilizadas na API
Esse projeto é uma API desenvolvida com o Laravel Framework (versão 11)

## Instruções para uso

### Instale suas dependências
```bash
composer install
```

### Configurações
```bash
Configure seu banco de dados e e-mail no arquivo .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=controle_despesas
DB_USERNAME=
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
```

### Execute suas migrations
```bash
php artisan migrate
```

### Inicie a aplicação em ambiente de desenvolvimento
```bash
php artisan serve
```

### Comando para processar a fila que enviará um e-mail ao usuário (na função de cadastrar despesas)
```bash
php artisan queue:work
```

### Documentação da API
[Acesse a documentalçao](https://documenter.getpostman.com/view/19094673/2sAXjQ3AgV)
