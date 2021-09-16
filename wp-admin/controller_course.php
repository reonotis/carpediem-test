<?php

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/admin.php';

class Course {

	private $_course_name    = "" ;
	private $_course_name_en = "" ;
	private $_background_color = "" ;

	public function register( ) {
		try {

			// リクエストパラメータのセットする
			$this->set_param();

			// エラーチェックを行う
			$err_lists = $this->check_validation();
			if($err_lists) throw new \Exception();

			// DB登録処理
			global $wpdb;
			$wpdb->insert(
					'courses',
				array(
					'course_name'    => $this->_course_name,
					'target'         => $this->_target,
					'course_text'    => $this->_course_text,
					'youtube_url'    => $this->_youtube_url,
					'background'     => $this->_background_color,
					'border'         => $this->_border,
					'display_flg'    => $this->_display_flg,
				// ),array(
				// 	'%d', //instructor_id
				// 	'%s', //comment
				)
			);

			// 並び順を決めておく
			$ID = $wpdb->insert_id;
			$wpdb->update(
				'courses',
				array(
					'sort' => $ID
				),
				array( 'ID' =>  $ID ),
			);

			// マッピング登録する
			foreach($_POST['membership_type_mapping'] as $date){
				$wpdb->insert(
						'membership_type_courses_mapping',
					array(
						'membership_type_id' => $date,
						'course_id'          => $ID,
					// ),array(
					// 	'%d', //
					// 	'%s', //
					)
				);
			}

			add_settings_error( 'settings_errors', 'settings_errors', '登録が完了しました。', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( 'settings-updated', 'true', './admin.php?page=list_of_course' );
			wp_redirect( $goback .'&id=2');
			exit;

		} catch (\Exception $e) {
			// If no settings errors were registered add a general 'updated' message.
			foreach($err_lists as $err_list){
				add_settings_error( 'settings_errors', 'settings_errors', $err_list, 'error' );
			}

			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );

			$date = array(
				'settings-updated' => true,
				'course_name'      => $this->_course_name,
				'target'           => $this->_target,
				'course_text'      => $this->_course_text,
				'display_flg'      => $this->_display_flg,
				'background_color' => $this->_background_color,
				'border'           => $this->_border,
			);
			// Redirect back to the settings page that was submitted.
			$goback = add_query_arg( $date, wp_get_referer() );
			wp_redirect( $goback );
			exit;
		}
	}

	public function update() {
		try {
			// リクエストパラメータのセットする
			$this->set_param();

			// エラーチェックを行う
			$err_lists = $this->check_validation();
			if($err_lists) throw new \Exception();

			global $wpdb;
			$wpdb->update(
				'courses',
				array(
					'course_name'    => $this->_course_name,
					// 'course_name_en' => $this->_course_name_en,
					'target'         => $this->_target,
					// 'target_en'      => $this->_target_en,
					'course_text'    => $this->_course_text,
					// 'course_text_en' => $this->_course_text_en,
					'youtube_url'    => $this->_youtube_url,
					'background'     => $this->_background_color,
					'border'         => $this->_border,
					'display_flg'    => $this->_display_flg,
				),
				// where句
				array( 'ID' => $_POST['id'] ),
			);

			// 一度マッピングを物理削除して再度登録しなおす
			$wpdb->query("DELETE FROM membership_type_courses_mapping WHERE course_id = ". $_POST['id']);
			foreach($_POST['membership_type_mapping'] as $DDD){
				$wpdb->insert(
						'membership_type_courses_mapping',
					array(
						'membership_type_id' => $DDD,
						'course_id'          => $_POST['id'],
					// ),array(
					// 	'%d', //
					// 	'%s', //
					)
				);
			}

			add_settings_error( 'settings_errors', 'settings_errors', '更新が完了しました。', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );
			$goback = add_query_arg( 'settings-updated', 'true', wp_get_referer() );
			wp_redirect( $goback );
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

	public function set_param(){
		$this->_course_name     = $_POST['course_name'] ;
		$this->_course_name_en  = $_POST['course_name_en'] ;
		$this->_target          = $_POST['target'] ;
		$this->_target_en       = $_POST['target_en'] ;
		$this->_course_text     = $_POST['course_text'] ;
		$this->_course_text_en  = $_POST['course_text_en'] ;
		$this->_display_flg     = $_POST['display_flg'] ;
		$this->_youtube_url     = $_POST['youtube_url'] ;
		$background_color       = $_POST['background_color'] ;
		$this->_background_color= str_replace('#', '', $background_color);
		$border                  = $_POST['border'] ;
		$this->_border           = str_replace('#', '', $border);
	}

	public function check_validation(){
		$err_lists = [];
		if(!$this->_course_name) array_push($err_lists,'コース名が入力されていません');
		// if(!$this->_course_name_en) array_push($err_lists,'コース名(英)が入力されていません');
		if(!$this->_target) array_push($err_lists,'対象が入力されていません');
		// if(!$this->_target_en) array_push($err_lists,'対象(英)が入力されていません');
		if(!$this->_course_text) array_push($err_lists,'詳細が入力されていません');
		// if(!$this->_course_text_en) array_push($err_lists,'詳細(英)が入力されていません');

		// array_push($err_lists,'強制終了');
		return $err_lists ;
	}

	public function delete() {
		try {
			$ID = $_POST['ID'];

			$timestamp = date('Y-m-d h:i:s');
			$user = wp_get_current_user();
			$userID = $user->ID;

			global $wpdb;
			$wpdb->update(
				'courses',
				array(
						'updated_at' => $timestamp,
						'del_flg' => '1'
				),
				array( 'id' =>  $ID )   // where句
			);

			add_settings_error( 'settings_errors', 'settings_errors', '削除が完了しました。', 'success' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );
		} catch (\Exception $e) {
			add_settings_error( 'settings_errors', 'settings_errors', '異常が発生しました。', 'error' );
			// add_settings_error()した内容を、DBに一時保存する
			set_transient( 'settings_errors', get_settings_errors(), 30 );
		} finally{
			$goback = add_query_arg( 'settings-updated', 'true', './admin.php?page=list_of_course' );
			wp_redirect( $goback);
			exit;
		}
	}
}

$course = new Course();
if ($_SERVER['REQUEST_METHOD'] === 'POST') { //POSTが渡されたら
	if($_POST['update'] )$course -> update();
	if($_POST['register'] )$course -> register();
	if($_POST['delete'] )$course -> delete();
}
