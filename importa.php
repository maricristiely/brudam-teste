<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Importando API</title>
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
    <h2 style="text-align:center;">Importa API</h2>
    <a href="http://localhost/testes/token.php" self="_blank">Cadastra Token</a> |
    <a href="http://localhost/testes/importa.php" self="_blank">Importa API</a> | 
    <a href="http://localhost/testes/consulta.php" self="_blank">Consulta API</a> 
    <div style="width: 100%">
        <form action="importa.php" method="POST">
            
            
            <input type="submit" value="Importar API" name="importar">
        </form>
    </div>
    <?php

        if (isset($_POST["importar"]) == false)
        {
            exit;
        }

        $connect = mysqli_connect("localhost", "root", "", "relatorios");
        
        $sql_sistema = "SELECT token FROM sistema";
        $consulta = $connect->query($sql_sistema);
        $registro = $consulta->fetch_assoc();
        $token = $registro["token"];
        
        if ($token == "")
        {
            echo "Não há token cadastrado. Vâ até a tela Cadastra Token";
            exit;
        }

        $url = "https://app-brudam.herokuapp.com/kabum/api/show/remessas/$token";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Chamando API
        $json = curl_exec($ch);

        //Lendo estrutura do json
        $resultado = json_decode($json, false);
        
        //testando se o token e valido
        $mensagem = $resultado->message;
        $status = $resultado->status;

        //Status 0 siginifica que o token do servidor
        if ($status == 0)
        {
            //Exibindo a mendagem que vem do servidor
            echo $mensagem;
            exit;
        }

        //a estrutura do json deve ser lida antes do foreach
        $coletas = $resultado->data->gulliverExpress->coletas;
        $minutas = $resultado->data->gulliverExpress->minutas; 
        
        foreach ($coletas as $registro)
        {
            $id_coleta = $registro->id_coleta;
            $tipo_coleta = $registro->tipo;
            $status_coleta = $registro->status;
            $data_emissao_coleta = $registro->data_emissao;
            $origem_coleta = $registro->origem;
            $destino_coleta = $registro->destino;
            $volume_coleta = $registro->volume;
            $peso_coleta = $registro->peso;
            $frete_coleta = $registro->frete;

            $sql_consulta = "SELECT * FROM coletas WHERE id_coleta = '$id_coleta'";

            $consulta = $connect->query($sql_consulta);

            $resultado = mysqli_num_rows($consulta);

            if ($resultado > 0)
            {
                echo "<p>Coleta $id_coleta já existe no banco de dados</p>";
            }
            else
            {
                $sql = "INSERT INTO coletas (id_coleta, tipo_coleta, status_coleta, data_emissao_coleta, origem_coleta, destino_coleta, volume_coleta, peso_coleta, frete_coleta)
                VALUES ( '$id_coleta', '$tipo_coleta', '$status_coleta', '$data_emissao_coleta', '$origem_coleta', '$destino_coleta', '$volume_coleta', '$peso_coleta', '$frete_coleta')";
                
                $connect->query($sql);

                echo "Coleta $id_coleta foi inserida com sucesso";
                echo "<p>Dados importado com sucesso. Token: $token</p>";
            }

        }
        
        foreach ($minutas as $registro)
        {
            $id_minuta = $registro->id_minuta;
            $tipo_minuta = $registro->tipo;
            $status_minuta = $registro->status;
            $data_emissao_minuta = $registro->data_emissao;
            $cte_minuta = $registro->cte;
            $origem_minuta = $registro->origem;
            $destino_minuta = $registro->destino;
            $volume_minuta = $registro->volume;
            $peso_minuta = $registro->peso;
            $frete_minuta = $registro->frete;
            $id_coleta = $registro->id_coleta;

            $sql_consulta = " SELECT * FROM minutas WHERE id_minuta = '$id_minuta'";

            $consulta = $connect->query($sql_consulta);
            $resultado = mysqli_num_rows($consulta);

            if ($resultado > 0)
            {
                echo "<p>Minuta $id_minuta já existe no banco de dados</p>";
            }
            else
            {
                if ($id_coleta == null)
                {
                    $id_coleta = "NULL";
                }
                $sql = "INSERT INTO minutas (id_minuta, tipo_minuta, status_minuta, data_emissao_minuta, cte_minuta, origem_minuta, destino_minuta, volume_minuta, peso_minuta, frete_minuta, id_coleta)
                VALUES ( '$id_minuta', '$tipo_minuta', '$status_minuta', '$data_emissao_minuta', '$cte_minuta', '$origem_minuta', '$destino_minuta', '$volume_minuta', '$peso_minuta', '$frete_minuta', $id_coleta)";

                $connect->query($sql);

                echo "Minuta $id_minuta foi inserida com sucesso";
                echo "<p>Dados importado com sucesso. Token: $token</p>";
            }
        }
        
        $connect->close();
    ?>
</body>
</html>
