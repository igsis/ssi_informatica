<?php
$pedidoAjax = true;
date_default_timezone_set('America/Sao_Paulo');
session_start();

// INSTALAÇÃO DA CLASSE NA PASTA FPDF.
require_once "../views/plugins/fpdf/fpdf.php";
// ACESSO AO BANCO
require_once "../config/configAPP.php";

$id = $_GET['id'];
$idChFunc = $_GET['chfunc'];

require_once "../controllers/ChamadoController.php";
$chamadoObj = new ChamadoController();
$chamado = $chamadoObj->recuperaChamado($id);
$funcionario = $chamadoObj->recuperaFuncionarioChamado($idChFunc);

require_once "../controllers/LocalController.php";
$localObj = new LocalController();
$local = $localObj->recuperaLocal($localObj->encryption($chamado->local_id))->fetchObject();

class PDF extends FPDF
{
     // Page header
    function Header()
    {
       // Move to the right
       $this->Cell(80);
       $this->Image('img/logo_cultura.jpg',170,10);
       // Line break
       $this->Ln(20);
    }
}

// GERANDO O PDF:
$pdf = new PDF('P','mm','A4'); //CRIA UM NOVO ARQUIVO PDF NO TAMANHO A4
$pdf->AliasNbPages();
$pdf->AddPage();

$x=10;
$l=5; //DEFINE A ALTURA DA LINHA   

$pdf->SetXY( $x , 10 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÃGINA

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,4,utf8_decode('PREFEITURA DO MUNICÍPIO DE SÃO PAULO'),0,1,'C');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,4,utf8_decode('SECRETARIA MUNICIPAL DE CULTURA'),0,1,'C');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,4,utf8_decode("ORDEM DE SERVIÇO"),0,1,'C');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(16,$l,utf8_decode('Número:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode("$chamado->id"),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(10,$l,utf8_decode('Data:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(45,$l,utf8_decode(date('d/m/Y H:i:s', strtotime($chamado->data_abertura))),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(16,$l,utf8_decode('Contato:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(43,$l,utf8_decode("$chamado->contato"),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(17,$l,utf8_decode('Telefone:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode($chamado->telefone),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,utf8_decode('Local:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(74,$l,utf8_decode($chamado->local),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(18,$l,utf8_decode('Endereço:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(65,$l,utf8_decode($local->logradouro.", ".$local->numero),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(28,$l,utf8_decode('Tipo de Serviço:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(58,$l,utf8_decode($chamado->categoria),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(22,$l,utf8_decode('Funcionário:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode($funcionario->nome),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(140,5,utf8_decode('Visto:'),0,1,'R');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(40,5,utf8_decode('Ferramentas/Materiais:'),0,1,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(190,$l,utf8_decode($funcionario->ferramentas));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(41,5,utf8_decode('Descrição dos serviços:'),0,1,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(190,$l,utf8_decode($chamado->descricao));

$pdf->Ln();

$pdf->SetXY($x,130);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(36,$l,utf8_decode('Horário de chegada:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode("___:___"),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(30,$l,utf8_decode('Horário de saída:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode("___:___"),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(50,$l,utf8_decode('Nome e carimbo do servidor:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode("____________________"),0,0,'L');

$pdf->Ln();

$pdf->SetXY($x,145);
$pdf->SetFont('Arial','', 10);
$pdf->Cell(25,$l,utf8_decode('_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _'),0,0,'L');

$pdf->SetXY( $x , 155 );// SetXY - DEFINE O X (largura) E O Y (altura) NA PÁGINA

$pdf->Image('img/logo_cultura.jpg',170,156);

$pdf->SetXY($x,155);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,4,utf8_decode('PREFEITURA DO MUNICÍPIO DE SÃO PAULO'),0,1,'C');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,4,utf8_decode('SECRETARIA MUNICIPAL DE CULTURA'),0,1,'C');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(180,4,utf8_decode("ORDEM DE SERVIÇO"),0,1,'C');

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(16,$l,utf8_decode('Número:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode("$chamado->id"),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(10,$l,utf8_decode('Data:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(45,$l,utf8_decode(date('d/m/Y H:i:s', strtotime($chamado->data_abertura))),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(16,$l,utf8_decode('Contato:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(43,$l,utf8_decode("$chamado->contato"),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(17,$l,utf8_decode('Telefone:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode($chamado->telefone),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(12,$l,utf8_decode('Local:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(74,$l,utf8_decode($chamado->local),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(18,$l,utf8_decode('Endereço:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(65,$l,utf8_decode($local->logradouro.", ".$local->numero),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(28,$l,utf8_decode('Tipo de Serviço:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(58,$l,utf8_decode($chamado->categoria),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(22,$l,utf8_decode('Funcionário:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode($funcionario->nome),0,1,'L');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(140,5,utf8_decode('Visto:'),0,1,'R');

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(40,$l,utf8_decode('Ferramentas/Materiais:'),0,1,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(190,$l,utf8_decode($funcionario->ferramentas));

$pdf->Ln();

$pdf->SetX($x);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(41,5,utf8_decode('Descrição dos serviços:'),0,1,'L');
$pdf->SetFont('Arial','', 10);
$pdf->MultiCell(190,5,utf8_decode($chamado->descricao));

$pdf->Ln();

$pdf->SetXY($x,271);
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(36,$l,utf8_decode('Horário de chegada:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode("___:___"),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(30,$l,utf8_decode('Horário de saída:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode("___:___"),0,0,'L');
$pdf->SetFont('Arial','B', 10);
$pdf->Cell(50,$l,utf8_decode('Nome e carimbo do servidor:'),0,0,'L');
$pdf->SetFont('Arial','', 10);
$pdf->Cell(15,$l,utf8_decode("____________________"),0,0,'L');

$pdf->Output();