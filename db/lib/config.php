<?php

define('ROOT',  dirname(__DIR__,2) . DIRECTORY_SEPARATOR);
define('UPLOAD_DIR', ROOT . 'public' . DIRECTORY_SEPARATOR . 'pic');
define('MAX_FILE_SIZE', 500000);
define('ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/gif']);