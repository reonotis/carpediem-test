<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_script(
        'main-script',
        get_template_directory_uri() . '-child/js/jquery.waypoints.min.js'
    );
}










// ここからオリジナル

/**
 * 渡されたレコードに日本語の曜日をセットする
 *
 * @param [type] $date
 * @return void
 */
function set_weeks_ja($date) {
    $week = array( "日", "月", "火", "水", "木", "金", "土" );
    $date->week_ja = $week[$date->week];
    return $date;
}






// instructor設定画面のfunctionを読み込む
include 'setting_instructors.php';
include 'setting_courses.php';
include 'setting_schedules.php';



/**
 * 会員種別を取得する
 */
function get_membership_type_list() {
    global $wpdb;
    $sql = "SELECT * FROM membership_type";
    $rows = $wpdb->get_results($sql);
    return $rows;
}

/**
 * コースに参加できるかできないかを判断ながら会員種別の一覧を取得
 */
function get_mapping_membership_types($id) {
    global $wpdb;
    $sql = "SELECT
                membership_type.*,
                MTCM.membership_type_id AS MT_id
            FROM membership_type
            LEFT JOIN membership_type_courses_mapping AS MTCM ON MTCM.membership_type_id = membership_type.id AND MTCM.course_id = $id
            WHERE membership_type.del_flg = 0
            ORDER BY membership_type.member_type_rank ASC
    ";
    $rows = $wpdb->get_results($sql);
    return $rows;
}

/**
 * 先生の一覧を表示する
 *
 * @return void
 */
function listOfUsers_function() {
    global $wpdb;
    $sql = "SELECT * FROM instructors";
    $rows = $wpdb->get_results($sql);
    // var_dump($rows);
    foreach($rows as $row){
        echo $row->instructor_name;
    }
}

add_shortcode('list_of_users', 'listOfUsers_function');



/**
 * スケジュール表を表示する
 *
 * @return void
 */
function make_scheduleTable($membership_type_id = NULL) {

    // 表示可能なcourseのIDを取得する
    $course_Ids = get_displayPossible_coursesId($membership_type_id);

    // 表示可能なcourseを取得する
    $schedules = get_displayPossible_schedules($course_Ids);

    // ここからHTML作成
    $return = make_schedules_html($schedules);

    return $return;
    // return var_dump($return);
}



/**
 * スケジュール表を表示する
 *
 * @return void
 */
function show_scheduleTable($atts) {
    $atts = shortcode_atts(array(
        "membership_type" => NULL,
    ),$atts);
    $membership_type = $atts['membership_type'];
    return make_scheduleTable($membership_type);
}
add_shortcode('show_schedule_Table', 'show_scheduleTable');





/**
 *
 */

function get_instructors_list(){

	global $wpdb;

	$query="SELECT *
            FROM instructors
            WHERE del_flg = 0
            ORDER BY rank asc
            ";
	$results = $wpdb->get_results($query);
	return $results;
}

/**
 *
 */
function get_instructor_awards($instructorIdList){
	global $wpdb;
	$query="SELECT *
            FROM instructor_awards
            WHERE instructor_id IN ($instructorIdList)
            AND del_flg = 0
            ORDER BY rank ASC
            ";
	$results = $wpdb->get_results($query);
	return $results;
}

/**
 *
 * @return void
 */
function show_instructorsList($atts) {
    // $atts = shortcode_atts(array(
    //     "membership_type" => NULL,
    // ),$atts);
    // $membership_type = $atts['membership_type'];
    $instructorsList = get_instructors_list();
    $instructorIdList = array_column($instructorsList, 'id');
    $instructorIdList = implode(',', $instructorIdList);

    $instructorAwardsList = get_instructor_awards($instructorIdList);
    if(is_null($instructorsList)){
        return '表示できるインストラクターはいません';
    }else{
        $HTML = '<div class="instructorsSection" >';
            $HTML .= '<div class="instructorsArea" >';
                foreach($instructorsList as $instructor ){
                        $HTML .= '<div class="instructorBox" >';
                            $HTML .= '<div class="instr_img" >';
                                $HTML .= '<img src="' . $instructor->img_pass . '" >';
                            $HTML .= '</div>';
                            $HTML .= '<div class="instr_level" >' . $instructor->instructor_level . '</div>';
                            $HTML .= '<div class="instr_level_support" ></div>';
                            $HTML .= '<div class="instr_content" >';
                                $HTML .= '<div class="instr_name" >' . $instructor->instructor_name . '</div>';
                                $HTML .= '<div class="inst_title" >' ;
                                    $HTML .= '<ul>' ;
                                        foreach($instructorAwardsList as $instructorAward){
                                            if($instructorAward->instructor_id == $instructor->id ){
                                                $HTML .= '<li>' . $instructorAward->award . '</li>' ;
                                            }
                                        }
                                    $HTML .= '</ul>';
                                $HTML .= '</div>';
                                $HTML .= '<div class="instructorButton" ><a href="./instructor?id=' . $instructor->id . '" >詳細を見る</a></div>';
                            $HTML .= '</div>';
                        $HTML .= '</div>';
                }
            $HTML .= '</div>';
        $HTML .= '</div>';
    }

    return $HTML;
    return var_dump($instructorsList);
}
add_shortcode('show_instructors_list', 'show_instructorsList');

/**
 * 最新のお知らせを表示
 *
 * @return void
 */
function func_showNewNotice(){

    $information= get_posts( array(
        // 取得条件
        'posts_per_page' => 5,  // 取得する件数
    ));
    if( $information):
        $HTML = '';
        $HTML .= '<ul class="newNotice" >';
            foreach( $information as $post ):
                $HTML .= '<li>';
                    $HTML .= date('Y年m月d日 ', strtotime($post->post_date) ) . '      ' . '<a href="' . $post->guid . '">'. $post->post_title . '</a>';
                $HTML .= '</li>';
            endforeach;

        $HTML .= '</ul>';
        return $HTML;

    else:
        return '<p>表示できる情報はありません。</p>';
    endif;
}
add_shortcode('show_new_notice', 'func_showNewNotice');











