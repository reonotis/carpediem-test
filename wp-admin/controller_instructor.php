<?php

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/admin.php';

class Instructor {

	private $comment_Limit = 3;

	public function register( ) {
		try {
			$instructor_name = $_POST['instructor_name'] ;
			$instructor_level = $_POST['instructor_level'] ;
			$band_colour = $_POST['band_colour'] ;
			$email = $_POST['email'] ;
			$faceBook_url = $_POST['faceBook_url'] ;
			$instagram_url = $_POST['instagram_url'] ;
			$twitter_url = $_POST['twitter_url'] ;
			$introduction = $_POST['introduction'] ;
			$lesson_fee = $_POST['lesson_fee'] ;
			$lesson_fee_4time = $_POST['lesson_fee_4time'] ;
			$display_flg = $_POST['display_flg'] ;

			// エラーチェックを行う
			$err_lists = [];
            if( !$instructor_name ) array_push($err_lists,'インストラクター名が入力されていません');
            if( !$email ) array_push($err_lists,'メールアドレスが入力されていません');

			if($err_lists) throw new \Exception();

			// 登録処理
			global $wpdb;

            $wpdb->insert(
                    'instructors',
                array(
                    'instructor_name' => $instructor_name,
                    'instructor_level' => $instructor_level,
                    'band_colour' => $band_colour,
                    'introduction' => $introduction,
                    'faceBook_url' => $faceBook_url,
                    'instagram_url' => $instagram_url,
                    'twitter_url' => $twitter_url,
                    'email' => $email,
                    'lesson_fee' => $lesson_fee,
                    'lesson_fee_4time' => $lesson_fee_4time,
                    'display_flg' => $display_flg
                    //     ),array(
                    //       '%s', //date
                    //       '%s', //open_time
                    //       '%d', //course_id
                    //       '%d', //price
                    //       '%d', //instructor_id
                    //       '%d', //erea
                    //       '%s', //comment
                    //       '%s'  //
                )
			);

			$ID = $wpdb->insert_id;

			// 並び順を決めておく
			$wpdb->update(
				'instructors',
                array(
                    'rank' => $ID
                ),
                array( 'ID' =>  $ID ),
			);

            add_settings_error( 'settings_errors', 'settings_errors', '登録が完了しました。', 'success' );
            // add_settings_error()した内容を、DBに一時保存する
            set_transient( 'settings_errors', get_settings_errors(), 30 );

			$goback = add_query_arg( 'settings-updated', 'true', './admin.php?page=edit_teacher_page' );
            wp_redirect( $goback .'&ID='. $ID);
            exit;

		} catch (\Exception $e) {
			// If no settings errors were registered add a general 'updated' message.
			foreach($err_lists as $err_list){
                add_settings_error( 'settings_errors', 'settings_errors', $err_list, 'error' );
            }

            // add_settings_error()した内容を、DBに一時保存する
            set_transient( 'settings_errors', get_settings_errors(), 30 );

            $aaa = array(
                'settings-updated' => true,
				'instructor_name' => $instructor_name,
				'instructor_level' => $instructor_level,
				'band_colour' => $band_colour,
				'email' => $email,
				'faceBook_url' => $faceBook_url,
				'instagram_url' => $instagram_url,
				'twitter_url' => $twitter_url,
				'introduction' => $introduction,
				'display_flg' => $display_flg,
				'lesson_fee' => $lesson_fee,
				'lesson_fee_4time' => $lesson_fee_4time,
            );
            // Redirect back to the settings page that was submitted.
            $goback = add_query_arg( $aaa, wp_get_referer() );
            wp_redirect( $goback );
            exit;
		}
	}

