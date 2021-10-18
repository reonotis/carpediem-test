
<div class="wrap">

    <!-- 一覧画面 -->
    <h1 class="wp-heading-inline">インストラクター登録</h1>

    <?php settings_errors(); ?>
    <!-- <table class="widefat fixed striped"> -->

    <form name="createuser" action="controller_instructor.php" method="post" >
        <?php settings_fields( 'company_settings' ); ?>
        <?php do_settings_sections( 'company_settings' ); ?>

        <table class="form-table" role="presentation" >
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="instructor_name">インストラクター名 <span class="description" >(必須)</span></label></th>
                    <td><input name="instructor_name" type="text" value="<?= $_GET['instructor_name'] ?>" id="instructor_name" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="instructor_level">タイトル<span class="description" >(必須)</span></label></th>
                    <td><input name="instructor_level" type="text" value="<?= $_GET['instructor_level'] ?>" id="instructor_level" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="band_colour">帯色<span class="description" >(必須)</span></label></th>
                    <td><input name="band_colour" type="text" value="<?= $_GET['band_colour'] ?>" id="band_colour" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="email">メールアドレス <span class="description">(必須)</span></label></th>
                    <td><input name="email" type="email" value="<?= $_GET['email'] ?>" id="email" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="faceBook">SNS</label></th>
                    <td>
                            FB      　<input name="faceBook_url" type="url" value="<?= $_GET['faceBook_url'] ?>" id="faceBook" style="width:25em;" ><br>
                            IG      　<input name="instagram_url" type="url" value="<?= $_GET['instagram_url'] ?>" id="instagram" style="width:25em;" ><br>
                            TW      　<input name="twitter_url" type="url" value="<?= $_GET['twitter_url'] ?>" id="twitter" style="width:25em;" >
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="introduction">自己紹介文</label></th>
                    <td>
                        <textarea name="introduction" style="width:40em;min-height:200px;" id="introduction" ><?= nl2br($_GET['introduction']) ?></textarea>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="display_flg">HPへの表示</label></th>
                    <td>
                        <label><input type="radio" name="display_flg" value="1" <?php if($_GET['display_flg'] == 1) echo " checked='checked'"; ?> >表示</label>
                        <label><input type="radio" name="display_flg" value="0" <?php if($_GET['display_flg'] == 0) echo " checked='checked'"; ?> >非表示</label>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="display_flg">プライベートレッスン</label></th>
                    <td>
                        1回<input name="lesson_fee" type="lesson_fee" value="<?= $_GET['lesson_fee'] ?>" id="lesson_fee" style="width:10em;" >円<br>
                        4回<input name="lesson_fee_4time" type="lesson_fee_4time" value="<?= $_GET['lesson_fee_4time'] ?>" id="lesson_fee_4time" style="width:10em;" >円
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="register" value="register" >
        <input type="submit" name="" value="登録" >
    </form>
</div>


<script type="text/javascript">


</script>

