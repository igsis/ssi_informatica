<?php
define('SERVERURL', "http://{$_SERVER['HTTP_HOST']}/ssi/");
define('NOMESIS', "SSI - Infraestrutura");
date_default_timezone_set('America/Sao_Paulo');
//ini_set('session.gc_maxlifetime', 60*60); // 60 minutos?
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
    // last request was more than 60 minutes ago
    session_unset();     // unset $_SESSION variable for the run-time
    session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp