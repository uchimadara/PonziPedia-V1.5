<?php
require_once 'app/init.php';

Auth::logout();

redirect_to(App::url());