# Teste Brudam

Importando API e consultando os dados inseridos no Banco de Dados.

- Instruções do Banco de Dados:

```
CREATE TABLE sistema
(
    token VARCHAR(100)
);

CREATE TABLE coletas
(
    id_coleta INT PRIMARY KEY,
    tipo_coleta INT,
    status_coleta VARCHAR(100),
    data_emissao_coleta DATETIME,
    origem_coleta VARCHAR(100),
    destino_coleta VARCHAR(100),
    volume_coleta INT,
    peso_coleta DECIMAL(10,2),
    frete_coleta DECIMAL(10,2)
);

CREATE TABLE minutas
(
    id_minuta INT PRIMARY KEY,
    tipo_minuta INT,
    status_minuta VARCHAR(100),
    data_emissao_minuta DATETIME,
    cte_minuta VARCHAR(100),
    origem_minuta VARCHAR(100),
    destino_minuta VARCHAR(100),
    volume_minuta INT,
    peso_minuta DECIMAL(10,2),
    frete_minuta DECIMAL(10,2),
    id_coleta INT
);
```
- Testando o Sistema:

1º Passo: Tela Cadastrar Token  - Nesta tela, vamos inserir o token válido ce019046e010bf7f1aab029cc688c9fd e cadastrar e armazenar no banco.

2º Passo: Tela Importação API - Essa tela irá importar os dados via POST mas só será possível se o TOKEN for válido. Caso válido, o sistema importa e grava os dados da API no banco, 
se não for válido traz uma mensagem ao usuário que o token não é válido e que os dados não foram importados.

3º Passo: Tela Consulta: Se faz necessário selecionar um período no filtro data inicial e final. Estando preenchido com o período de datas que contém no banco traz o resultado. Caso o período preenchido não são as mesmas datas que contém no banco traz uma mensagem ao usuário. E se o botão CONSULTAR seja clicado sem ter data selecionada não traz nenhuma informação.
	
