<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Consulta Dados</title>
</head>
<style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
        }

        td, th  {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: left;
            background-color: #f1f3e5;
            color: black;
        }

        tr:nth-child(even) { background-color: #f2f2f2; }

        tr:hover { background-color: #ddd; }

        input[type=date], select {
            width: 90%;
            padding: 12px 20px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type=submit] {
            width: 45%;
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
            font-size: 15px;
        }
    </style>
</head>
<body>

    <h3>898 - Relatório de Emissões via Cliente API</h3>
    <a href="http://localhost/testes/token.php" self="_blank">Cadastro Token</a> |
    <a href="http://localhost/testes/importa.php" self="_blank">Importa API</a> | 
    <a href="http://localhost/testes/consulta.php" self="_blank">Consulta API</a> 

    <div style="width: 18%">
        <form action="/testes/consulta.php">
            <label for="dataInicial">Data Inicial:</label>
            <input type="date" id="dataInicial" name="dataInicial">
            <label for="dataFinal">Data Final:</label>
            <input type="date" id="dataFinal" name="dataFinal">
            <input type="submit" value="Consultar">
        </form>
    </div>
        <table>
            <tr>
                <th>Minuta</th>
                <th>Origem</th>
                <th>Destino</th>
                <th>Volume</th>
                <th>CTE</th>
                <th>Peso</th>
                <th>Tipo Minuta</th>
                <th>Status</th>
                <th>Frete</th>
                <th>Data Emissao Minuta</th>
                <th>Coleta</th>
                <th>Tipo Coleta</th>
                <th>Data Coleta</th>
            </tr>
                <?php
                    $dataInicial = "";
                    $dataFinal = "";

                    if (isset($_GET["dataInicial"]))
                    {
                        $dataInicial = $_GET["dataInicial"];
                    }

                    if (isset($_GET["dataFinal"]))
                    {
                        $dataFinal = $_GET["dataFinal"];
                    }

                    if ($dataInicial == "" || $dataFinal == "")
                    {
                        return;
                    }

                    $sql = "SELECT * FROM minutas LEFT JOIN coletas ON minutas.id_coleta = coletas.id_coleta
                     WHERE data_emissao_minuta BETWEEN '$dataInicial 00:00:00' AND '$dataFinal 23:59:59'";

                    $connect = mysqli_connect("localhost", "root", "", "relatorios");

                    $dados = $connect->query($sql);

                    $resultado = mysqli_num_rows($dados);

                    if ($resultado == 0)
                    {
                        echo "<p>Nao ha registros para o filtro informado</p>";
                        exit;
                    }

                    while ($registro = $dados->fetch_assoc())
                    {
                        $data_emissao_minuta = $registro["data_emissao_minuta"];
                        $data_emissao_coleta = $registro["data_emissao_coleta"];
                        $peso_minuta = $registro["peso_minuta"];
                        $frete_minuta = $registro["frete_minuta"];
                        $peso_coleta = $registro["peso_coleta"];
                        $frete_coleta = $registro["frete_coleta"];

                        $peso_minuta_tela = number_format($peso_minuta, 2, ",", ".");
                        $frete_minuta_tela = number_format($frete_minuta, 2, ",", ".");
                        $peso_coleta_tela = number_format($peso_coleta, 2, ",", ".");                       
                        $frete_coleta_tela = number_format($frete_coleta, 2, ",", ".");
                        
                        if ($data_emissao_coleta)
                        {
                            $data_emissao_coleta_banco = date_create($data_emissao_coleta);
                            $data_emissao_coleta_tela = date_format($data_emissao_coleta_banco, "d/m/Y");
                            //$data_emissao_coleta_tela = date('d/m/Y', strtotime($data_emissao_coleta));
                        }
                        else   
                        {
                            $data_emissao_coleta_tela = "";
                        }

                        if ($data_emissao_minuta)
                        {
                            $data_emissao_minuta_banco = date_create($data_emissao_minuta);
                            $data_emissao_minuta_tela = date_format($data_emissao_minuta_banco, "d/m/Y");
                        }
                        else   
                        {
                            $data_emissao_minuta_tela = "";
                        }

                        echo "<tr>";
                        echo "<td>" . $registro["id_minuta"] . "</td>";
                        echo "<td>" . $registro["origem_minuta"] . "</td>";
                        echo "<td>" . $registro["destino_minuta"] . "</td>";
                        echo "<td>" . $registro["volume_minuta"] . "</td>";
                        echo "<td>" . $registro["cte_minuta"] . "</td>";
                        echo "<td>" . $peso_minuta_tela . "</td>";
                        echo "<td>" . $registro["tipo_minuta"] . "</td>";
                        echo "<td>" . $registro["status_minuta"] . "</td>";
                        echo "<td>" . $frete_minuta_tela . "</td>";
                        echo "<td>" . $data_emissao_minuta_tela . "</td>";
                        echo "<td>" . $registro["id_coleta"] . "</td>";
                        echo "<td>" . $registro["tipo_coleta"] . "</td>";
                        echo "<td>" . $data_emissao_coleta_tela . "</td>";
                        echo "</tr>";
                    }

                    $connect->close();
            ?>
</body>
</html>
