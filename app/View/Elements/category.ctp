<div id="add-category">
  <div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">カテゴリー</h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="box-body">
    <?php $categories = $this->requestAction(['controller' => 'Categories', 'action' => 'categoryList']);?>

      <div class="list-category">
        <?php if (!empty($ct_checks)): ?>
          <?php foreach ($categories as $category): ?>
          <?php 
            $status_ct = false;
            foreach ($ct_checks as $value){
              if ($value['id'] == $category['Category']['id']){
                $status_ct = true;
                break;
              }
            }
          ?>
            <?php if ($status_ct == true): ?>
              <p>
                <input  data-id="checkbox"  type="checkbox" name="data[Post][category][]" value="<?=$category['Category']['id']?>" id="<?=$category['Category']['id']?>" checked>
                <label for="<?=$category['Category']['id']?>"></label>
                <label><?=$category['Category']['name']?></label>
              </p>
              <?php else: ?>
               <p>
                <input  data-id="checkbox"  type="checkbox" name="data[Post][category][]" value="<?=$category['Category']['id']?>" id="<?=$category['Category']['id']?>">
                <label for="<?=$category['Category']['id']?>"></label>
                <label><?=$category['Category']['name']?></label>
              </p>
            <?php endif ?>
           
          <?php endforeach ?>
        <?php else: ?>
          <?php foreach ($categories as $category): ?>
            <p>
              <input  type="checkbox" name="data[Post][category][]" value="<?=$category['Category']['id']?>" id="<?=$category['Category']['id']?>">
              <label for="<?=$category['Category']['id']?>"></label>
              <label><?=$category['Category']['name']?></label>
            </p>
          <?php endforeach ?>
        <?php endif ?>
      </div>
      
      <div class="form-group">
        <a id="show-hidden-form" href="javascript:void(0)" class="hide-if-no-js taxonomy-add-new">  + 新規カテゴリーを追加</a>
      </div>
      <div class="form-add-category">
        <div class="form-group">
          <input type="text" name="newcategory" id="newcategory" class="form-control">
        </div>
        
        <div class="form-group">
          <select name="newcategory_parent" id="newcategory_parent" class="form-control" >
            <option value="-1">— 親カテゴリー —</option>
            <?php foreach ($categories as $category): ?>
              <option class="level-0" value="<?=$category['Category']['id']?>"><?=$category['Category']['name']?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group btn-add-ajax">
          <div class="btn btn-default btn-ms" id="add-category-ajax">新規カテゴリーを追加</div>
        </div>
      </div>
  </div>
</div>

  <?php $this->start('script')?>
    <script type="text/javascript">
    $(function(){
      $('#show-hidden-form').click(function(event) {
        $(".form-add-category").toggle(1000);
      });
      $('#add-category-ajax').on('click', function(event) {
        var newcategory = $('#newcategory').val();
        if(newcategory == ""){
          $('#newcategory').focus();
        }else{
          var parent_id = $('#newcategory_parent').val(); 
          $.ajax({
            url: '/admin/categories/addAjax',
            type: 'GET',
            data: {
              'newcategory': newcategory,
              'parent_id': parent_id
            },
            beforeSend: function(){
              // $('#list-media').html('<div class="loadding-media" class=""><i class="fa fa-refresh fa-spin"></i> Loading</div>');
            },
            success: function( msg ) {
              // $('#list-media').html(msg);
              if(msg != "error"){
                var html = '<p>';
                      html += '<input  type="checkbox" name="data[Post][category][]" value="' + msg + '" id="'+msg+'" checked>';
                      html += '<label for="'+msg+'"></label>';
                      html += '<label>' + newcategory + '</label>';
                    html += '</p>';
                $('.list-category').prepend(html);

                var html1 = '<option class="level-0" value="' + msg + '">' + newcategory + '</option>';
                $('#newcategory_parent').append(html1);

                $('#newcategory').text('');
              }
            }
          });
        }
      });
    });
  </script>
  <?php $this->end()?>
</div>