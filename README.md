# 🏥 Sistema SUS – Gerenciamento de Pacientes, Consultas e Medicamentos

Sistema web desenvolvido em **PHP + MySQL**, com foco em controle de:

- Cadastro de usuários (pacientes e administradores)
- Login seguro com hash de senha
- Consultas agendadas
- Alertas de medicamentos
- Painel administrativo completo
- Exclusão de usuários, medicamentos e consultas

Este sistema foi criado para facilitar a organização da rotina de saúde dos pacientes e permitir que administradores tenham controle total do banco de dados com uma interface simples e intuitiva.

---

## 🚀 Tecnologias Utilizadas

- **PHP 8+**
- **MySQL (MariaDB)**
- **XAMPP** ou outro servidor local
- **HTML5 / CSS3**
- **Prepared Statements (SQL Seguro)**

---

## 📌 Funcionalidades

### 👤 Área do Usuário
- Login por CPF e senha
- Visualização de consultas
- Visualização de alertas de medicamentos

### 🛠️ Área do Administrador
- Registro de novos usuários
- Listagem completa de usuários e consultas
- Edição de dados
- Exclusão de:
  - Usuários
  - Medicamentos
  - Consultas

⚠️ Exclusão de usuários remove automaticamente suas consultas e medicamentos (sem erros de chave estrangeira).

---

## 📁 Estrutura do Banco de Dados

### **Tabela `users`**
```sql
id INT AUTO_INCREMENT PRIMARY KEY
name VARCHAR(255)
cpf VARCHAR(20) UNIQUE
phone VARCHAR(50)
password VARCHAR(255)
is_admin TINYINT(1)
Tabela medications
sql
Copy code
id INT AUTO_INCREMENT PRIMARY KEY
user_id INT
name VARCHAR(255)
next_refill_date DATE
Tabela consultas
sql
Copy code
id INT AUTO_INCREMENT PRIMARY KEY
user_id INT
descricao VARCHAR(255)
date DATE
time TIME
📦 Instalação
1️⃣ Clonar o repositório
bash
Copy code
git clone https://github.com/SEU_USUARIO/NOME_DO_REPOSITORIO.git
2️⃣ Importar o banco de dados
Abra phpMyAdmin

Crie um banco chamado sus

Importe o arquivo .sql (caso exista no repositório)

3️⃣ Configurar conexão
Arquivo: db.php

php
Copy code
<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "sus";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>
4️⃣ Abrir no navegador
Acesse:

bash
Copy code
http://localhost/Site_Sus/index.php
🔐 Segurança Implementada
Hash de senha com password_hash()

Login com password_verify()

SQL protegido com Prepared Statements

Bloqueio de páginas para usuários não logados

Proteção contra SQL Injection

🖥️ Telas do Sistema
(adicione prints aqui futuramente)

✨ Melhorias Futuras (Opcional)
Sistema de relatório em PDF

Dashboard com gráficos

Notificações automáticas de consulta e medicamentos

API REST

Versão mobile (PWA)

👨‍💻 Autor
Felipe Gomes

Projeto desenvolvido para estudo e aprimoramento em:

PHP

Banco de Dados

Estruturas seguras de login

CRUD completo

Boas práticas de desenvolvimento
