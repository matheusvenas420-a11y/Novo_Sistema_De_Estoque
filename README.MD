# Sistema de Controle de Estoque

Aplicação web desenvolvida para cadastrar e gerenciar produtos utilizando PHP e MariaDB. O projeto foi desenvolvido como atividade prática de desenvolvimento de sistemas, aplicando operações CRUD e integração com banco de dados.

## Funcionalidades

- Cadastrar, editar e excluir produtos.
- Impedir que dois produtos utilizem o mesmo código.
- Pesquisar produtos por nome, código ou descrição.
- Visualizar a descrição completa de cada produto por meio de uma janela.
- Exibir a quantidade de produtos encontrados.
- Mostrar mensagens após operações de cadastro, edição e exclusão.

## Tecnologias

- PHP com PDO para acesso ao banco de dados.
- MariaDB para armazenar os produtos.
- HTML5 para estruturar as páginas.
- Tailwind CSS para estilização da interface.
- JavaScript para interações na interface.
- XAMPP para executar o Apache e o MariaDB localmente.
- DBeaver para criar e consultar o banco de dados.

## Requisitos

- XAMPP instalado, com Apache e MySQL/MariaDB.
- DBeaver instalado para executar o script de criação do banco.
- Extensão PDO MySQL habilitada no PHP do XAMPP.
- Navegador de internet.

## Instalação e execução

### 1. Colocar o projeto no diretório do XAMPP

Copie a pasta do projeto para:

```text
C:\xampp\htdocs\SistemaEstoque
```

O arquivo inicial do sistema é `index.php`.

### 2. Iniciar os serviços

Abra o Painel de Controle do XAMPP e inicie:

1. **Apache**
2. **MySQL** (serviço de banco de dados MariaDB do XAMPP)

### 3. Criar o banco e a tabela pelo DBeaver

Abra o DBeaver e conecte ao MariaDB local.

Caso ainda não tenha uma conexão configurada:

1. Clique em **Nova conexão**.
2. Escolha **MariaDB**.
3. Informe os dados de conexão:
   - Host: `localhost`
   - Porta: `3306`
   - Usuário: `root`
   - Senha: vazia, caso esteja usando a configuração padrão do XAMPP.
4. Teste a conexão e finalize.

Depois de conectar:

1. Clique com o botão direito na conexão do MariaDB.
2. Abra um novo editor SQL.
3. Copie e execute o script abaixo.

```sql
CREATE DATABASE sistema_estoque
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sistema_estoque;

CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(30) NOT NULL UNIQUE,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    quantidade INT NOT NULL DEFAULT 0,
    preco DECIMAL(10,2) NOT NULL
);
```

A coluna `codigo` possui a regra `UNIQUE`, que impede o cadastro de produtos com códigos repetidos.

**Importante:** se o banco de dados e a tabela já existirem, não execute novamente os comandos de criação.

### 4. Conferir a conexão do PHP

Abra o arquivo `conexao.php` e confira os dados utilizados para conectar ao banco:

```php
$host = "localhost";
$banco = "sistema_estoque";
$usuario = "root";
$senha = "";
```

Esses são os valores padrão utilizados neste projeto com o XAMPP.

Caso seu MariaDB utilize outra porta, usuário ou senha, ajuste as configurações no arquivo `conexao.php`.

### 5. Abrir o sistema

No navegador, acesse:

```text
http://localhost/SistemaEstoque/
```

Se o Apache estiver configurado para utilizar a porta 81, acesse:

```text
http://localhost:81/SistemaEstoque/
```

## Como usar

1. Clique em **Novo Produto** para abrir o formulário de cadastro.
2. Informe o código, nome, descrição, quantidade e preço do produto.
3. Clique em salvar para cadastrar o produto.
4. Na listagem, utilize a barra de pesquisa para procurar produtos por nome, código ou descrição.
5. Clique em **Ver descrição** para visualizar a descrição completa do produto.
6. Utilize **Editar** para alterar as informações de um produto.
7. Utilize **Excluir** para remover um produto do estoque.

Se um código já estiver associado a outro produto, o sistema informa que ele está em uso.

Os produtos são armazenados no banco de dados MariaDB e permanecem disponíveis mesmo depois que o navegador é fechado.

## Estrutura dos arquivos principais

- `index.php`: lista, pesquisa e exibe os produtos cadastrados.
- `cadastrar.php`: apresenta e processa o formulário de cadastro.
- `editar.php`: apresenta e processa o formulário de edição.
- `excluir.php`: realiza a exclusão de produtos.
- `conexao.php`: configura a conexão com o MariaDB.

## Verificação rápida

Para conferir se a instalação está funcionando:

1. Cadastre um produto e verifique se ele aparece na listagem.
2. Tente cadastrar outro produto com o mesmo código e confirme que o sistema impede a duplicidade.
3. Pesquise produtos por nome, código e descrição.
4. Clique em **Ver descrição** e confira se as informações são exibidas corretamente.
5. Edite um produto e confirme se as alterações foram salvas.
6. Exclua um produto de teste e verifique se ele desaparece da listagem.