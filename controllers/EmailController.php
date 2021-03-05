<?php

if ($pedidoAjax) {
    require_once "../models/RecuperaSenhaModel.php";
    require_once "../controllers/UsuarioController.php";
    require_once "../plugins/phpmailer/src/PHPMailer.php";
    require_once "../plugins/phpmailer/src/SMTP.php";
    require_once "../plugins/phpmailer/src/PHPMailer.php";
} else {
    require_once "./models/RecuperaSenhaModel.php";
    require_once "./controllers/UsuarioController.php";
    require_once "./plugins/phpmailer/src/PHPMailer.php";
    require_once "./plugins/phpmailer/src/SMTP.php";
    require_once "./plugins/phpmailer/src/PHPMailer.php";
}


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class EmailController extends RecuperaSenhaModel
{
    public function verificaEmail($email)
    {
        $usuario = new UsuarioController();
        $emailBD = $usuario->recuperaEmail($email);

        if ($emailBD->rowCount() == 1) {

            $token = $this->gerarToken();

            $dados = array(
                'email' => $email,
                'token' => $token,
            );

            if ($this->setToken($email, $token)) {
                $this->enviarEmail($email, $token);
                $alert = [
                    'alerta' => 'simples',
                    'titulo' => 'Resete enviado por e-mail',
                    'texto' => "Enviamos um email para <b>$email</b> para a reiniciarmos sua senha. <br> Por favor acesse seu email e clique no link recebido para cadastrar uma nova senha! (Lembre-se de verificar o spam)",
                    'tipo' => 'success',
                ];
            } else {
                $alert = [
                    'alerta' => 'simples',
                    'titulo' => 'Erro',
                    'texto' => "Erro ao tentar enviar e-mail, por favor tente novamente.",
                    'tipo' => 'error',
                ];
            }
        } else {
            $alert = [
                'alerta' => 'simples',
                'titulo' => 'Não tem email',
                'texto' => "E-mail não encontrado em nossa base de dados.",
                'tipo' => 'error',
            ];
        }

        return MainModel::sweetAlert($alert);
    }

    public function validarEmail($email)
    {
        $token = $this->gerarToken();

        return $this->enviarEmail($email, $token,false);

    }

    private function gerarToken()
    {
        return MainModel::encryption(random_bytes(50));
    }

    public function geraEmail($token, $resetSenha)
    {
        if ($resetSenha) {
            $endereco = SERVERURL . "resete_senha&tk=" . $token;
            $html = "<!DOCTYPE html>
        <html style=\"padding: 0px; margin: 0px;\" lang=\"pt_br\">
           <head> 
               <meta charset=\"UTF-8\" />
                <style>
                   body{margin:
                        0;padding: 0;
                   }
                   @media only screen and (max-width:640px){
                       table, img[class=\"partial-image\"]{
                            width:100% !important;
                            height:auto !important;
                            min-width: 200px !important; 
                   }
              </style>
           </head>
        <body>
        <table style=\"border-collapse: collapse; border-spacing:
           0; min-height: 418px;\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" bgcolor=\"#f2f2f2\">
           <tbody>
              <tr>
                 <td align=\"center\" style=\"border-collapse: collapse;
                    padding-top: 30px; padding-bottom: 30px;\">
                    <table cellpadding=\"5\" cellspacing=\"5\" width=\"600\" bgcolor=\"white\" style=\"border-collapse: collapse;
                       border-spacing: 0;\">
                       <tbody>
                          <tr>
                             <td style=\"border-collapse: collapse; padding: 0px;
                                text-align: center; width: 600px;\">
                                <table style=\"border-collapse: collapse;
                                   border-spacing: 0; box-sizing: border-box;
                                   min-height: 40px; position: relative; width: 100%;
                                   font-family: Arial; font-size: 25px;
                                   padding-bottom: 20px; padding-top: 20px;
                                   text-align: center; vertical-align:
                                   middle;\">
                                   <tbody>
                                      <tr>
                                         <td style=\"border-collapse: collapse; font-family:
                                            Arial; padding: 10px 15px;\">
                                            <table width=\"100%\" style=\"border-collapse: collapse; border-spacing:
                                               0; font-family: Arial;\">
                                               <tbody>
                                                  <tr>
                                                     <td style=\"border-collapse: collapse;\">
                                                        <h2 style=\"font-weight: normal; margin: 0px; padding:
                                                           0px; color: #666; word-wrap: break-word;\"><a style=\"display: inline-block; text-decoration:
                                                           none; box-sizing: border-box; font-family: arial;
                                                           width: 100%; font-size: 25px; text-align: center;
                                                           word-wrap: break-word; color: rgb(102,102,102);
                                                           cursor: text;\" target=\"_blank\"><span style=\"font-size: inherit; text-align: center;
                                                           width: 100%; color: #666;\">Olá!</span></a>
                                                        </h2>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                   </tbody>
                                </table>
                                <table style=\"border-collapse: collapse;
                                   border-spacing: 0; box-sizing: border-box;
                                   min-height: 40px; position: relative; width:
                                   100%;\">
                                   <tbody>
                                      <tr>
                                         <td style=\"border-collapse:
                                            collapse; font-family: Arial; padding: 10px
                                            15px;\">
                                            <table width=\"100%\" style=\"border-collapse: collapse; border-spacing:
                                               0; text-align: left; font-family:
                                               Arial;\">
                                               <tbody>
                                                  <tr>
                                                     <td style=\"border-collapse:
                                                        collapse;\">
                                                        <div style=\"font-family: Arial;
                                                           font-size: 15px; font-weight: normal; line-height:
                                                           170%; text-align: left; color: #666; word-wrap:
                                                           break-word;\">
                                                           <div style=\"text-align:
                                                              center;\">Recebemos sua solicitação de recuperação de senha. Caso tenha solicitado, clique no botão abaixo para continuar<span style=\"line-height: 0;
                                                                 display: none;\"></span><span style=\"line-height:
                                                                 0; display:
                                                                 none;\"></span>.
                                                           </div>
                                                        </div>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                   </tbody>
                                </table>
                                <table style=\"border-collapse: collapse;
                                   border-spacing: 0; box-sizing: border-box;
                                   min-height: 40px; position: relative; width: 100%;
                                   padding-bottom: 10px; padding-top: 10px;
                                   text-align: center;\">
                                   <tbody>
                                      <tr>
                                         <td style=\"border-collapse: collapse; font-family:
                                            Arial; padding: 10px 15px;\">
                                            <div style=\"font-family: Arial; text-align:
                                               center;\">
                                               <table style=\"border-collapse:
                                                  collapse; border-spacing: 0; background-color:
                                                  rgb(0,123,255); border-radius: 10px; color:
                                                  rgb(255,255,255); display: inline-block;
                                                  font-family: Arial; font-size: 15px; font-weight:
                                                  bold; text-align: center;\">
                                                  <tbody style=\"display:
                                                     inline-block;\">
                                                     <tr style=\"display:
                                                        inline-block;\">
                                                        <td align=\"center\" style=\"border-collapse: collapse; display:
                                                           inline-block; padding: 15px 20px;\"><a target=\"_blank\" href='" . $endereco . "' style=\"display: inline-block;
                                                           text-decoration: none; box-sizing: border-box;
                                                           font-family: arial; color: #fff; font-size: 15px;
                                                           font-weight: bold; margin: 0px; padding: 0px;
                                                           text-align: center; word-wrap: break-word; width:
                                                           100%; cursor: text;\">Recupere Sua Senha Aqui</a>
                                                        </td>
                                                     </tr>
                                                  </tbody>
                                               </table>
                                            </div>
                                         </td>
                                      </tr>
                                   </tbody>
                                </table>
                                <table style=\"border-collapse: collapse;
                                   border-spacing: 0; box-sizing: border-box;
                                   min-height: 40px; position: relative; width:
                                   100%;\">
                                   <tbody>
                                   <tr>
                                      <td style=\"border-collapse:
                                            collapse; font-family: Arial; padding: 10px
                                            15px;\">
                                         <table width=\"100%\" style=\"border-collapse: collapse; border-spacing:
                                               0; text-align: left; font-family:
                                               Arial;\">
                                            <tbody>
                                            <tr>
                                               <td style=\"border-collapse:
                                                        collapse;\">
                                                  <div style=\"font-family: Arial;
                                                           font-size: 15px; font-weight: normal; line-height:
                                                           170%; text-align: left; color: #666; word-wrap:
                                                           break-word;\">
                                                     <div style=\"text-align:
                                                              center;\">Caso não tenha sido você, apenas ignore este e-mail e sua senha se manterá a mesma.<span style=\"line-height: 0;
                                                                 display: none;\"></span><span style=\"line-height:
                                                                 0; display:
                                                                 none;\"></span>
                                                     </div>
                                                  </div>
                                               </td>
                                            </tr>
                                            </tbody>
                                         </table>
                                      </td>
                                   </tr>
                                   </tbody>
                                </table>
        
                                <table style=\"border-collapse: collapse;
                                   border-spacing: 0; box-sizing: border-box;
                                   min-height: 40px; position: relative; width:
                                   100%;\">
                                   <tbody>
                                      <tr>
                                         <td style=\"border-collapse:
                                            collapse; font-family: Arial; padding: 10px
                                            15px;\">
                                            <table width=\"100%\" style=\"border-collapse: collapse; border-spacing:
                                               0; text-align: left; font-family:
                                               Arial;\">
                                               <tbody>
                                                  <tr>
                                                     <td style=\"border-collapse:
                                                        collapse;\">
                                                        <div style=\"font-family: Arial;
                                                           font-size: 15px; font-weight: normal; line-height:
                                                           170%; text-align: left; color: rgb(120,113,99);
                                                           word-wrap: break-word;\">
                                                           <div style=\"text-align:
                                                              center; color: rgb(120,113,99);\"><span style=\"line-height: 0; display: none; color:
                                                              rgb(120,113,99);\"></span><br/>Atenciosamente,<br/><br/><strong>SMC Sistemas</strong>
                                                           </div>
                                                        </div>
                                                     </td>
                                                  </tr>
                                                  <tr>
                                                     <td style=\"border-collapse:
                                                        collapse;\">
                                                        <div style=\"font-family: Arial;
                                                           font-size: 15px; font-weight: normal; line-height:
                                                           170%; text-align: left; color: rgb(120,113,99);
                                                           word-wrap: break-word;\">
                                                           <div style=\"text-align:
                                                              center; color: rgb(120,113,99);\"><span style=\"line-height: 0; display: none; color:
                                                              rgb(120,113,99);\"></span><br/><hr/><strong>Esta é uma mensagem automática. Por favor, não responda este e-mail.</strong>
                                                           </div>
                                                        </div>
                                                     </td>
                                                  </tr>
                                               </tbody>
                                            </table>
                                         </td>
                                      </tr>
                                   </tbody>
                                </table>
                             </td>
                          </tr>
                       </tbody>
                    </table>
                 </td>
              </tr>
           </tbody>
        </table>
        </body>
        </html>
            ";
        } else {
            $endereco = SERVERURL . "ativar_conta&tk=" . $token;
            $html = "<!doctype html>
                <html lang='pt-br' xmlns='http://www.w3.org/1999/xhtml' xmlns:v='urn:schemas-microsoft-com:vml' xmlns:o='urn:schemas-microsoft-com:office:office'>
                  <head>
                    <title>
                    </title>
                    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1'>
                    <style type='text/css'>
                      #outlook a{padding: 0;}
                                .ReadMsgBody{width: 100%;}
                                .ExternalClass{width: 100%;}
                                .ExternalClass *{line-height: 100%;}
                                body{margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;}
                                table, td{border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;}
                                img{border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
                                p{display: block; margin: 13px 0;}
                    </style>
                    <!--[if !mso]><!-->
                    <style type='text/css'>
            @media only screen and (max-width:480px) {
            @-ms-viewport {width: 320px;}
                                        @viewport {	width: 320px; }
                                    }
                    </style>
                    <!--<![endif]-->
                    <!--[if mso]> 
                        <xml> 
                            <o:OfficeDocumentSettings> 
                                <o:AllowPNG/> 
                                <o:PixelsPerInch>96</o:PixelsPerInch> 
                            </o:OfficeDocumentSettings> 
                        </xml>
                        <![endif]-->
                    <!--[if lte mso 11]> 
                        <style type='text/css'> 
                            .outlook-group-fix{width:100% !important;}
                        </style>
                        <![endif]-->
                    <style type='text/css'>
            @media only screen and (min-width:480px) {
            .dys-column-per-100 {
                width: 100.000000% !important;
                max-width: 100.000000%;
                      }
                      }
                      @media only screen and (min-width:480px) {
            .dys-column-per-100 {
                width: 100.000000% !important;
                max-width: 100.000000%;
                      }
                      }
                    </style>
                  </head>
                  <body>
                    <div>
                      <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='background:#f7f7f7;background-color:#f7f7f7;width:100%;'>
                        <tbody>
                          <tr>
                            <td>
                              <div style='margin:0px auto;max-width:600px;'>
                                <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
                                  <tbody>
                                    <tr>
                                      <td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
                                        <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'><tr><td style='vertical-align:top;width:600px;'>
                <![endif]-->
                                        <div class='dys-column-per-100 outlook-group-fix' style='direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;'>
                                          <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                                            <tr>
                                              <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                                                <div style='color:#4d4d4d;font-family:Oxygen, Helvetica neue, sans-serif;font-size:32px;font-weight:700;line-height:37px;text-align:center;'>
            Bem vindo
        </div>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;'>
                                                <div style='color:#777777;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;line-height:21px;text-align:center;'>
            Para que possamos liberar seu acesso clique no botão abaixo para confirmar seu e-mail e finalizar seu cadastro.
                                                </div>
                                              </td>
                                            </tr>
                                          </table>
                                        </div>
                                        <!--[if mso | IE]>
                </td></tr></table>
                <![endif]-->
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <!--[if mso | IE]>
                <table align='center' border='0' cellpadding='0' cellspacing='0' style='width:600px;' width='600'><tr><td style='line-height:0px;font-size:0px;mso-line-height-rule:exactly;'>
                <![endif]-->
                      <div style='margin:0px auto;max-width:600px;'>
                        <table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='width:100%;'>
                          <tbody>
                            <tr>
                              <td style='direction:ltr;font-size:0px;padding:20px 0;text-align:center;vertical-align:top;'>
                                <!--[if mso | IE]>
                <table role='presentation' border='0' cellpadding='0' cellspacing='0'><tr><td style='vertical-align:top;width:600px;'>
                <![endif]-->
                                <div class='dys-column-per-100 outlook-group-fix' style='direction:ltr;display:inline-block;font-size:13px;text-align:left;vertical-align:top;width:100%;'>
                                  <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='vertical-align:top;' width='100%'>
                                    <tr>
                                      <td align='center' style='font-size:0px;padding:10px 25px;word-break:break-word;' vertical-align='middle'>
                                        <table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:separate;line-height:100%;'>
                                          <tr>
                                            <td align='center' bgcolor='##0069D9' role='presentation' style='background-color:#0069D9;border:none;border-radius:5px;cursor:auto;padding:10px 25px;' valign='middle'>
                                              <a href='{$endereco}' style='background:#0069D9;color:#ffffff;font-family:Oxygen, Helvetica neue, sans-serif;font-size:14px;font-weight:400;line-height:21px;margin:0;text-decoration:none;text-transform:none;' target='_blank'>
            Confirmar e-mail
        </a>
                                            </td>
                                          </tr>
                                        </table>
                                      </td>
                                    </tr>
                                  </table>
                                </div>
                                <!--[if mso | IE]>
                </td></tr></table>
                <![endif]-->
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <!--[if mso | IE]>
                </td></tr></table>
                <![endif]-->
                    </div>
                  </body>
                </html><!--  -->";
        }
        return $html;
    }

    public function novaSenha($senha, $token)
    {
        $query = "SELECT `email` FROM `resete_senhas` WHERE token = '" . $token . "'";
        $resultado = DbModel::consultaSimples($query);
        if ($resultado->rowCount() == 1) {
            $email = $resultado->fetch(PDO::FETCH_COLUMN);
            $dado = array('senha' => MainModel::encryption($senha));
            DbModel::updateEspecial('usuarios', $dado, 'email2', $email);
            if (DbModel::connection()->errorCode() == 0) {
                DbModel::deleteEspecial('resete_senhas', 'token', $token);
                if (DbModel::connection()->errorCode() == 0) {
                    $alert = [
                        'alerta' => 'sucesso',
                        'titulo' => 'Sucesso!',
                        'texto' => 'Senha altera com sucesso!',
                        'tipo' => 'success',
                        'location' => SERVERURL
                    ];
                } else {
                    $alert = $this->erroToken();
                }

            } else {
                $alert = $this->erroToken();
            }
        } else {
            $alert = $this->erroToken('Esse link já foi utilizado para trocar senha.<br>Faça uma nova solicitação para trocar senha.');
        }

        return MainModel::sweetAlert($alert);
    }

    private function erroToken($textErro = 'Erro ao tentar trocar senha. Tente novamente.')
    {
        return [
            'alerta' => 'simples',
            'titulo' => 'Erro',
            'texto' => $textErro,
            'tipo' => 'error',
        ];
    }

    function enviarEmail($destinatario, $token, $resetSenha = true)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->CharSet = "UTF-8";
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = SMTP;
            $mail->Password = SENHASMTP;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

//            DEBUG
//            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
//            $mail->setLanguage('pt');
//            $mail->SMTPDebug = 3;
//            $mail->Debugoutput = 'html';

            $mail->setFrom(SMTP);
            $mail->FromName = "SSI";
            $mail->addReplyTo('no-reply@siscontrat.com.br');
            $mail->addAddress($destinatario);

            $mail->isHTML(true);
            $mail->Subject = "SSI - Boas Vindas";
            $mail->Body = $this->geraEmail($token, $resetSenha);

            if ($mail->send())
                return true;

            MainModel::gravarLog("E-mail não existe: {$mail->ErrorInfo}");
            return false;
        } catch (Exception $ex) {
            MainModel::gravarLog("Erro ao enviar e-mail: {$mail->ErrorInfo}");
            return false;
        }
    }
}