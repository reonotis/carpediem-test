<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * MySQL 設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link https://ja.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// 注意:
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.osdn.jp/%E7%94%A8%E8%AA%9E%E9%9B%86#.E3.83.86.E3.82.AD.E3.82.B9.E3.83.88.E3.82.A8.E3.83.87.E3.82.A3.E3.82.BF 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define( 'DB_NAME', 'carpemita_wp1' );

/** MySQL データベースのユーザー名 */
define( 'DB_USER', 'carpemita_wp1' );

/** MySQL データベースのパスワード */
define( 'DB_PASSWORD', 'uxf9rz9pko' );

/** MySQL のホスト名 */
define( 'DB_HOST', 'mysql10035.xserver.jp' );

/** データベースのテーブルを作成する際のデータベースの文字セット */
define( 'DB_CHARSET', 'utf8' );

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define( 'DB_COLLATE', '' );

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'p@(:6_-7eJ&AlNe#Y0%(V#oJ$xVf$jV+(_Mq*S^,o0X>@s)X2/{Ls24n~VXx/~>y' );
define( 'SECURE_AUTH_KEY',  '*|u3^5[lH9VSAk45XsdTq)qzP}JOVnnVc-V&GSg+`&dwtGy-xPp:UdfqZc-iQ6eV' );
define( 'LOGGED_IN_KEY',    'h~DuGb,B.Q9_6{VnyAx%X)B78dgAu==<k;Jme3c5$o2fnB-OQp!9X$QGNh<!##s<' );
define( 'NONCE_KEY',        '?^MX}njb4tEqpHl/aPE&#GqY7=NE[s#T,fTcx8(n.LLB!W4kuA:=W27`2xSnEe=6' );
define( 'AUTH_SALT',        'm/&o?uG,Th] 6+Bf-gG`^(j|,ODCS]rcaCnvSFe4QY YK;11v5N%`Z(/GRNOgDV<' );
define( 'SECURE_AUTH_SALT', '|V@y/:gtW`jdHM](-[J_E|q74_-3ly^vw=n[m{enR0M)mwOvwNG?5QSpP)(3OEy5' );
define( 'LOGGED_IN_SALT',   '`sG|ID}~gqD$@uFY<rg86*,p-d>.CHr6$Q)BY.{jy|<r/M~~2Ai8idk3R;!gVJ0n' );
define( 'NONCE_SALT',       'NBt3M:RvV3V.xIs{[+1:pt_G$D(1@^RJRe$KssBBx7Xl#j-@F>g,^f&+~n~4:r]#' );
define( 'WP_CACHE_KEY_SALT','fPD6=*iy#KoYg@{V^k*KUzWEuXzgY$6`4tD$Iki|]O|i])l3~;(dLpQMF9dlPA`M' );

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix = 'wp_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数についてはドキュメンテーションをご覧ください。
 *
 * @link https://ja.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* カスタム値は、この行と「編集が必要なのはここまでです」の行の間に追加してください。 */



/* 編集が必要なのはここまでです ! WordPress でのパブリッシングをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
