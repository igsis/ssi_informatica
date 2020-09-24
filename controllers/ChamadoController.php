<?php
if ($pedidoAjax) {
    require_once "../models/MainModel.php";
} else {
    require_once "./models/MainModel.php";
}

class ChamadoController extends MainModel
{
    public function insereChamado()
    {
        /* executa limpeza nos campos */
        $dados = [];
        $pagina = $_POST['pagina'];
        unset($_POST['_method']);
        unset($_POST['pagina']);
        foreach ($_POST as $campo => $post) {
            $dados[$campo] = MainModel::limparString($post);
        }
        /* ./limpeza */

        /* cadastro */
        $insere = DbModel::insert('chamados', $dados);
        if ($insere->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $id = DbModel::connection()->lastInsertId();
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Chamado',
                'texto' => 'Chamado cadastrado com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . $pagina . '/nota_cadastro&id=' . MainModel::encryption($id)
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . $pagina . '/chamado_cadastro'
            ];
        }
        /* ./cadastro */
        return MainModel::sweetAlert($alerta);
    }

    /* edita */
    public function editaChamado($id)
    {
        $idDecryp = MainModel::decryption($id);

        unset($_POST['_method']);
        unset($_POST['id']);

        $dados = [];
        foreach ($_POST as $campo => $post) {
            $dados[$campo] = MainModel::limparString($post);
        }

        $edita = DbModel::update('chamados', $dados, $idDecryp);
        if ($edita->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Chamado',
                'texto' => 'Chamado editado com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/nota_cadastro&id=' . $id
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/nota_cadastro&id=' . $id
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function listaChamadoUsuario($idUsuario)
    {
        //$idUsuario = MainModel::decryption($idUsuario);
        return DbModel::consultaSimples("
            SELECT ch.*, c.categoria, l.local, cs.status FROM chamados ch 
                INNER JOIN categorias c on ch.categoria_id = c.id
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN chamado_status cs on ch.status_id = cs.id    
            WHERE usuario_id = '$idUsuario' ORDER BY status_id, id")->fetchAll(PDO::FETCH_OBJ);
    }

    public function listaChamadoAdministrador($idAdministrador,$status)
    {
        return MainModel::consultaSimples("
            SELECT ch.*, c.categoria, l.local, cs.status FROM chamados ch 
                INNER JOIN categorias c on ch.categoria_id = c.id
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN chamado_status cs on ch.status_id = cs.id
            WHERE status_id IN ($status) AND ch.administrador_id = '$idAdministrador'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function buscaChamadoAdministrador($dados)
    {
        $where = '';
        if (count($dados)) {
            $where = 'WHERE ';
            foreach ($dados as $key => $dado) {
                if ($key != 'descricao' && $key != 'solucao') {
                    if ($where != 'WHERE ') {
                        $where .= "AND ";
                    }
                    $where .= " ch.{$key} = '{$dado}' ";
                } else {
                    if ($where != '') {
                        $where .= "AND ";
                    }
                    $where .= " ch.{$key} LIKE '%{$dado}%' ";
                }
            }
        }

        $query = "SELECT ch.*, c.categoria, l.local, cs.status FROM chamados ch 
                    INNER JOIN categorias c on ch.categoria_id = c.id
                    INNER JOIN locais l on ch.local_id = l.id
                    INNER JOIN chamado_status cs on ch.status_id = cs.id    
                {$where} ORDER BY prioridade_id, id";

        $chamados = DbModel::consultaSimples($query)->fetchAll(PDO::FETCH_OBJ);

        return $chamados;
    }

    public function recuperaChamado($id)
    {
        $id = MainModel::decryption($id);
        $chamado = DbModel::consultaSimples("
            SELECT ch.*, c.categoria, l.local, cs.status FROM chamados ch 
                INNER JOIN categorias c on ch.categoria_id = c.id
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN chamado_status cs on ch.status_id = cs.id    
            WHERE ch.id = '$id'
        ")->fetchObject();
        return $chamado;
    }

    public function insereFuncionarioChamado()
    {
        /* executa limpeza nos campos */
        $dados = [];
        $idChamado = $_POST['chamado_id'];
        unset($_POST['_method']);
        unset($_POST['id']);
        foreach ($_POST as $campo => $post) {
            $dados[$campo] = MainModel::limparString($post);
        }
        /* ./limpeza */

        /* cadastro */
        $insere = DbModel::insert('chamado_funcionarios', $dados);
        if ($insere->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $id = DbModel::connection()->lastInsertId();
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Chamado',
                'texto' => 'Funcionário cadastrado no chamado com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/nota_cadastro&id=' . MainModel::encryption($idChamado)
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/nota_cadastro&id=' . MainModel::encryption($idChamado)
            ];
        }
        /* ./cadastro */
        return MainModel::sweetAlert($alerta);
    }

    public function editaFuncionarioChamado($id)
    {
        $idDecryp = MainModel::decryption($id);

        $idChamado = $_POST['chamado_id'];
        unset($_POST['_method']);
        unset($_POST['id']);

        $dados = [];
        foreach ($_POST as $campo => $post) {
            $dados[$campo] = MainModel::limparString($post);
        }

        $edita = DbModel::update('chamado_funcionarios', $dados, $idDecryp);
        if ($edita->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Funcionário / Material',
                'texto' => 'Informações editadas com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/nota_cadastro&id=' . MainModel::encryption($idChamado)
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/nota_cadastro&id=' . MainModel::encryption($idChamado)
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function excluiFuncionarioChamado()
    {
        $id = $_POST['id'];
        $idChamado = $_POST['idChamado'];
        $exclui = DbModel::deleteEspecial("chamado_funcionarios", "id", $id);
        if ($exclui->rowCount() >= 1 || DbModel::connection()->errorCode() == 0) {
            $alerta = [
                'alerta' => 'sucesso',
                'titulo' => 'Funcionário / Material',
                'texto' => 'Funcionário removido com sucesso!',
                'tipo' => 'success',
                'location' => SERVERURL . 'administrador/nota_cadastro&id=' . MainModel::encryption($idChamado)
            ];
        } else {
            $alerta = [
                'alerta' => 'simples',
                'titulo' => 'Erro!',
                'texto' => 'Erro ao salvar!',
                'tipo' => 'error',
                'location' => SERVERURL . 'administrador/nota_cadastro&id=' . MainModel::encryption($idChamado)
            ];
        }
        return MainModel::sweetAlert($alerta);
    }

    public function listaFuncionarioChamado($idChamado)
    {
        $idChamado = MainModel::decryption($idChamado);
        return DbModel::consultaSimples("
            SELECT f.nome, f.cargo, cf.ferramentas, cf.id, f.id AS 'funcionario_id' FROM chamado_funcionarios cf 
                INNER JOIN funcionarios f on cf.funcionario_id = f.id
            WHERE cf.chamado_id = '$idChamado'
        ")->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaFuncionarioChamado($id)
    {
        $id = MainModel::decryption($id);
        return DbModel::consultaSimples("
            SELECT f.nome, f.cargo, cf.ferramentas, cf.id FROM chamado_funcionarios cf 
                INNER JOIN funcionarios f on cf.funcionario_id = f.id
            WHERE cf.id = '$id'
        ")->fetchObject();
    }

    public function relatorioChamadosMes()
    {
        $mes = date('m');
        $ano = date('Y');
        $ultimoDia = date("t", mktime(0, 0, 0, $mes, '01', $ano));
        $dataInicio = "$ano-$mes-01";
        $dataFim = "$ano-$mes-$ultimoDia";

        $query = "SELECT COUNT(ch.status_id) AS 'quantidade', cs.`status`
                    FROM chamado_status AS cs
                    INNER JOIN chamados AS ch ON cs.id = ch.status_id
                    WHERE DATE(ch.data_abertura) BETWEEN '{$dataInicio}' AND '{$dataFim}'
                    GROUP BY ch.status_id";
        return DbModel::consultaSimples($query)->fetchAll(PDO::FETCH_OBJ);
    }

    public function listaCategorias()
    {
        $query = "SELECT id, categoria FROM categorias WHERE publicado = 1";
        return DbModel::consultaSimples($query)->fetchAll(PDO::FETCH_OBJ);
    }

    public function recuperaEstatisticaAdm()
    {
        $dados = array();
        for ($x = 1; $x < 4; $x++) {
            $query = "SELECT COUNT(id) as `contador` FROM chamados WHERE status_id = {$x}";
            $resultado = DbModel::consultaSimples($query)->fetchObject()->contador;
            array_push($dados, $resultado);
        }
        return $dados;
    }

    public function recuperaEstatisticaUsuario($id)
    {
        $dados = array();
        for ($x = 1; $x < 4; $x++) {
            $query = "SELECT COUNT(id) as `contador` FROM chamados WHERE status_id = {$x} AND usuario_id = {$id}";
            $resultado = DbModel::consultaSimples($query)->fetchObject()->contador;
            array_push($dados, $resultado);
        }
        return $dados;
    }

    public function recuperaEstatisticaCategoria($id)
    {
        $dados = array();
        array_push($dados, 0);
        for ($x = 1; $x < 4; $x++) {
            $query = "SELECT COUNT(ch.id) as `contador`
                    FROM chamados AS ch 
                    LEFT JOIN chamado_status AS cs ON ch.status_id = cs.id
                    WHERE ch.categoria_id = {$id} AND ch.status_id = {$x}";
            $resultado = DbModel::consultaSimples($query)->fetchObject()->contador;
            array_push($dados, $resultado);
            $dados[0] = $dados[0] + $resultado;
        }

        return $dados;
    }

    public function recuperaChamadoFuncionario($id)
    {
        $id = MainModel::decryption($id);
        $query = "SELECT * FROM chamado_funcionarios WHERE chamado_id = {$id}";
        $resultado = DbModel::consultaSimples($query)->fetchObject();

        return $resultado;
    }
}