	public function award_insert() {
		try {
			$ID    = $_POST['id'] ;
			global $wpdb;
			$wpdb->insert(
					'instructor_awards',
				array(
					'instructor_id'    => $ID,
				// ),array(
				// 	'%s', //comment
				)
			);

			add_settings_error( 'settings_errors', 'settings_errors', 'レコードを作成しました。修正してください', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
		} catch (\Exception $e) {
			$getMessage = $e->getMessage();

			// If no settings errors were registered add a general 'updated' message.
			add_settings_error( 'settings_errors', 'settings_errors', $getMessage, 'error' );

			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;
		}
	}

	public function award_delete() {
		try {

			$ID    = $_POST['ID'] ;
			global $wpdb;
			$wpdb->update('instructor_awards',
				array(
					'del_flg' =>1,
				),
				array( 'id' =>  $ID ),// where句
			);

			add_settings_error( 'settings_errors', 'settings_errors', '削除しました', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;
		} catch (\Exception $e) {
			$getMessage = $e->getMessage();

			// If no settings errors were registered add a general 'updated' message.
			add_settings_error( 'settings_errors', 'settings_errors', $getMessage, 'error' );

			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;
		}
	}

	public function award_update() {
		try {

			$ID    = $_POST['ID'] ;
			$rank  = $_POST['rank'] ;
			$award = $_POST['award'] ;
			$display_flg = $_POST['display_flg'] ;

			global $wpdb;
			$wpdb->update('instructor_awards',
				array(
					'award'       => $award,
					'rank'        => $rank,
					'display_flg' => $display_flg,
				),
				array( 'id' =>  $ID ),// where句
			);

			add_settings_error( 'settings_errors', 'settings_errors', '更新が完了しました。', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;
		} catch (\Exception $e) {
			$getMessage = $e->getMessage();

			// If no settings errors were registered add a general 'updated' message.
			add_settings_error( 'settings_errors', 'settings_errors', $getMessage, 'error' );

			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;
		}
	}

	public function update() {
		try {
			// var_dump($_POST);exit;
			$ID               = $_POST['ID'] ;
			$instructor_name  = $_POST['instructor_name'] ;
			$instructor_level = $_POST['instructor_level'] ;
			$band_colour      = $_POST['band_colour'] ;
			$email            = $_POST['email'] ;
			$introduction     = $_POST['introduction'] ;
			$faceBook_url     = $_POST['faceBook_url'];
			$instagram_url    = $_POST['instagram_url'];
			$twitter_url      = $_POST['twitter_url'];
			$display_flg      = $_POST['display_flg'];
			$lesson_fee       = $_POST['lesson_fee'];
			$lesson_fee_4time = $_POST['lesson_fee_4time'];

			// エラーチェックを行う
			$err_lists = [];
			if( !$instructor_name ) array_push($err_lists,'インストラクター名が入力されていません');
			// if( !$l_name ) array_push($err_lists,'名前が入力されていません');
			// if( !$email )  array_push($err_lists,'メールアドレスが入力されていません');
			// if( !$f_read ) array_push($err_lists,'ミョウジが入力されていません');
			// if( !$l_read ) array_push($err_lists,'ナマエが入力されていません');
			// if( $commentCount > $this->comment_Limit ) array_push($err_lists,'コメントの文字数は'. $this->comment_Limit. '文字以内で記入してください');

			// array_push($err_lists,'強制終了します');
			if($err_lists) throw new \Exception();

			global $wpdb;
			$wpdb->update('instructors',
				array(
					'instructor_name' => $instructor_name,
					'instructor_level' => $instructor_level,
					'band_colour' => $band_colour,
					'introduction' => $introduction,
					'faceBook_url' => $faceBook_url,
					'instagram_url' => $instagram_url,
					'twitter_url' => $twitter_url,
					'display_flg' => $display_flg,
					'email' => $email,
					'lesson_fee' => $lesson_fee,
					'lesson_fee_4time' => $lesson_fee_4time,
				),
				array( 'ID' =>  $ID ),// where句
			);

			add_settings_error( 'settings_errors', 'settings_errors', '更新しました', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;

		} catch (\Exception $e) {
			$getMessage = $e->getMessage();

			// If no settings errors were registered add a general 'updated' message.
			foreach($err_lists as $err_list){
				add_settings_error( 'settings_errors', 'settings_errors', $err_list, 'error' );
			}

			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
			exit;
		}
    }

    public function delete() {
		try {

			$id = $_POST['id'];

			global $wpdb;
			$wpdb->update(
				'instructors',
                array(
                        'del_flg' =>1,
                ),
				array( 'id' =>  $id )   // where句
			);

            add_settings_error( 'settings_errors', 'settings_errors', '削除が完了しました。', 'success' );
            // add_settings_error()した内容を、DBに一時保存する
            set_transient( 'settings_errors', get_settings_errors(), 30 );

            // Redirect back to the settings page that was submitted.
            // $goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
            $goback = add_query_arg( 'settings-updated', 'true', './admin.php?page=list_of_teacher'  );
            wp_redirect( $goback );
            exit;

		} catch (\Exception $e) {
			$_SESSION['error']= $e->getMessage();
		}
	}

}



$instructor = new Instructor();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //POSTが渡されたら
	if($_POST['update'] )$instructor -> update();
	if($_POST['award_insert'] )$instructor -> award_insert();
	if($_POST['award_update'] )$instructor -> award_update();
	if($_POST['award_delete'] )$instructor -> award_delete();
	if($_POST['register'] )$instructor -> register();
	if($_POST['delete'] )$instructor -> delete();
}

?>

