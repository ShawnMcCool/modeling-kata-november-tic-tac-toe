<?php

use function PhAnsi\terminal_cursor_position;
use function PhAnsi\terminal_height;
use function PhAnsi\terminal_width;

require 'vendor/autoload.php';

echo terminal_width();
echo terminal_height();
[$x, $y] = terminal_cursor_position();