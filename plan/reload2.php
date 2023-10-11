<?php
$include_path = __DIR__ . "/..";
require_once $include_path . "/dependencies/config.php";
require_once  $include_path . "/dependencies/mysql.php";
require_once  $include_path . "/dependencies/framework.php";

$current_day = $_POST['date'];
?>
<div class="alert-message col-12 mb-4">

</div>
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
    <tbody>
    <tr class="small_piece tb_header">
        <th class="tb_header color-3 no_border_no_margin text-center" style='text-align: center;'>
            <div class="text-center">
            <b class="white_text modt">
                <?php
                $weekday = (new DateTime($current_day))->format('N');
                echo $weekday_names[$weekday] . " " . date('d.m.Y', strtotime($current_day));
                ?>
            </b>
            </div>
        </th>
        <th class="tb_header color-1 text-left db_text" colspan="5"><?php PrintInfo($current_day, 13, 10, $pdo, $webroot); ?></th>
        <th class="tb_header color-3 no_border_no_margin modt text-left" colspan="5">
            <p class="white_text">
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
            </p>
        </th>
    </tr>
    <tr class="name_badge center small_piece">
        <td class="color-6 db_text rooms"><b class="bold">Zeiten l/ll</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Raum 1</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Freiarbeit</b></td>
        <td class="color-6 db_text rooms"><b class="bold">Zeiten ll-lV</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Raum 2</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Raum 3 (HS)</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Raum 4 (RS)</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Gesprächsraum</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Sonnenzimmer</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Extern</b></td>
        <td class="color-1 db_text rooms"><b class="bold">Ext.</b></td>
    </tr>




    <tr class="small_piece">

        <td class="color-6 no_border">
            8:00 – 9:00<br />
            <b class="bold">Morgenband</b>
        </td>
        <td class="color-2 db_text text-center" colspan="2"><?php PrintInfo($current_day, 1, 1, $pdo, $webroot); ?></td>

        <td class="color-6 no_border">
            8:00 – 9:00<br />
            <b class="bold">Morgenband</b>
        </td>

        <td class="color-2 db_text text-center" colspan="7"><?php PrintInfo($current_day, 1, 10, $pdo, $webroot); ?></td>
    </tr>
    <tr class="macro_piece">

        <td class="color-6 no_border">
            9:00 – 9:30<br />
            <b class="bold">Morgenkreise</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 2, 1, $pdo, $webroot); ?></td>
        <td class="color-1 db_text"><?php PrintInfo($current_day, 2, 9, $pdo, $webroot); ?></td>

        <td class="color-6 no_border" rowspan="2">
            9:00 - 10:00<br/>
            <b class="bold">Offene Räume</b>
        </td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 6, 2, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 6, 3, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 6, 4, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 6, 5, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="6"><?php PrintLessonToPlan($current_day, 13, 6, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="6"><?php PrintLessonToPlan($current_day, 13, 7, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="6"><?php PrintLessonToPlan($current_day, 13, 14, $pdo, $webroot); ?></td>
    </tr>




    <tr class="small_piece">

        <td class="color-6 no_border" rowspan="2">
            9:30 – 10:30<br/>
            <b class="bold">Angebot 1</b>
        </td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 3, 1, $pdo, $webroot); ?></td>
        <td class="color-1 db_text" rowspan="2"><?php PrintInfo($current_day, 3, 9, $pdo, $webroot); ?></td>
    </tr>


    <tr class="macro_piece">

        <td class="color-6 no_border">
            10:00 – 10:30<br/>
            <b class="bold">Morgenkreise</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 7, 2, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 7, 3, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 7, 4, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 7, 5, $pdo, $webroot); ?></td>
    </tr>





    <tr class="macro_piece">

        <td class="color-6 no_border">
            <b class="bold">Räum-Pause </b>
        </td>

        <td class="color-2 db_text"></td>
        <td class="color-1 db_text"></td>

        <td class="color-6 no_border" rowspan="3">
            10:30 – 12:00<br/>
            <b class="bold">Großes Band</b>
        </td>

        <td class="color-2 db_text" rowspan="3"><?php PrintLessonToPlan($current_day, 8, 2, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="3"><?php PrintLessonToPlan($current_day, 8, 3, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" rowspan="3"><?php PrintLessonToPlan($current_day, 8, 4, $pdo, $webroot); ?></td>
    </tr>



    <tr class="small_piece">

        <td class="color-6 no_border">
            10:45 – 11:45<br/>
            <b class="bold">Angebot 2</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 4, 1, $pdo, $webroot); ?></td>
        <td class="color-1 db_text"><?php PrintInfo($current_day, 4, 9, $pdo, $webroot); ?></td>


        <td class="color-2 db_text" rowspan="2"><?php PrintLessonToPlan($current_day, 8, 5, $pdo, $webroot); ?></td>
    </tr>


    <tr class="macro_piece">

        <td class="color-6 no_border">
            11:45 – 12:00<br>
            <b class="bold">Logbuchzeit</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 16, 1, $pdo, $webroot); ?></td>
        <td class="color-1 db_text"><?php PrintInfo($current_day, 16, 9, $pdo, $webroot); ?></td>
    </tr>


    <tr class="breakfast">

        <td class="color-6 no_border">
            12:00 – 13:00<br/>
            <b class="bold">Mittagspause</b>
        </td>

        <td class="color-4 no_border_no_margin text-center" colspan="10"><?php PrintInfoWithDesc($current_day, 14, 10, $pdo, $webroot); ?></td>
    </tr>


    <tr class="small_piece">

        <td class="color-6 no_border">
            13:00 – 14:15<br/>
            <b class="bold">Nachmittagsband</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 1, $pdo, $webroot); ?></td>
        <td class="color-1 db_text"><?php PrintInfo($current_day, 5, 9, $pdo, $webroot); ?></td>

        <td class="color-6 no_border">
            13:00 – 14:15<br/>
            <b class="bold">Nachmittagsband</b>
        </td>

        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 2, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 3, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 4, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 5, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 6, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 9, 7, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 5, 14, $pdo, $webroot); ?></td>
    </tr>

    <tr class="macro_piece">

        <td class="color-6 no_border">
            14:15 – 14:30<br>
            <b class="bold">Logbuchzeit</b>
        </td>

        <td class="color-2 db_text" colspan="1"><?php PrintLessonToPlan($current_day, 15, 1, $pdo, $webroot); ?></td>
        <td class="color-1 db_text" colspan="1"><?php PrintInfo($current_day, 15, 9, $pdo, $webroot); ?></td>
        <td class="color-6 no_border">
            14:15 – 14:30<br>
            <b class="bold">Logbuchzeit</b>
        </td>
        <td class="color-2 db_text" colspan="3"><?php PrintLessonToPlan($current_day, 15, 2, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" colspan="1"><?php PrintLessonToPlan($current_day, 15, 5, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" colspan="1"><?php PrintLessonToPlan($current_day, 15, 6, $pdo, $webroot); ?></td>
        <td class="color-2 db_text" colspan="2"><?php PrintLessonToPlan($current_day, 15, 8, $pdo, $webroot); ?></td>
    </tr>


    <tr class="small_piece">
        <td class="white-col align-bottom" rowspan="3" colspan="3">
            <span onclick="openFullscreen()"    class="plan_btn open_fullscreen fe fe-32 fe-maximize-2 pointer"></span>
            <span onclick="closeFullscreen()"   class="plan_btn close_fullscreen fe fe-32 fe-minimize-2 pointer"></span>
            <span onclick="updateDateInUrl(-1)" class="plan_btn fe fe-24 fe-arrow-left pointer"></span>
            <span onclick="updateDateInUrl(1)"  class="plan_btn fe fe-24 fe-arrow-right pointer"></span>
            <span onclick='customPrint()' class="plan_btn fe fe-24 fe-download pointer"></span>
            <?php
            if (IsPermitted($create_lessons, $permission_level)) {
                echo '<span onclick="window.location.href=\'../lessons/details/?date=' . $current_day . '\'" class="plan_btn fe fe-24 fe-plus pointer"></span>';
            }
            ?>
            <span onclick="window.location.href='../dashboard'" class="plan_btn fe fe-24 fe-home pointer"></span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 260.513 276.552" style="padding-bottom: 0px;padding-top: 0px;padding-right: 0px;margin-left: 5px;margin-top: -5px;padding-left: 0px;" width="30" height="30"><style>@keyframes animate-svg-stroke-1{0%{stroke-dashoffset:95.20066833496094px;stroke-dasharray:95.20066833496094px}to{stroke-dashoffset:0;stroke-dasharray:95.20066833496094px}}@keyframes animate-svg-stroke-2{0%{stroke-dashoffset:92.57791900634766px;stroke-dasharray:92.57791900634766px}to{stroke-dashoffset:0;stroke-dasharray:92.57791900634766px}}@keyframes animate-svg-stroke-3{0%{stroke-dashoffset:128.8311309814453px;stroke-dasharray:128.8311309814453px}to{stroke-dashoffset:0;stroke-dasharray:128.8311309814453px}}@keyframes animate-svg-stroke-4{0%{stroke-dashoffset:74.30066680908203px;stroke-dasharray:74.30066680908203px}to{stroke-dashoffset:0;stroke-dasharray:74.30066680908203px}}@keyframes animate-svg-stroke-5{0%{stroke-dashoffset:65.75314712524414px;stroke-dasharray:65.75314712524414px}to{stroke-dashoffset:0;stroke-dasharray:65.75314712524414px}}@keyframes animate-svg-stroke-6{0%{stroke-dashoffset:333.66778564453125px;stroke-dasharray:333.66778564453125px}to{stroke-dashoffset:0;stroke-dasharray:333.66778564453125px}}@keyframes animate-svg-stroke-7{0%{stroke-dashoffset:159.18104553222656px;stroke-dasharray:159.18104553222656px}to{stroke-dashoffset:0;stroke-dasharray:159.18104553222656px}}@keyframes animate-svg-stroke-8{0%{stroke-dashoffset:90.17328643798828px;stroke-dasharray:90.17328643798828px}to{stroke-dashoffset:0;stroke-dasharray:90.17328643798828px}}@keyframes animate-svg-stroke-9{0%{stroke-dashoffset:80.41352081298828px;stroke-dasharray:80.41352081298828px}to{stroke-dashoffset:0;stroke-dasharray:80.41352081298828px}}@keyframes animate-svg-stroke-10{0%{stroke-dashoffset:358.4232177734375px;stroke-dasharray:358.4232177734375px}to{stroke-dashoffset:0;stroke-dasharray:358.4232177734375px}}@keyframes animate-svg-stroke-11{0%{stroke-dashoffset:62.24898910522461px;stroke-dasharray:62.24898910522461px}to{stroke-dashoffset:0;stroke-dasharray:62.24898910522461px}}@keyframes animate-svg-stroke-12{0%{stroke-dashoffset:55.919700622558594px;stroke-dasharray:55.919700622558594px}to{stroke-dashoffset:0;stroke-dasharray:55.919700622558594px}}@keyframes animate-svg-stroke-13{0%{stroke-dashoffset:140.86436462402344px;stroke-dasharray:140.86436462402344px}to{stroke-dashoffset:0;stroke-dasharray:140.86436462402344px}}@keyframes animate-svg-stroke-14{0%{stroke-dashoffset:159.6337890625px;stroke-dasharray:159.6337890625px}to{stroke-dashoffset:0;stroke-dasharray:159.6337890625px}}@keyframes animate-svg-stroke-15{0%{stroke-dashoffset:155.77137756347656px;stroke-dasharray:155.77137756347656px}to{stroke-dashoffset:0;stroke-dasharray:155.77137756347656px}}@keyframes animate-svg-stroke-16{0%{stroke-dashoffset:229.5051727294922px;stroke-dasharray:229.5051727294922px}to{stroke-dashoffset:0;stroke-dasharray:229.5051727294922px}}</style><g data-paper-data="{&quot;isPaintingLayer&quot;:true}" fill="none" stroke-linecap="round" stroke-miterlimit="10" style="mix-blend-mode:normal"><path d="m185.728 46.948 27.767 88.968" stroke="#f5520f" stroke-width="10.5" style="animation:animate-svg-stroke-1 1s cubic-bezier(.47,0,.745,.715) 0s both" transform="translate(-110.119 -41.698)"></path><path d="M251.274 140.828s9.15-28.156 16.233-41.197c7.208-13.273 26.645-37.76 26.645-37.76" stroke="#f5520f" stroke-width="11" style="animation:animate-svg-stroke-2 1s cubic-bezier(.47,0,.745,.715) .12s both" transform="translate(-110.119 -41.698)"></path><path d="M245.418 75.66s-15.352-.99-23.23 6.473c-5.827 5.52-6.677 11.964-4.977 18.065 2.47 8.87 3.26 13.398 14.994 12.96 9.202-.343 18.42-7.92 19.937-13.354 1.629-5.831-3.668-9.421-9.362-9.133-2.281.115-7.243 1.358-9.66 3.98-2.86 3.098-3.002 7.643-3.002 7.643" stroke="#f5520f" stroke-width="8" style="animation:animate-svg-stroke-3 1s cubic-bezier(.47,0,.745,.715) .24s both" transform="translate(-110.119 -41.698)"></path><path d="M240.052 294.425s-6.09-11.706-7.027-19.038c-1.652-12.942-2.025-51.873-2.025-51.873" stroke="#f5520f" stroke-width="8" style="animation:animate-svg-stroke-4 1s cubic-bezier(.47,0,.745,.715) .36s both" transform="translate(-110.119 -41.698)"></path><path d="M249.105 229.549s8.594 13.789 11.069 22.18c3.247 11.006 5.527 38.471 5.527 38.471" stroke="#f5520f" stroke-width="9" style="animation:animate-svg-stroke-5 1s cubic-bezier(.47,0,.745,.715) .48s both" transform="translate(-110.119 -41.698)"></path><path d="M228.888 132.084s-7.392 16.547-8.09 36.5c-.563 16.12 2.772 53.23 9.18 55.063 24.198 6.924 19.87-17.798 18.748-40.137-.322-6.414-.438-22.72-6.837-33.238-5.006-8.229-9.221-26.858-12.63 3.837-.228 2.053-2.07 60.394 8.945 61.277 10.31.827-2.074-47.092-2.074-47.092" stroke="#f5520f" stroke-width="8" style="animation:animate-svg-stroke-6 1s cubic-bezier(.47,0,.745,.715) .6s both" transform="translate(-110.119 -41.698)"></path><path d="M332.689 246.366s-8.764.199-15.117 7.767c-4.05 4.825-6.97 21.399-3.796 25.37 3.734 4.67 12.831 9.409 16.706 8.31 6.362-1.806 17.898-6.402 14.925-17.86-2.006-7.731-9.213-16.804-10.733-14.227-.54.916-9.662 2.575-11.69 11.319-2.25 9.7 5.885 9.121 7.799 8.885 12.624-1.558 2.811-11.46 2.811-11.46" stroke="#c1c611" stroke-width="8" style="animation:animate-svg-stroke-7 1s cubic-bezier(.47,0,.745,.715) .72s both" transform="translate(-110.119 -41.698)"></path><path d="M340.534 227.054s9.257 9.065 12.012 17.987c2.27 7.35 2.18 30.325 4.892 44.204 2.847 14.57 8.443 19.884 8.443 19.884" stroke="#c1c611" stroke-width="9.5" style="animation:animate-svg-stroke-8 1s cubic-bezier(.47,0,.745,.715) .84s both" transform="translate(-110.119 -41.698)"></path><path d="M311.567 238.822s-18.744 41.53-22.283 54.666c-1.765 6.554-.952 19.262-.952 19.262" stroke="#c1c611" stroke-width="11" style="animation:animate-svg-stroke-9 1s cubic-bezier(.47,0,.745,.715) .96s both" transform="translate(-110.119 -41.698)"></path><path d="M325.145 163.687s-6.372.325-9.656 4.828c-7.294 10.002-8.66 11.939-9.237 31.077-.356 11.847 3.657 20.678 6.395 26.254 1.849 3.764 6.628 10.447 11.134 9.969 6.896-.732 15.851-2.372 16.551-36.825.173-8.483 2.158-21.158-3.754-30.7-.268-.433-2.494-3.027-8.446-1.47-11.105 2.907-14.775 24.702-12.559 41.456.88 6.658 1.276 14.228 3.157 18.623.218.512 6.198 4.565 8.993-.014 1.945-3.186 4.724-17.714 4.536-25.929-.43-18.711 2.29-34.984-6.484-25.672-3.743 3.973-2.25 17.113-2.012 28.53.21 10.02-1.032 18.713-1.032 18.713" stroke="#c1c611" stroke-width="7" style="animation:animate-svg-stroke-10 1s cubic-bezier(.47,0,.745,.715) 1.08s both" transform="translate(-110.119 -41.698)"></path><path d="M320.317 100.018s-5.148 9.653-6.164 18.71c-1.174 10.455-.474 40.432-.474 40.432" stroke="#c1c611" stroke-width="10" style="animation:animate-svg-stroke-11 1s cubic-bezier(.47,0,.745,.715) 1.2s both" transform="translate(-110.119 -41.698)"></path><path d="M357.13 111.484s-8.231 16.264-11.536 24.705c-3.231 8.252-8.077 25.385-8.077 25.385" stroke="#c1c611" stroke-width="10" style="animation:animate-svg-stroke-12 1s cubic-bezier(.47,0,.745,.715) 1.3199999999999998s both" transform="translate(-110.119 -41.698)"></path><path d="M160.09 204.724s8.532-11.316 14.885-8.33c14.507 6.82-3.023 19.498-14.596 22.678-4.933 1.355-11.124.347-12.174-10.378-1.158-11.816 3.518-26.63 16.975-27.946 14.641-1.43 19.049 18.847 19.049 18.847" stroke="#0268a8" stroke-width="10" style="animation:animate-svg-stroke-13 1s cubic-bezier(.47,0,.745,.715) 1.44s both" transform="translate(-110.119 -41.698)"></path><path d="M157.373 313.052s17.833-15.981 31.13-33.76c10.788-14.424 17.765-31.166 16.39-32.465-.713-.675-10.098 8.801-21.08 22.024-10.911 13.137-24.63 36.657-24.63 36.657" stroke="#0268a8" stroke-width="8" style="animation:animate-svg-stroke-14 1s cubic-bezier(.47,0,.745,.715) 1.56s both" transform="translate(-110.119 -41.698)"></path><path d="M147.416 304.603s-10.82 1.025-17.987-4.82c-2.616-2.135-18.905-33.083-14.585-37.427 6-6.034 19.37 6.687 24.386 14.535.644 1.008 14.406 16.527 13.998 16.892-12.032 10.776-30.254-19.958-30.254-19.958" stroke="#0268a8" stroke-width="8" style="animation:animate-svg-stroke-15 1s cubic-bezier(.47,0,.745,.715) 1.68s both" transform="translate(-110.119 -41.698)"></path><path d="M164.012 223.131s-10.87 4.982-15.216 16.301c-6.136 15.98-3.483 25.071-2.48 28.01.936 2.74 6.3 14.334 13.365 17.988 5.076 2.626 24.858-27.223 16.013-53.421-1.462-4.33-15.55-.85-17.423 7.99-1.457 6.874-2.812 20.33-3.106 23.638-.347 3.91 4.798 14.455 6.722 10.902 3.35-6.19 6.35-31.19 6.35-31.19" stroke="#0268a8" stroke-width="7" style="animation:animate-svg-stroke-16 1s cubic-bezier(.47,0,.745,.715) 1.7999999999999998s both" transform="translate(-110.119 -41.698)"></path></g></svg>        </td>

        <td class="color-6 no_border" rowspan="2">
            14:30 – 15:00<br/>
            <b class="bold">Putzen</b>
        </td>
        <td class="color-5 text-center no_border_no_margin bold" colspan="8"><?php PrintInfoWithDesc($current_day, 12, 10, $pdo, $webroot); ?></td>
    </tr>

    <tr class="small_piece">
        <td class="color-5 text-center db_text" colspan="2"><?php PrintInfo($current_day, 12, 11, $pdo, $webroot); ?></td>
        <td class="color-5 text-center db_text" colspan="2"><?php PrintInfo($current_day, 12, 12, $pdo, $webroot); ?></td>
        <td class="color-5 text-center db_text" colspan="3"><?php PrintInfo($current_day, 12, 13, $pdo, $webroot); ?></td>
    </tr>




    <tr class="piece">
        <td class="color-6 no_border">
            15:00 – 16:00<br/>
            <b class="bold">Spätes Band</b>
        </td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 2, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 3, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 4, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 5, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 6, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 7, $pdo, $webroot); ?></td>
        <td class="color-2 db_text"><?php PrintLessonToPlan($current_day, 10, 14, $pdo, $webroot); ?></td>
    </tr>
    </tbody>
</table>