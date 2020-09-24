<?php
session_start(['name' => 'ssi']);
session_destroy();
echo '<script> window.location.href="'. SERVERURL .'" </script>';