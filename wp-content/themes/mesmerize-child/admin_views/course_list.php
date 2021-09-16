
<div class="wrap">

        <h1 class="wp-heading-inline">コース一覧</h1>
        <a href="./admin.php?page=register_course_page" class="page-title-action">新規追加</a>

        <?php settings_errors(); ?>
        <!-- <table class="widefat fixed striped"> -->
        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>コース名</th>
                    <th>対象</th>
                    <th>詳細</th>
                    <th>HP表示</th>
                    <th>並び替え</th>
                    <th>編集</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if($courses){
                    foreach($courses as $course){
                        ?>
                            <tr>
                                <td>
                                    <div class="" style="background:#<?= $course->background ?>;border:solid 1px #<?= $course->border ?>;padding:5px;">
                                        <?= $course->course_name ?><br>
                                        <?= $course->course_name_en ?>
                                    </div>
                                </td>
                                <td>
                                    <?= $course->target ?><br>
                                    <?= $course->target_en ?>
                                </td>
                                <td>
                                </td>
                                <td><?= $course->display_name ?></td>
                                <td>
                                </td>
                                <td><a href="./admin.php?page=edit_course_page&ID=<?= $course->id ?>" >編集</a></td>
                                <td>
                                    <form action="controller_course.php" method="post" >
                                        <input type="hidden" name="ID" value="<?= $course->id ?>" >
                                        <input type="submit" name="delete" value="削除" onclick="return aaa();" >
                                    </form>
                                </td>
                            </tr>
                        <?php
                    }

                }else{
                    echo "<tr><td colspan='7'>登録されているコースはありません</td></tr>";
                }
                ?>
            </tbody>
        </table>
</div>





<script type="text/javascript">
    function aaa(){
        var result = window.confirm('このコースを削除しますか？');
        if( result ) return true; return false;
    }

</script>

