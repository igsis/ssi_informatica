<?php
if ($pedidoAjax) {
    require_once "../models/ArquivoModel.php";
    define('UPLOADDIR', "../uploads/");
} else {
    require_once "./models/ArquivoModel.php";
    define('UPLOADDIR', "./uploads/");
}

class ArquivoController extends ArquivoModel
{
    public function listarArquivosEnviados($chamado_id) {
        $chamado_id = MainModel::decryption($chamado_id);
        return DbModel::consultaSimples("SELECT * FROM arquivos WHERE chamado_id = '$chamado_id' AND publicado = '1'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function enviarArquivo($chamado_id, $pagina) {
        unset($_POST['pagina']);
        $chamado_id = MainModel::decryption($chamado_id);

        foreach ($_FILES as $file) {
            $numArquivos = count($file['error']);
            foreach ($file as $key => $dados) {
                for ($i = 0; $i < $numArquivos; $i++) {
                    $arquivos[$i][$key] = $file[$key][$i];
                }
            }
        }
        $erros = ArquivoModel::enviaArquivos($arquivos, $chamado_id,5, true);
        $erro = MainModel::in_array_r(true, $erros, true);

        if ($erro) {
            foreach ($erros as $erro) {
                if ($erro['bol']){
                    $lis[] = "'<li>" . $erro['arquivo'] . ": " . $erro['motivo'] . "</li>'";
                }
            }
            $alerta = [
                'alerta' => 'arquivos',
                'titulo' => 'Oops! Tivemos alguns Erros!',
                'texto' => $lis,
                'tipo' => 'error',
                'location' => SERVERURL . $pagina
            ];
        } else {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Arquivos Enviados!',
                'texto' => 'Arquivos enviados com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . $pagina
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function apagarArquivo ($arquivo_id, $pagina){
        $arquivo_id = MainModel::decryption($arquivo_id);
        $remover = DbModel::apaga('arquivos', $arquivo_id);
        if ($remover->rowCount() > 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Arquivo Apagado!',
                'texto' => 'Arquivo apagado com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . $pagina
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Oops! Algo deu Errado!',
                'texto' => 'Falha ao remover o arquivo do servidor, tente novamente mais tarde',
                'tipo' => 'error',
            ];
        }
        return MainModel::sweetAlert($alerta);
    }
}