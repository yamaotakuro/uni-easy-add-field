<?php
/**
* @package UNIONNET_AddPlugin
* @version 1.0
*/
/*
Plugin Name: AddUserField
Description: ユーザーフィールドを追加するプラグイン
Author: Takuro Yamao
Version: 1.0
*/

add_action('init', 'AddUserField::init');

class AddUserField{

  static function init(){
    return new self();
  }
  public function __construct(){
    if (is_admin() && is_user_logged_in()) {
      add_action('admin_menu', [$this, 'uni_add_user_field']);
      add_action('admin_enqueue_scripts', [$this, 'admin_load_styles']);
      add_action('admin_enqueue_scripts', [$this, 'admin_load_scripts']);
      add_action('admin_print_footer_scripts', [$this, 'admin_script']);
      add_action('wp_ajax_uni_add_field_update_options', [$this, 'update_options']);
    }
  }
  
  // メニューを追加する
  public function uni_add_user_field(){
    add_menu_page(
      'ユーザーフィールド追加',
      'ユーザーフィールド追加',
      'read',
      'uni_add_field',
      [$this, 'uni_add_field'],
      plugins_url( 'images/smile.png', __FILE__ )
    );
  }
  

  //管理画面でのJS読み込み
  public function admin_load_scripts() {
    wp_enqueue_script('jquery');
  }

  //管理画面でのCSS読み込み
  public function admin_load_styles() {
    wp_enqueue_style('uni_add_field' , plugin_dir_url(__FILE__) .'css/uni_add_field.css');
  }
  
  //管理画面のJSの実行
  public function admin_script(){
    ?>
    <script>
    (function($){
      $(function(){
        
        //設定ページでのAjax(保存する)
        function setting_update(type){
          var fieldObj ={};
          var arr = [];
          var nameVal = $('.uni_add_field_name').val();
          var otherVal = $('.uni_add_field_self_other').val();
          var chiefVal = $('.uni_add_field_chief_officer').val();
          var addressVal = $('.uni_add_field_address').val();
          var telVal = $('.uni_add_field_tel').val();
          var faxVal = $('.uni_add_field_fax').val();
          var freeVal = $('.uni_add_field_free').val();
          var mailVal = $('.uni_add_field_mail').val();
          var domainVal = $('.uni_add_field_domain').val();
          var opentimeVal = $('.uni_add_field_opentime').val();
          var holidayVal = $('.uni_add_field_holiday').val();
          var uaVal = $('.uni_add_field_ua').val();

          arr.push(nameVal,otherVal,chiefVal,addressVal,telVal,faxVal,freeVal,mailVal,domainVal,opentimeVal,holidayVal,uaVal);
          fieldObj = arr;   

          $.ajax({
            url : ajaxurl,
            type : 'POST',
            data : {action : 'uni_add_field_update_options' ,uni_user_field : fieldObj  },
          })
          .done(function(data) {
            if(type =="update"){
              alert('保存しました');
            }
          })
          .fail(function() {
            window.alert('失敗しました');
          });
        }

        //保存
        $('#field_update').on('click',function(){
          setting_update('update');
        });
      
      });
    })(jQuery);

    

  </script>
  <?php
  }

  //Ajaxで受け取ったデータを保存
  public function update_options(){
    update_option('uni_user_field',$_POST['uni_user_field']);
    exit('保存しました。');
  }

  //保存したフィールドを取得
  public function get_settings(){
    $uni_user_field= get_option('uni_user_field');
    return $uni_user_field;
  }

  // メニューがクリックされた時にコンテンツ部に表示する内容(初期表示用)
  public function uni_add_field() {
    $uni_user_fields = $this->get_settings();
  ?>
  <h2>固定ユーザーフィールド用</h2>

  <form method="post" action="">
  
    <table id="uni_table">
      <tbody>
        <tr>
          <th>名称</th>
          <td><input type="text" name="uni_add_field[general-name]" class="field_data uni_add_field_name" value="<?php echo $uni_user_fields[0];?>"></td>
        </tr>
        <tr>
          <th>名称の謙譲表現</th>
          <td><input type="text" name="uni_add_field[general-self_other]" class="field_data uni_add_field_self_other" value="<?php echo $uni_user_fields[1];?>" placeholder="なし、当社、当店、当院、当園、当校、当会などを入力"></td>
        </tr>
        <tr>
          <th>代表者（個人情報管理責任者）</th>
          <td><input type="text" name="uni_add_field[general-chief_officer]" class="field_data uni_add_field_chief_officer" value="<?php echo $uni_user_fields[2];?>"></td>
        </tr>
        <tr>
          <th>所在地</th>
          <td><textarea name="uni_add_field[general-address]" class="field_data uni_add_field_address" cols="30" rows="10"><?php echo $uni_user_fields[3];?></textarea></td>
        </tr>
        <tr>
          <th>TEL</th>
          <td><input type="text" name="uni_add_field[general-tel]" class="field_data uni_add_field_tel" value="<?php echo $uni_user_fields[4];?>"></td>
        </tr>
        <tr>
          <th>FAX</th>
          <td><input type="text" name="uni_add_field[general-fax]" class="field_data uni_add_field_fax" value="<?php echo $uni_user_fields[5];?>"></td>
        </tr>
        <tr>
          <th>フリーダイアル</th>
          <td><input type="text" name="uni_add_field[general-free]" class="field_data uni_add_field_free" value="<?php echo $uni_user_fields[6];?>"></td>
        </tr>
        <tr>
          <th>代表メールアドレス</th>
          <td><input type="text" name="uni_add_field[general-mail]" class="field_data uni_add_field_mail" value="<?php echo $uni_user_fields[7];?>"></td>
        </tr>
        <tr>
          <th>メールドメイン</th>
          <td><input type="text" name="uni_add_field[general-domain]" class="field_data uni_add_field_domain" value="<?php echo $uni_user_fields[8];?>"></td>
        </tr>
        <tr>
          <th>営業時間/診療時間等</th>
          <td><textarea name="uni_add_field[general-opentime]" class="field_data uni_add_field_opentime" cols="30" rows="10"><?php echo $uni_user_fields[9];?></textarea></td>
        </tr>
        <tr>
          <th>定休日</th>
          <td><textarea name="uni_add_field[general-holiday]" class="field_data uni_add_field_holiday" cols="30" rows="10"><?php echo $uni_user_fields[10];?></textarea></td>
        </tr>
        <tr>
          <th>Google Analytics UA</th>
          <td><input type="text" name="uni_add_field[general-ua]" class="field_data uni_add_field_ua" value="<?php echo $uni_user_fields[11];?>"></td>
        </tr>
      </tbody> 
      
    </table>
    <div class="btn"><input type="button" class="button button-primary" value="保存" id="field_update" name="update"></div>
    
  </form>
  <?php
  }
}