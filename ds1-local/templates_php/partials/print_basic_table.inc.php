<?php

    $table_start = "<table class='table table-bordered table-striped'>";
    $table_end = "</table>";

    if ($selected_columns != null) {
        // chci jen vybrane klice
        echo $table_start;

        foreach ($selected_columns as $col) {
            echo "<tr><th>$titles[$col]</th><td>$data[$col]</td></tr>";
        }

        echo $table_end;
    }
    else {
        // TODO chci vse
    }