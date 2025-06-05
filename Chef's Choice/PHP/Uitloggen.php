<?php
session_start();
session_unset();
session_destroy();
header('Location: ../Index.php?logout=1');
exit;
