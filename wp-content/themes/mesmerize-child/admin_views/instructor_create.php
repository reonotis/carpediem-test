
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
                    <th scope="row"><label for="instructor_name_en">インストラクター名(英) <span class="description" >(必須)</span></label></th>
                    <td><input name="instructor_name_en" type="text" value="<?= $_GET['instructor_name_en'] ?>" id="instructor_name" style="width:25em;" ></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="email">メールアドレス <span class="description">(必須)</span></label></th>
                    <td><input name="email" type="email" value="<?= $_GET['email'] ?>" id="email" style="width:25em;" ></td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="register" value="register" >
        <input type="submit" name="" value="送信" >
    </form>
</div>


<script type="text/javascript">


</script>

