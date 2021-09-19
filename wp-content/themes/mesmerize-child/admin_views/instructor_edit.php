<?php
    if(!$_GET['ID']){
        echo "<h2 class='wp-heading-inline'>先生一覧から編集する先生を選択してください</h2>";
        exit;
    }else{
        $instructor = get_instructor($_GET['ID']);
        if(!$instructor){
            echo "不正なアクセスを検知しました。";
            exit;
        }
    }
?>

<div class="wrap">

    <!-- 一覧画面 -->
    <h1 class="wp-heading-inline">インストラクター編集</h1>

    <?php settings_errors(); ?>
    <!-- <table class="widefat fixed striped"> -->

    <form name="createuser" action="controller_instructor.php" method="post" >
        <?php settings_fields( 'company_settings' ); ?>
        <?php do_settings_sections( 'company_settings' ); ?>

        <table class="form-table" role="presentation" >
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="instructor_name">インストラクター名 <span class="description" >(必須)</span></label></th>
                    <td><input name="instructor_name" type="text" value="<?= $instructor->instructor_name ?>" id="instructor_name" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="instructor_level">タイトル<span class="description" >(必須)</span></label></th>
                    <td><input name="instructor_level" type="text" value="<?= $instructor->instructor_level ?>" id="instructor_level" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="email">メールアドレス <span class="description">(必須)</span></label></th>
                    <td><input name="email" type="email" value="<?= $_GET['email'] ?>" id="email" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="award">アワード</label></th>
                    <td><input name="award" type="text" value="<?= $instructor->award ?>" id="award" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="faceBook">SNS</label></th>
                    <td>
                            FB      　<input name="faceBook" type="url" value="" id="faceBook" style="width:25em;" ><br>
                            IG      　<input name="instagram" type="url" value="" id="instagram" style="width:25em;" ><br>
                            TW      　<input name="twitter" type="url" value="" id="twitter" style="width:25em;" >
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="introduction">自己紹介文</label></th>
                    <td>
                        <textarea name="" style="width:50em;" id="introduction" ></textarea>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="course_text">画像</label></th>
                    <td>
                        <div id="stylist_img_url_thumbnail" class="uploded_thumbnail">
                            <img src="<?= wp_get_attachment_url( $instructor->img_pass ) ?>" alt="" >
                        </div>
                        <input type="button" name="stylist_img_url_slect" value="選択" />
                        <input type="button" name="stylist_img_url_clear" value="クリア" />
                        <input type="hidden" name="stylist_img_url_id" value="<?= $instructor->img_pass ?>" />
                        <?php generate_upload_image_tag( get_option('stylist_img_url')); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="id" value="<?= $instructor->id ?>" >
        <input type="hidden" name="update" value="update" >
        <input type="submit" name="" value="更新する" >
    </form>
</div>


<script type="text/javascript">


</script>

