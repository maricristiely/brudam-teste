<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastra Token</title>
</head>
<style>
        body 
        {
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            width: 100;
            border: 15px;
            padding: 50px;
        }

        input[type=text], select {
            width: 25%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            background-color: #ebebeb;
            color: black;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        div {
            border-radius: 5px;
            padding: 20px;
            text-align: center;
        }
</style>
<body>
    <h2 style="text-align:center;">Cadastra Token</h2>
    <a href="http://localhost/testes/token.php" self="_blank">Cadastra Token</a> |
    <a href="http://localhost/testes/importa.php" self="_blank">Importa API</a> | 
    <a href="http://localhost/testes/consulta.php" self="_blank">Consulta API</a>  

    <div style="width: 100%">
        <form action="token.php" method="POST">
            <input type="text" id="token" name="token" placeholder="Insira o Token">
            <input type="submit" value="Cadastrar token" name="cadastrar">
        </form>
    </div>
    <?php

        //Verifica se o botao importar foi clicado
        if(isset($_POST["cadastrar"]) == false)
        { 
            exit;
        }

        $token = "";

        if(isset($_POST["token"]))
        { 
             // le o valor do token da tela
            $token = $_POST["token"];
        }

        if($token == "")
        { 
            echo "Informe o campo Token";
            exit;
        }

        //Atribuindo os comandos do sql de exclusao e insersao
        $sql_delete = "DELETE FROM sistema";
        
        $sql_insert = "INSERT INTO sistema (token) VALUES ( '$token')";
        
        //Conectando e executando os comandos de exclusao e insersao
        $connect = mysqli_connect("localhost", "root", "", "relatorios");
        $connect->query($sql_delete);
        $connect->query($sql_insert);

        $connect->close();

        echo "Token foi inserido com sucesso";
        
    ?>
</body>
</html>