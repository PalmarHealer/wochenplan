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
        <td class="color-3 db_text"><b class="white_text heading"><?php echo date('d.m.Y', strtotime($current_day)); ?></b></td>
        <td class="color-1 db_text"><b class="bold">Raum 1</b></td>
        <td class="color-1 db_text"><b class="bold">Freiarbeit</b></td>
        <td class="color-6 db_text"><b class="bold">Zeiten ll/lV</b></td>
        <td class="color-1 db_text"><b class="bold">Raum 3 (HS)</b></td>
        <td class="color-1 db_text"><b class="bold">Raum 4 (RS)</b></td>
        <td class="color-1 db_text"><b class="bold">Gesprächsraum</b></td>
        <td class="color-1 db_text"><b class="bold">SZ/Praxisber.</b></td>
        <td class="color-1 db_text"><b class="bold">Sport</b></td>
        <td class="color-1 db_text"><b class="bold">Ausflug</b></td>
    </tr>




    <tr class="small_piece">
        <td class="color-1 no_border">
            8:00 – 9:00<br />
            <b class="bold">Morgenband</b>
        </td>
        <td class="color-2 no_border center" colspan="9"><p>Ankommen</p></td>
    </tr>
    <tr class="macro_piece">
        <td class="color-1 no_border">
            9:00 – 9:30<br />
            <b class="bold">Morgenkreise</b>
        </td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-1 db_text">Test contend</td>
        <td class="color-6 no_border" rowspan="2">
            9:00 - 10:00<br/>
            <b class="bold">Offene Räume</b>
        </td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
        <td class="color-2 db_text" rowspan="5">Test contend</td>
        <td class="color-2 db_text" rowspan="5">Test contend</td>
    </tr>







    <tr class="small_piece">
        <td class="color-1 no_border" rowspan="2">
            9:30 – 10:30<br/>
            <b class="bold">Angebot 1</b>
        </td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
        <td class="color-1 db_text" rowspan="2">Test contend</td>
    </tr>


    <tr class="macro_piece">
        <td class="color-6 no_border">
            10:00 – 10:30<br/>
            <b class="bold">Morgenkreise</b>
        </td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
    </tr>





    <tr class="macro_piece">
        <td class="color-1 no_border">
            <b class="bold">Räum-Pause </b>
        </td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-1 db_text">Test contend</td>
        <td class="color-6 no_border" rowspan="2">
            10:30 – 12:00<br/>
            <b class="bold">Großes Band</b>
        </td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
        <td class="color-2 db_text" rowspan="2">Test contend</td>
    </tr>



    <tr class="small_piece">
        <td class="color-1 no_border">
            10:45 – 11:45<br/>
            <b class="bold">Angebot 2</b>
        </td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-1 db_text">Test contend</td>
    </tr>



    <tr class="breakfast">
        <td class="color-1 no_border">
            12:00 – 13:00<br/>
            <b class="bold">Mittagspause</b>
        </td>
        <td class="color-4 no_border center2" colspan="9"><b class="bold">Mittagessen</b> </td>
    </tr>


    <tr class="small_piece">
        <td class="color-1 no_border">
            13:00 – 14:30<br/>
            <b class="bold">Nachmittags-<br/>
                band</b>
        </td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-1 db_text">Test contend</td>
        <td class="color-6 no_border">
            13:00 – 14:30<br/>
            <b class="bold">Nachmittags Band</b>
        </td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
    </tr>




    <tr class="small_piece">
        <td class="white-col" rowspan="3"></td>
        <td class="white-col" rowspan="3"></td>
        <td class="white-col" rowspan="3"></td>
        <td class="color-6 no_border" rowspan="2">
            14:30 – 15:00<br/>
            <b class="bold">Putzen</b>
        </td>
        <td class="color-5" colspan="8"></td>
    </tr>

    <tr class="small_piece">
        <td class="color-5" colspan="2"></td>
        <td class="color-5" colspan="2"></td>
        <td class="color-5" colspan="2"></td>
    </tr>




    <tr class="piece">
        <td class="color-6 no_border">
            15:00 – 16:00<br/>
            <b class="bold">Spätes Band</b>
        </td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
        <td class="color-2 db_text">Test contend</td>
    </tr>
    </tbody>
</table>