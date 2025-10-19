<?php

require __DIR__.'/../vendor/autoload.php';
require __DIR__.'/../bootstrap/app.php';

use Illuminate\Support\Facades\DB;

DB::statement('ALTER TABLE users MODIFY avatar TEXT;');

echo "Avatar column modified successfully!\n";