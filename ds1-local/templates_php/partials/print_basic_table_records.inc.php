<?php

    $table_start = "<table class='table table-bordered table-striped'>";
    $table_end = "</table>";


    if ($selected_columns != null) {
        // chci jen vybrane klice
        echo $table_start;

        // prvni radek
        echo "<tr>";

            foreach ($selected_columns as $col) {

                $title_pom = "";
                if (key_exists($col, $titles)) $title_pom = $titles[$col];
                else $title_pom = $col;

                echo "<th>$title_pom</th>";
            }

        echo "</tr>";

        // data
        if ($data != null)
            foreach ($data as $record) {
                // vypsat radek
                echo "<tr>";
                foreach ($selected_columns as $col) {
                    echo "<td>$record[$col]</td>";
                }
                echo "</tr>";
            }

        echo $table_end;
    }
    else {
        // TODO chci vse
    }