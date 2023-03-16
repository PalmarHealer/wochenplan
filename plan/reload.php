<?php
$include_path = __DIR__ . "/..";
require_once $include_path . "/dependencies/config.php";
require_once  $include_path . "/dependencies/mysql.php";
require_once  $include_path . "/dependencies/framework.php";

$current_day = $_POST['date'];
?>
<table class="full tg">
    <colgroup>
        <col class="piece"/>
        <col/>
        <col/>
        <col/>
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
                echo $weekday_names[$weekday];
                ?>
            </b>
        </th>
        <th class="color-1" colspan="4"></th>
        <th class="color-3" colspan="5"></th>
    </tr>
    </thead>
    <tbody>
    <tr class="name-badge center">
        <td class="color-3 no_border"><b class="white_text heading"><?php echo date('d.m.Y', strtotime($current_day)); ?></b></td>
        <td class="color-2 no_border"><b class="bold">Raum 1</b></td>
        <td class="color-2 no_border"><b class="bold">Raum 2</b></td>
        <td class="color-2 no_border"><b class="bold">Raum 3 (HS)</b></td>
        <td class="color-2 no_border"><b class="bold">Raum 4 (RS)</b></td>
        <td class="color-2 no_border">Gesprächsraum</td>
        <td class="color-2 no_border">SZ/Praxisber.</td>
        <td class="color-2 no_border">Sport</td>
        <td class="color-2 no_border">Ausflug</td>
        <td class="color-1 no_border">Freiarbeit</td>
    </tr>
    <tr class="small_piece">
        <td class="color-1 no_border">
            8:00 – 9:00<br />
            <b class="bold">Morgenband</b>
        </td>
        <td class="color-2 no_border center" colspan="8"><p>Ankommen</p></td>
        <td class="color-1"></td>
    </tr>
    <tr class="piece">
        <td class="color-1 no_border">
            9:00 - 10:00<br />
            <b class="bold">Morgenband</b>
        </td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 1, 1, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 1, 2, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 1, 3, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 1, 4, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 1, 5, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 1, 6, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="3"><?php PrintLessonToPlan($current_day, 1, 7, $pdo); ?></td>
        <td class="color-2 db_text" rowspan="3"><?php PrintLessonToPlan($current_day, 1, 8, $pdo); ?></td>
        <td class="color-1 db_text"><?php PrintLessonToPlan($current_day, 1, 9, $pdo); ?></td>
    </tr>
    <tr class="piece">
        <td class="color-1 no_border">
            10:00 – 10:30<br/>
            <b class="bold">Morgenkreise</b>
        </td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 2, 1, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 2, 2, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 2, 3, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 2, 4, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 2, 5, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 2, 6, $pdo); ?></td>
        <td class="color-1 db_text"><?php PrintLessonToPlan($current_day, 2, 9, $pdo); ?></td>
    </tr>
    <tr>
        <td class="color-1 no_border">
            10:30 – 12:00<br/>
            <b class="bold">Großes Band</b>
        </td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 3, 1, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 3, 2, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 3, 3, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 3, 4, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 3, 5, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 3, 6, $pdo); ?></td>
        <td class="color-1 db_text"><?php PrintLessonToPlan($current_day, 3, 9, $pdo); ?></td>
    </tr>
    <tr class="breakfast">
        <td class="color-1 no_border">
            12:00 – 13:00<br/>
            <b class="bold">Mittagspause</b>
        </td>
        <td class="color-4 no_border" colspan="8"><b class="bold">Mittagessen</b> </td>
        <td class="color-1"></td>
    </tr>
    <tr>
        <td class="color-1 no_border">
            13:00 – 14:30<br/>
            <b class="bold">Nachmittags-<br/>
                band</b>
        </td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 1, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 2, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 3, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 4, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 5, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 6, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 7, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 8, $pdo); ?></td>
        <td class="color-1 db_text"><?php PrintLessonToPlan($current_day, 5, 9, $pdo); ?></td>
    </tr>
    <tr class="small_piece">
        <td class="color-1 no_border" rowspan="2">
            14:30 – 15:00<br/>
            <b class="bold">Putzen</b>
        </td>
        <td class="color-5" colspan="8"></td>
        <td class="color-1"></td>
    </tr>
    <tr class="small_piece">
        <td class="color-5 db_text" colspan="2"></td>
        <td class="color-5 db_text" colspan="3"></td>
        <td class="color-5 db_text" colspan="3"></td>
        <td class="color-1 db_text"></td>
    </tr>
    <tr class="piece">
        <td class="color-1 no_border">
            15:00 – 16:00<br/>
            <b class="bold">Spätes Band</b>
        </td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 6, 1, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 6, 2, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 6, 3, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 6, 4, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 6, 5, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 6, 6, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 6, 7, $pdo); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 6, 8, $pdo); ?></td>
        <td class="color-1 db_text"><?php PrintLessonToPlan($current_day, 6, 9, $pdo); ?></td>
    </tr>
    </tbody>
</table>