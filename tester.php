<?php
for ($i = 1; $i <= 30; $i++) {
    if ($i % 14 == 0 && $i % 4 == 0) {
        echo 'Unictive Media<br>';
    } elseif ($i % 4 == 0) {
        echo 'Unictive<br>';
    } else {
        echo $i . '<br>';
    }
}
