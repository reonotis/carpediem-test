<?php

// 管理画面のメインメニュー
function list_of_schedule(){
    add_menu_page(
        'schedule',                  //   第1引数　：　ページタイトル（title）,
        'スケジュール表',                  //   第2引数　：　メニュータイトル,
        'manage_options',           //   第3引数　：　メニュー表示するユーザーの権限,
        'list_of_schedule',          //   第4引数　：　メニューのスラッグ,
        'add_list_of_schedule',      //   第5引数　：　メニュー表示時に使われる関数,
        'dashicons-admin-generic',  //   第6引数　：　メニューのテキスト左のアイコン,
        4                           //   第7引数　：　メニューを表示する位置;
    );
}
function add_list_of_schedule(){
    $schedules = get_schedules();
    include 'admin_views/schedule_list.php';
}
add_action('admin_menu', 'list_of_schedule');



// 管理画面のサブメニュー
function add_custom_submenu_page_schedule(){
    add_submenu_page('list_of_schedule', 'スケジュール登録', 'スケジュール登録', 'manage_options', 'register_schedule_page', 'add_register_schedule_menu_page', 2);
    add_submenu_page('list_of_schedule', 'スケジュール編集', 'スケジュール編集', 'manage_options', 'edit_schedule_page', 'add_edit_schedule_menu_page', 2);
}
function add_register_schedule_menu_page(){
    include 'admin_views/schedule_create.php';
}
function add_edit_schedule_menu_page(){
    include 'admin_views/schedule_edit.php';
}
add_action('admin_menu', 'add_custom_submenu_page_schedule');













function get_schedules(){
	global $wpdb;
	$query="SELECT schedules.*,
                    courses.course_name,
                    courses.background,
                    courses.border
            FROM schedules
            INNER JOIN courses ON courses.id = schedules.course_id
            WHERE schedules.del_flg IS NULL
            ORDER BY week asc, time asc
            ";
	$results = $wpdb->get_results($query);
    $results = set_schedule_displayNames($results);
    $results = set_status_ja($results);
	return $results;
}


function get_schedule($id){
	global $wpdb;
	$query="SELECT *
            FROM schedules
            WHERE id = $id
            AND del_flg IS NULL
            ";
	$results = $wpdb->get_row($query);
    $results = set_schedule_displayName($results);
	return $results;
}


function set_schedule_displayNames($dates){
    foreach($dates as $date){
        $date = set_schedule_displayName($date);
    }
    return $dates;
}

function set_schedule_displayName($date){
    if($date->display_flg == 1){
        $date->display_name = "表示";
    }else{
        $date->display_name = "非表示";
    }
    return $date;
}

/**
 * 渡されたデータを一行づつ整理していく
 *
 * @param [type] $dates
 * @return void
 */
function set_status_ja($dates){
    foreach($dates as $date){
        $date = set_weeks_ja($date); //日本語の曜日をセットする
        $date = set_ImplementationDate($date); //実施時間を求める
    }
    return $dates;
}

/**
 * 渡されたレコードから表示用の実施時間をセットする
 *
 * @param [type] $date
 * @return void
 */
function set_ImplementationDate($date){
    $startTime = strtotime($date->time);
    $sectionTime = $date->section * 15 ;
    $endTime = $startTime + ($sectionTime * 60);

    $date->implementationDate = date("H:i", $startTime) ." ～ " . date("H:i", $endTime) ;
    $date->period = $sectionTime ;

    return $date;
}


/**
 * 表示可能なcourseのIDを取得して、カンマ区切りの文字列に変換する
 *
 * @return void
 */
function get_displayPossible_coursesId( $membership_type_id = NULL){
    // 条件によってSQLを作成
    if($membership_type_id){
        // 会員のIDが指定されている場合は、その会員タイプが受講できるcourseのIDを取得する
        $query="SELECT courses.id
                FROM membership_type_courses_mapping AS MTCM
                LEFT JOIN courses ON courses.id = MTCM.course_id
                WHERE MTCM.membership_type_id = $membership_type_id
                AND courses.display_flg = 1
                AND courses.del_flg = 0
                ";
    }else{
        // 会員のIDが指定されていない場合は、受講できるcourseのIDを取得する
        $query="SELECT id
                FROM courses
                WHERE courses.display_flg = 1
                AND courses.del_flg = 0
                ";
    }

	global $wpdb;
    $results = $wpdb->get_results($query );

    // 表示可能なcourseのIDを配列に直してから、カンマ区切りの文字列に変換
    foreach($results as $result){
        $array[] = $result->id;
    }
    $return = implode( ",", $array );

    return $return;
}

