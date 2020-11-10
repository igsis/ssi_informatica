<?php
/** @noinspection PhpUndefinedVariableInspection */
if ($pedidoAjax) {
    require_once "../models/MainModel.php";
    require_once "../controllers/UsuarioController.php";
    require_once "../controllers/ArquivoController.php";
} else {
    require_once "./models/MainModel.php";
    require_once "./controllers/UsuarioController.php";
    require_once "./controllers/ArquivoController.php";
}

class ChamadoController extends MainModel
{
    public function insereChamado()
    {
        /* executa limpeza nos campos */
        $dados = [];
        $pagina = $_POST['pagina'];
        if ($pagina == "administrador"){
            $idUsuario = $_POST['usuario_id'];
            $usuario = DbModel::getInfo('usuarios',$idUsuario)->fetch(PDO::FETCH_OBJ);
            $dados['local_id'] = $usuario->local_id;
        }
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
            /* envio de arquivo*/
            $arquivoObj = new ArquivoController();
            $enviarArquivo = $arquivoObj->enviarArquivo($arquivoObj->encryption($id),$pagina);
            /* ./envio de arquivo */
            if ($enviarArquivo){
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

        foreach ($dados as $key => $valor) {
            if ($valor == "NULL") {
                $dados[$key] = null;
            }
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
            SELECT ch.*, c.categoria, l.local, cs.status, u.nome as tecnico FROM chamados ch 
                INNER JOIN categorias c on ch.categoria_id = c.id
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN chamado_status cs on ch.status_id = cs.id
                LEFT JOIN usuarios u on ch.tecnico_id = u.id
            WHERE usuario_id = '$idUsuario' ORDER BY status_id, id")->fetchAll(PDO::FETCH_OBJ);
    }

    public function listaChamadoAdministrador($idAdministrador,$status)
    {
        return MainModel::consultaSimples("
            SELECT ch.*, c.categoria, l.local, i.instituicao, cs.status, uu.nome as usuario, ut.nome as tecnico 
            FROM chamados ch 
                INNER JOIN categorias c on ch.categoria_id = c.id
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN chamado_status cs on ch.status_id = cs.id
                INNER JOIN instituicoes i on l.instituicao_id = i.id
                INNER JOIN usuarios uu on ch.usuario_id = uu.id
                LEFT JOIN usuarios ut on ch.tecnico_id = ut.id
            WHERE status_id IN ($status) AND ch.administrador_id = '$idAdministrador'")->fetchAll(PDO::FETCH_OBJ);
    }

    /*public function listaChamadoTecnico($idTecnico,$status)
    {
        //$instituicao = MainModel::consultaSimples("SELECT instituicao_id FROM usuarios WHERE id = '$idTecnico'")->fetchColumn();
        return MainModel::consultaSimples("
            SELECT ch.*, c.categoria, l.local, cs.status, uu.nome as usuario, ut.nome as tecnico 
            FROM chamados ch 
                INNER JOIN categorias c on ch.categoria_id = c.id
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN instituicoes i2 on l.instituicao_id = i2.id
                INNER JOIN chamado_status cs on ch.status_id = cs.id
                INNER JOIN usuarios uu on ch.usuario_id = uu.id
                LEFT JOIN usuarios ut on ch.tecnico_id = ut.id
                LEFT JOIN tecnico_instituicao ti on i2.id = ti.instituicao_id
            WHERE status_id IN ($status) AND (ch.tecnico_id = '$idTecnico' OR ch.tecnico_id IS NULL) AND ti.instituicao_id = '$instituicao'")->fetchAll(PDO::FETCH_OBJ);
    }*/

    public function listaChamadoTecnico($idTecnico,$status)
    {
        return MainModel::consultaSimples("
            SELECT ch.*, c.categoria, l.local,ich.instituicao, cs.status, uu.nome as usuario, ut.nome as tecnico 
            FROM chamados ch    
                INNER JOIN categorias c on ch.categoria_id = c.id
                INNER JOIN usuarios uu on ch.usuario_id = uu.id
                INNER JOIN chamado_status cs on ch.status_id = cs.id
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN instituicoes ich on l.instituicao_id = ich.id
                LEFT JOIN usuarios ut on ch.tecnico_id = ut.id
                LEFT JOIN tecnico_instituicao ti on ich.id = ti.instituicao_id
            WHERE status_id IN ($status) AND ti.tecnico_id = '$idTecnico'")->fetchAll(PDO::FETCH_OBJ);
    }

    public function listaTecnicoUnidade($unidade)
    {
        return DbModel::consultaSimples("SELECT id, nome FROM tecnico_instituicao ti INNER JOIN usuarios u on ti.tecnico_id = u.id WHERE ti.instituicao_id = '$unidade'")->fetchAll(PDO::FETCH_OBJ);
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
        return DbModel::consultaSimples("
            SELECT ch.*, c.categoria, l.local, l.instituicao_id, cs.status, uu.nome as usuario, uu.usuario as login, ut.nome as tecnico, uu.telefone, uu.email1 
            FROM chamados ch 
                INNER JOIN categorias c on ch.categoria_id = c.id
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN chamado_status cs on ch.status_id = cs.id
                INNER JOIN usuarios uu on ch.usuario_id = uu.id
                LEFT JOIN usuarios ut on ch.tecnico_id = ut.id    
            WHERE ch.id = '$id'
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

    public function recuperaEstatisticaTecnico($idTecnico,$status)
    {
        return MainModel::consultaSimples("
            SELECT COUNT(ch.id) as contador
            FROM chamados ch    
                INNER JOIN locais l on ch.local_id = l.id
                INNER JOIN instituicoes i2 on l.instituicao_id = i2.id
                LEFT JOIN usuarios ut on ch.tecnico_id = ut.id
                LEFT JOIN tecnico_instituicao ti on i2.id = ti.instituicao_id
            WHERE status_id IN ($status) AND (ch.tecnico_id = '$idTecnico' OR (ch.tecnico_id IS NULL AND ti.tecnico_id = '$idTecnico'))")->fetchColumn();
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

}