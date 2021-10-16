<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'modal-video', 'https://cdnjs.cloudflare.com/ajax/libs/lity/2.3.1/lity.css' );
    wp_enqueue_script(
        'jquery',
        'https://code.jquery.com/jquery-2.2.4.min.js'
    );
    wp_enqueue_script(
        'q_and_a-script',
        get_stylesheet_directory_uri() . '/js/mesmerize-child.js'
    );
    wp_enqueue_script(
        'main-script',
        get_template_directory_uri() . '-child/js/jquery.waypoints.min.js'
    );
    wp_enqueue_script(
        'jquery-modal-video.min',
        'https://cdnjs.cloudflare.com/ajax/libs/lity/2.3.1/lity.js'
    );
}






// ここからオリジナル

/**
 * 曜日を日本語にして返す
 *
 * @param [type] $week_num
 * @return void
 */
function get_weekName($week_num){
    // TODO エラーハンドリングしたい
    $week = [
        0 => '日',
        1 => '月',
        2 => '火',
        3 => '水',
        4 => '木',
        5 => '金',
        6 => '土',
    ];
    return $week[$week_num] . '曜日';
}


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
 *
 * @return void
 */
function func_show_course_schedule_list($atts) {
    // TODO エラーハンドリングしたい
    $atts = shortcode_atts(array(
        "course_id" => NULL,
    ),$atts);
    $course_id = $atts['course_id'];
    $course_name = get_course_name($course_id);
    $scheduleList = get_displayPossible_schedules($course_id);
    $scheduleList = setting_SchedulesForWeekAndTime($scheduleList);

    $HTML = '<div class="courseScheduleListArea" >';
        $HTML .= '<div class="courseScheduleWrapper">';
            $HTML .= '<div class="courseScheduleWrapperTitle" id="courseScheduleWrapperTitle'.$course_id.'">';
                $HTML .= $course_name . 'の開催日時を確認する';
            $HTML .= '</div>';
        $HTML .= '</div>';
        $HTML .= '<div class="courseScheduleContent" id="courseScheduleContent'.$course_id.'" style="display: none;">';
            foreach($scheduleList as $week => $schedule){
                if(!empty($schedule)){
                    $HTML .= '<div class="scheduleWeekRow">';
                        // 曜日を表示する
                        $HTML .= '<div class="scheduleWeekName">' . get_weekName($week) . ' :</div>';
                        // 時間を全て表示する
                        $HTML .= '<div class="scheduleWeekContent">';
                            foreach($schedule as $data){
                                $HTML .= '<span class="scheduleWeekContentTime">' . date('H:i', strtotime($data)) . '～,</span>';
                            }
                        $HTML .= '</div>';
                    $HTML .= '</div>';
                }
            }
        $HTML .= '</div>';
    $HTML .= '</div>';
    return $HTML;
}
add_shortcode('show_course_schedule_list', 'func_show_course_schedule_list');



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
function get_instructor_galleries($instructorId, $display_flg = false){
	global $wpdb;
	$query="SELECT *
            FROM instructor_garallys
            WHERE instructor_id = $instructorId
            AND del_flg = 0 ";
    if($display_flg == true){
        $query .= "AND display_flg = 1 ";
    }
    $query .= "ORDER BY rank ASC";
	$results = $wpdb->get_results($query);
	return $results;
}

/**
 *
 */