/**
 * 渡されたIDを基に、表示可能なスケジュールを取得する
 *
 * @param [type] $course_Ids
 * @return void
 */
function get_displayPossible_schedules($course_Ids){
	global $wpdb;
    $query="SELECT
            schedules.*,
            courses.course_name,
            courses.background,
            courses.border,
            courses.youtube_url
            FROM schedules
            INNER JOIN courses ON courses.id = schedules.course_id
            WHERE schedules.course_id IN ($course_Ids)
            AND schedules.del_flg IS NULL
            ORDER BY week ASC, time ASC
    ";
    $results = $wpdb->get_results($query);
    return $results;
}

/**
 * 特定のコース名を返す
 *
 * @param [type] $course_id
 * @return void
 */
function get_course_name($course_id){
	global $wpdb;
    $query="SELECT
            courses.course_name
            FROM courses
            WHERE id = $course_id
    ";
    $results = $wpdb->get_var($query);
    return $results;
}

/**
 * 曜日別の配列に設定しなおす
 * @param [type] $scheduleList
 * @return void
 */
function setting_SchedulesForWeekAndTime($scheduleList){
    $result = [];
    for($i = 0; $i < 7; $i++){ // 日曜日から土曜日まで回す
        $result[$i] = [];
        foreach($scheduleList as $key => $schedule){
            if($schedule->week == $i){
                $result[$i][$key]['start_time'] = $schedule->time;
                $result[$i][$key]['sectionTime'] = $schedule->section * 15;
            }
        }
    }
    return $result;
}

/**
 * Undocumented function
 *
 * @param [type] $schedules
 * @return void
 */
function make_schedules_html($schedules){

// var_dump($schedules);exit;
    $open_time = '05:45:00';
    $close_time = '22:45:00';

    $html ="
    <div class='schedule_section' >
        <div class='schedule_area' >
            <div class='schedule_headerArea' >
                <div class='MIDASHI' > 　</div>
                <div class='weekArea' >月</div>
                <div class='weekArea' >火</div>
                <div class='weekArea' >水</div>
                <div class='weekArea' >木</div>
                <div class='weekArea' >金</div>
                <div class='weekArea saturday' >土</div>
                <div class='weekArea sunday' >日</div>
            </div>
            <div class='schedule_bodyArea' >
                <div class='times'>";
                    for( $time = strtotime($open_time); $time <= strtotime($close_time); $time = strtotime('+15 minute' , $time) ){
                        $html .="<div class='JIKAN'>";
                            if( date('i', $time) == "00" ) $html .="<div class='JIKANTIME'>". date('H:i', $time) ."</div>";
                        $html .="</div>";
                    }
                $html .="
                </div>";


                // 月曜日startにする
                $WEEK = [1, 2, 3, 4, 5, 6, 0 ];
                foreach($WEEK as $YOUBI){ //曜日の7回分を回す

                    $html .= "<div class='weeks'>";
                        for( $time = strtotime($open_time); $time <= strtotime($close_time); $time = strtotime('+15 minute' , $time) ){
                            $html .= "<div class='JIKAN";
                            if(date('H' , $time)% 2 == 1)$html .= " grayBox";
                            if( date("i", $time) == 45 ) $html .= " J45";
                            $html .= "'>";
                                foreach ($schedules as $value) {
                                    if(  $value->display_flg == 1 && $value->week == $YOUBI && date('H:i' , strtotime( $value->time)) == date('H:i' , $time)){
                                        if($value->youtube_url){
                                            $html .= '<a href="' . $value->youtube_url . '" data-lity="data-lity" >' ;
                                        }
                                        $html .= "<div class='KOUSU time".$value->section ."'  style='background:#" .$value->background. ";border:solid 1px #" .$value->border. ";'  >" ;
                                            $html .= "<div class='lessonTitle'>" ;
                                                if($value->section >= 2){
                                                    $html .= "<div class='implementationDate'>" ;
                                                        $html .= $value->implementationDate;
                                                    $html .= "</div>" ;
                                                }
                                                $html .= "<div class=''>" ;
                                                    $html .= $value->course_name ;
                                                $html .= "</div>" ;
                                            $html .= "</div>" ;
                                        $html .= "</div>";
                                        if($value->youtube_url){
                                            $html .= '</a>' ;
                                        }
                                    }
                                }
                            $html .= "</div>";
                        }
                    $html .= "</div>";
                }


            $html .="
            </div>
        </div>
    </div>

    ";

    return $html ;
}