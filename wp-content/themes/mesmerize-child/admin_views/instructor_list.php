
<div class="wrap">

    <?php if($_GET['ID']) : ?>
        <!-- 編集画面 -->
        <h1 class="wp-heading-inline">先生編集画面</h1>



    <?php else : ?>
        <!-- 一覧画面 -->
        <h1 class="wp-heading-inline">先生一覧</h1>
        <a href="http://paralymbics.jp/wp-admin/admin.php?page=instructor_new" class="page-title-action">新規追加</a>

        <?php settings_errors(); ?>
        <!-- <table class="widefat fixed striped"> -->
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>キャリア</th>
                    <th>性別</th>
                    <th>コメント</th>
                    <th>HP表示</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($instructors){
                    foreach($instructors as $instructor){
                        ?>
                            <tr>
                                <th><?= $instructor->instructor_name ?></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><a href="./admin.php?page=edit_teacher_page&ID=<?= $instructor->id ?>" >編集</a></th>
                                <th></th>
                            </tr>
                        <?php
                    }

                }else{
                    echo "<tr><td colspan='7'>公表されている開催予定はありません</td></tr>";
                }
                ?>
            </tbody>
        </table>

    <?php endif ; ?>
</div>





<script type="text/javascript">


</script>