function get_instructor_awards($instructorIdList, $display_flg = false){
	global $wpdb;
	$query="SELECT *
            FROM instructor_awards
            WHERE instructor_id IN ($instructorIdList)
            AND del_flg = 0 ";
    if($display_flg == true){
        $query .= "AND display_flg = 1 ";
    }
    $query .= "ORDER BY rank ASC";
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

    $instructorAwardsList = get_instructor_awards($instructorIdList, true);
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
                        $HTML .= '<div class="instr_level ' . $instructor->band_colour . '" >' . $instructor->instructor_level . '</div>';
                        $HTML .= '<div class="instr_level_support" ><div class="instr_level_support_knot" ></div></div>';
                        $HTML .= '<div class="instr_content" >';
                            $HTML .= '<div class="instr_name" >' . $instructor->instructor_name . '</div>';
                            $HTML .= '<div class="inst_title" >' ;
                                $HTML .= '<ul>' ;
                                $count = 0;
                                    foreach($instructorAwardsList as $instructorAward){
                                        if($instructorAward->instructor_id == $instructor->id ){
                                            $HTML .= '<li>' . $instructorAward->award . '</li>' ;
                                            $count++;
                                            if($count==3){
                                                break;
                                            }
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
                $HTML .= '<a href="' . $post->guid . '">';
                    $HTML .= '<li>';
                        $HTML .= date('Y年m月d日 ', strtotime($post->post_date) ) . '　>　' . $post->post_title ;
                    $HTML .= '</li>';
                $HTML .= '</a>';
            endforeach;

        $HTML .= '</ul>';
        return $HTML;

    else:
        return '<p>表示できる情報はありません。</p>';
    endif;
}
add_shortcode('show_new_notice', 'func_showNewNotice');

/**
 * 会員別インフォメーションを表示する
 * @return void
 */
function func_showMemberInfoTable($atts){
    $atts = shortcode_atts(array(
        "type_id" => 1,
        "pattern" => 1,
    ),$atts);
    $type_id = $atts['type_id'];
    $pattern = $atts['pattern'];

	global $wpdb;
	$query="SELECT *
            FROM membership_type
            WHERE id = $type_id
            AND del_flg = 0
            ";
	$membership_type = $wpdb->get_row($query);
    if(!$membership_type) return false;

	$query="SELECT courses.course_name
            FROM membership_type_courses_mapping
            INNER JOIN courses ON courses.id = membership_type_courses_mapping.course_id
            WHERE membership_type_id = $type_id
            AND del_flg = 0
            AND display_flg = 1
            ";
	$results = $wpdb->get_results($query);
    if(!$results) return false;

    if( $pattern == 1){
        $html = member_info_table1($membership_type, $results, $type_id);
    }else if( $pattern == 2){
        $html = member_info_table2($membership_type);
    }
    return $html;
}
add_shortcode('show_member_info_table', 'func_showMemberInfoTable');

function member_info_table1($membership_type, $results, $type_id){
    $html = "";
    $html .= '
    <table class="member_info_table1" >
        <tr>
            <th>対象</th>
            <th>入会金</th>
            <th>月会費</th>
            <th>参加可能コース</th>
        </tr>
        <tr>
            <td>'. nl2br($membership_type->target_person) .'</td>
            <td>11,000円</td>
            <td>'. number_format($membership_type->monthly_fee) .'円</td>
            <td>';
                foreach($results as $data){
                    $html .= $data->course_name. '<br>' ;
                }
                if($type_id == 6){
                    $html .= '※'.$membership_type->member_type_name.'で登録された女性の方は別途、Women’s only class も受講する事が可能です。';
                }
            $html .= '</td>
        </tr>
    </table>';
    return $html;
}

function member_info_table2($membership_type){
    $html = "";
    $html .= '
    <table class="member_info_table2" >
        <tr>
            <th>対象</th>
            <td>'. nl2br($membership_type->target_person) .'</td>
        </tr>
        <tr>
            <th>入会金</th>
            <td>11,000円</td>
        </tr>
        <tr>
            <th>月会費</th>
            <td>';
            if(is_numeric($membership_type->monthly_fee)){
                $html .= number_format($membership_type->monthly_fee) .'円';
            }else{
                $html .= $membership_type->monthly_fee;
            }
            $html .= '</td>
        </tr>
    </table>';
    return $html;
}





// フッターの内容を修正
function get_footer_text(){
	$copyrightText = __( 'Built using WordPress and the <a rel="nofollow" target="_blank" href="%1$s" class="mesmerize-theme-link">Mesmerize Theme</a>', 'mesmerize' );
	$copyrightText = sprintf( $copyrightText, 'https://extendthemes.com/go/built-with-mesmerize/' );
	$previewAtts = "";
	if ( mesmerize_is_customize_preview() ) {
		$previewAtts = 'data-footer-copyright="true"';
	}
	$copyright = '<p ' . $previewAtts . ' class="copyright">&copy;&nbsp;' . "&nbsp;" . date_i18n( __( 'Y',
			'mesmerize' ) ) . '&nbsp;' . esc_html( get_bloginfo( 'name' ) ) . '</p>';
	return apply_filters( 'mesmerize_get_footer_copyright', $copyright, $previewAtts );
}