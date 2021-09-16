<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
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
 * オリジナルのCSS
 *
 * @return void
 */
function my_admin_style(){
    wp_enqueue_style( 'my_admin_style', get_stylesheet_directory_uri().'/my_admin_style.css' );
}
add_action( 'admin_enqueue_scripts', 'my_admin_style' );




