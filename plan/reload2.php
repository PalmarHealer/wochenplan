<?php
$include_path = __DIR__ . "/..";
require_once $include_path . "/dependencies/config.php";
require_once  $include_path . "/dependencies/mysql.php";
require_once  $include_path . "/dependencies/framework.php";

$current_day = $_POST['date'];
?>
<table class="full tg">
    <colgroup>
        <col class="lesson_signs"/>
        <col/>
        <col/>
        <col class="lesson_signs"/>
        <col/>
        <col/>
        <col/>
        <col/>
        <col/>
        <col/>
    </colgroup>
    <thead>
    <tr class="small_piece center">
        <th class="color-3 no_border">
            <b class="white_text modt">
                <?php
                $weekday = (new DateTime($current_day))->format('N');
                echo $weekday_names[$weekday] . " " . date('d.m.Y', strtotime($current_day));
                ?>
            </b>
        </th>
        <th class="color-1 text-left" colspan="4"><?php PrintInfo($current_day, 13, 10, $pdo); ?></th>
        <th class="color-3 white_text modt text-left" colspan="5">
            <?php

            $names = array();
            foreach (GetAllSickNotesRaw($pdo) as &$sickNote) {
                    $dates = array();
                    $dates[1] = $sickNote['start_date'];
                    $dates[2] = $sickNote['end_date'];

                    if (IsDateBetween($dates, $current_day)) {
                        if (!in_array($sickNote['vorname'], $names)) {
                            $names[] = $sickNote['vorname'];
                        }
                    }

            }
            foreach ($names as $key => $name) {
                echo $name;
                if ($key != count($names)-1) {
                    echo ", ";
                }
            }
            ?>
        </th>
    </tr>
    </thead>
    <tbody>
    <tr class="name-badge center small_piece">
        <td class="color-6 db_text"><b class="bold">Zeiten l/ll</b></td>
        <td class="color-1 db_text"><b class="bold">Raum 1</b></td>
        <td class="color-1 db_text"><b class="bold">Freiarbeit</b></td>
        <td class="color-6 db_text"><b class="bold">Zeiten ll-lV</b></td>
        <td class="color-1 db_text"><b class="bold">Raum 2</b></td>
        <td class="color-1 db_text"><b class="bold">Raum 3 (HS)</b></td>
        <td class="color-1 db_text"><b class="bold">Raum 4 (RS)</b></td>
        <td class="color-1 db_text"><b class="bold">Gesprächsraum</b></td>
        <td class="color-1 db_text"><b class="bold">SZ/Praxisber.</b></td>
        <td class="color-1 db_text"><b class="bold">Sport</b></td>
    </tr>




    <tr class="small_piece">

        <td class="color-6 no_border">
            8:00 – 9:00<br />
            <b class="bold">Morgenband</b>
        </td>
        <td class="color-2 db_text" colspan="2"></td>

        <td class="color-6 no_border">
            8:00 – 9:00<br />
            <b class="bold">Morgenband</b>
        </td>

        <td class="color-2 db_text text-center" colspan="6"><?php PrintInfo($current_day, 1, 10, $pdo); ?></td>
    </tr>
    <tr class="macro_piece">

        <td class="color-6 no_border">
            9:00 – 9:30<br />
            <b class="bold">Morgenkreise</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 2, 1, $pdo); ?></td>
        <td class="color-1 db_text"><?php PrintInfo($current_day, 2, 9, $pdo); ?></td>

        <td class="color-6 no_border" rowspan="2">
            9:00 - 10:00<br/>
            <b class="bold">Offene Räume</b>
        </td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 6, 2, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 6, 3, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 6, 4, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 6, 5, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="5"><?php PrintLessonToPlan($current_day, 13, 6, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="5"><?php PrintLessonToPlan($current_day, 13, 7, $pdo); ?></td>
    </tr>




    <tr class="small_piece">

        <td class="color-6 no_border" rowspan="2">
            9:30 – 10:30<br/>
            <b class="bold">Angebot 1</b>
        </td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 3, 1, $pdo); ?></td>
        <td class="color-1 db_text" rowspan="2"><?php PrintInfo($current_day, 3, 9, $pdo); ?></td>
    </tr>


    <tr class="macro_piece">

        <td class="color-6 no_border">
            10:00 – 10:30<br/>
            <b class="bold">Morgenkreise</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 7, 2, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 7, 3, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 7, 4, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 7, 5, $pdo); ?></td>
    </tr>





    <tr class="macro_piece">

        <td class="color-6 no_border">
            <b class="bold">Räum-Pause </b>
        </td>

        <td class="color-2 db_text"></td>
        <td class="color-1 db_text"></td>

        <td class="color-6 no_border" rowspan="2">
            10:30 – 12:00<br/>
            <b class="bold">Großes Band</b>
        </td>

        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 8, 2, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 8, 3, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 8, 4, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 8, 5, $pdo); ?></td>
    </tr>



    <tr class="small_piece">

        <td class="color-6 no_border">
            10:45 – 11:45<br/>
            <b class="bold">Angebot 2</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 4, 1, $pdo); ?></td>
        <td class="color-1 db_text"><?php PrintInfo($current_day, 4, 9, $pdo); ?></td>
    </tr>



    <tr class="breakfast">

        <td class="color-6 no_border">
            12:00 – 13:00<br/>
            <b class="bold">Mittagspause</b>
        </td>

        <td class="color-4 no_border center2" colspan="9"><b class="bold">Mittagessen</b><br><?php PrintLessonToPlan($current_day, 10, 14, $pdo); ?></td>
    </tr>


    <tr class="small_piece">

        <td class="color-6 no_border">
            13:00 – 14:30<br/>
            <b class="bold">Nachmittagsband</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 1, $pdo); ?></td>
        <td class="color-1 db_text"><?php PrintInfo($current_day, 5, 9, $pdo); ?></td>

        <td class="color-6 no_border">
            13:00 – 14:30<br/>
            <b class="bold">Nachmittagsband</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 2, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 3, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 4, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 5, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 6, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 7, $pdo); ?></td>
    </tr>




    <tr class="small_piece">
        <td class="white-col" rowspan="3"></td>
        <td class="white-col" rowspan="3"></td>
        <td class="white-col" rowspan="3"></td>

        <td class="color-6 no_border" rowspan="2">
            14:30 – 15:00<br/>
            <b class="bold">Putzen</b>
        </td>
        <td class="color-5 text-center" colspan="8"><?php PrintInfo($current_day, 12, 10, $pdo); ?></td>
    </tr>

    <tr class="small_piece">
        <td class="color-5 text-center" colspan="2"><?php PrintInfo($current_day, 12, 11, $pdo); ?></td>
        <td class="color-5 text-center" colspan="2"><?php PrintInfo($current_day, 12, 12, $pdo); ?></td>
        <td class="color-5 text-center" colspan="2"><?php PrintInfo($current_day, 12, 13, $pdo); ?></td>
    </tr>




    <tr class="piece">
        <td class="color-6 no_border">
            15:00 – 16:00<br/>
            <b class="bold">Spätes Band</b>
        </td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 2, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 3, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 4, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 5, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 6, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 7, $pdo); ?></td>
    </tr>
    </tbody>
</table>