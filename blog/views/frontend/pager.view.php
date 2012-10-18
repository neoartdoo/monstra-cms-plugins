<?php
    $neighbours = 6;
    $left_neighbour = $page - $neighbours;
    if ($left_neighbour < 1) $left_neighbour = 1;

    $right_neighbour = $page + $neighbours;
    if ($right_neighbour > $pages) $right_neighbour = $pages;

    if ($page > 1) {
         echo ' <a href="?page=1">begin</a> ... <a href="?page=' . ($page-1) . '">←prev</a> ';
    }

    for ($i=$left_neighbour; $i<=$right_neighbour; $i++) {
        if ($i != $page) {
            echo ' <a href="?page=' . $i . '">' . $i . '</a> ';
        }
        else {
            // выбранная страница
            echo ' <b>' . $i . '</b> ';
        }
    }

    if ($page < $pages) {
        echo  ' <a href="?page=' . ($page+1) . '">next→</a> ... <a href="?page=' . $pages . '">end</a> ';
    }

?>