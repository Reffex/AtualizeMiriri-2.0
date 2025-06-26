<?php
    include '../../connect.php';

    function importarIndiceBCB($nome, $codigo_serie_bcb) {
        global $conn;

        $url = "https://api.bcb.gov.br/dados/serie/bcdata.sgs.$codigo_serie_bcb/dados?formato=json";
        $dados = json_decode(file_get_contents($url), true);

        if (!$dados) {
            echo "Erro ao obter dados do índice $nome<br>";
            return;
        }

        foreach ($dados as $linha) {
            $data_brasileira = $linha['data']; 
            $valor = floatval(str_replace(',', '.', $linha['valor']));

        // Transformar para formato YYYY-MM-01
            $partes = explode('/', $data_brasileira);
            if (count($partes) === 2) {
                $data_ref = $partes[1] . '-' . str_pad($partes[0], 2, '0', STR_PAD_LEFT) . '-01';

            // Inserir no banco se ainda não existir
                $stmt = $conn->prepare("INSERT IGNORE INTO indices (nome, data_ref, valor) VALUES (?, ?, ?)");
                $stmt->bind_param("ssd", $nome, $data_ref, $valor);
                $stmt->execute();
            }
        }

        echo "✅ Importado índice $nome com sucesso.<br>";
    }

// Códigos SGS do Banco Central:
    importarIndiceBCB('ipca', 433);
    importarIndiceBCB('inpc', 188);
    importarIndiceBCB('selic', 1178);

?>
