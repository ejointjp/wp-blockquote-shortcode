<?php
/*
Plugin Name: WP Blockquote Shortcode
Plugin URI: http://e-joint.jp/works/wp-blockquote-shortcode/
Description: It is a WordPress plugin that makes quotation easily with Shortcode.
Version: 0.1.2
Author: e-JOINT.jp
Author URI: http://e-joint.jp
Text Domain: wp-blockquote-shortcode
Domain Path: /languages
License: GPL2
*/

/*  Copyright 2017 e-JOINT.jp (email : mail@e-joint.jp)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
     published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class wp_blockquote_shortcode
{

  private $options;
  const VERSION = '0.1.2';

  public function __construct(){

    //翻訳ファイルの読み込み
    load_plugin_textdomain('wp-blockquote-shortcode', false, basename(dirname(__FILE__)) . '/languages');

    //設定画面を追加
    add_action( 'admin_menu', array(&$this, 'add_plugin_page') );

    //設定画面の初期化
    add_action( 'admin_init', array(&$this, 'page_init') );

    //スタイルシートの読み込み
    add_action( 'wp_enqueue_scripts', array(&$this, 'add_styles') );

    //ショートコードを使えるようにする
    add_shortcode('bq', array(&$this, 'generate_shortcode') );

  }

  //設定画面を追加
  public function add_plugin_page() {

    add_options_page(
      __('WP Blockquote', 'wp-blockquote-shortcode' ),
      __('WP Blockquote', 'wp-blockquote-shortcode' ),
      'manage_options',
      'wpqb-setting',
      array(&$this, 'create_admin_page')
    );
  }

  //設定画面を生成
  public function create_admin_page() {

    $this->options = get_option( 'wpbq-setting' );
    ?>
    <div class="wrap">
      <h2>WP Blockquote Shortcode</h2>
      <?php

      global $parent_file;
      if ( $parent_file != 'options-general.php' ) {
        require(ABSPATH . 'wp-admin/options-head.php');
      }
      ?>

      <form method="post" action="options.php">
      <?php
        settings_fields( 'wpbq-setting' );
        do_settings_sections( 'wpbq-setting' );
        submit_button();
      ?>
      </form>

      <h3><?php echo __('How to Use', 'wp-blockquote-shortcode'); ?></h3>
      <p><?php echo __('1. Please drag and drop the link (bookmarklet) below to the bookmark bar.', 'wp-blockquote-shortcode'); ?></p>
      <p><a href="javascript:(function(){var d=document;var t=d.selection?d.selection.createRange().text:d.getSelection();var n='Wp Blockquote Shortcode';var p='[bq uri=&quot;'+location.href+'&quot;]';var s='[/bq]';window.prompt(n,p+t+s);void(0);})();">WP Blockquote Shortcode</a></p>
      <p><?php echo __('2. Open the web page containing the statement you want to quote in a separate tab (another window).', 'wp-blockquote-shortcode'); ?></p>
      <p><?php echo __('3. Drag the sentence you want to quote and select it.', 'wp-blockquote-shortcode'); ?></p>
      <p><?php echo __('4. With the sentence selected, click on the bookmarklet to execute it.', 'wp-blockquote-shortcode'); ?></p>
      <p><?php echo __('5. A short code will be displayed in the dialog box, please copy and paste it in the WordPress article.', 'wp-blockquote-shortcode'); ?></p>
</p>

    </div>
  <?php
  }

  //設定画面の初期化
  public function page_init(){
    register_setting('wpbq-setting', 'wpbq-setting');
    add_settings_section('wpbq-setting-section-id', '', '', 'wpbq-setting');

    add_settings_field( 'nocss', __('Do not use default CSS', 'wp-blockquote-shortcode'), array( &$this, 'nocss_callback' ), 'wpbq-setting', 'wpbq-setting-section-id' );
  }

  public function nocss_callback(){
    $checked = isset($this->options['nocss']) ? checked($this->options['nocss'], 1, false) : '';
    ?><input type="checkbox" id="nocss" name="wpbq-setting[nocss]" value="1"<?php echo $checked; ?>><?php
  }

  //スタイルシートの追加
  public function add_styles() {
    $this->options = get_option('wpbq-setting');

    if(isset($this->options['nocss'])) {
      if ( !$this->options['nocss'] ) {
        wp_enqueue_style( 'wpbq', plugins_url( 'css/wp-blockquote-shortcode.min.css', __FILE__ ), array(), null, 'all' );
      }
    } else {
      wp_enqueue_style( 'wpbq', plugins_url( 'css/wp-blockquote-shortcode.min.css', __FILE__ ), array(), null, 'all' );
    }
  }

  public function generate_shortcode($atts, $text){
    extract( shortcode_atts( array(
      'uri' => null,
    ), $atts ) );

    $parse = parse_url($uri);
    $domain = $parse['host'];

    $html = '<blockquote class="wpbq">';
    $html .= '<div class="wpbq__content">';
    $html .= '<p>' . $text . '</p>';
    $html .= '</div>';
    $html .= '<p class="wpbq__cite"><cite class="wpbq__cite__cite">' . __('Reference', 'wp-blockquote-shortcode') . ': <a class="wpbq__cite__a" href="' . esc_url($uri) . '">' . $domain . '</a></cite></p>';
    $html .= '</blockquote>';

    return $html;
  }
}

$wpbq = new wp_blockquote_shortcode();
