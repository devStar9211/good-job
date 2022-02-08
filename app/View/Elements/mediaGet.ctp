<?php $this->start('css')?>
<?php //echo $this->Html->css('media'); ?>
<?php $this->end()?>
<div id="media">
  <!-- Button trigger modal -->
  <a class="media-get" href="javascript:void(0)" data-toggle="modal" data-target="#mediaModalGet">
    アイキャッチ画像を設定
  </a>

  <!-- Modal -->
  <div class="modal fade" id="mediaModalGet" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 96%; height: 100%;">
      <div class="modal-content" style="height: 91%">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h4 class="modal-title" id="myModalLabel">メディアを挿入</h4>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs">
            <li id="upload-media-get"><a data-toggle="tab" href="#upload-files-get">ファイルをアップロード</a></li>
            <li id="media-list-get" class="active"><a data-toggle="tab" href="#media-library-get">メディアライブラリ</a></li>
          </ul>
          <div class="tab-content">
            <div id="upload-files-get" class="tab-pane fade upload-files">
              <div id="uploadGet" class="btn btn-default btn-sm"><span>ファイルを選択<span></div>
              <span id="statusGet"></span>
            </div>
            <div id="media-library-get" class="tab-pane fade in active">
              <div class="">
                <div class="">
                  <div id="filter-media">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <select name="" id="filter_date_get" class="form-control" required="required">
                          <option value="all" selected="selected">すべての日付</option>
                          <?php
                            $today =  date('Y-m-1');
                            while (date('Y', strtotime($today)) >= 2017) {?>
                               <option value="<?=$today?>"><?php echo date('F Y', strtotime($today));?></option>
                              <?php $month_plus = date('Y-m-d', strtotime($today . " -1 month"));
                              $today = $month_plus;
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <input type="text" class="form-control" id="key-search-file-get" placeholder="検索">
                      </div>
                    </div> 
                  </div>

                   <div id="list-media-get"  class="list-media">
                      <?php $medias = $this->requestAction(['controller' => 'Posts', 'action' => 'getListMedia']);?>
                        <?php if (!empty($medias)): ?>
                          <ul>
                          <?php foreach ($medias as $media): ?>
                              <li id="li-get-<?=$media['Post']['id']?>">
                              <input type="hidden" name="value-id-get" id ="value-id-get" value="<?=$media['Post']['id']?>">
                              <input type="checkbox" id="cbget<?=$media['Post']['id']?>" name="check_image_get[]" value="<?=$media["Post"]["short_description"]?>"/>
                              
                              <label for="cbget<?=$media['Post']['id']?>"><img src="<?=$media["Post"]["short_description"]?>" /></label>
                            </li>
                          <?php endforeach ?>
                          </ul>
                      <?php else: ?>
                        <div class="media-empty" style="font-weight: bold;text-align: center; margin-top: 40px;">
                          どのフォルダーも見つかりません。
                        </div>
                      <?php endif ?>
                  </div>
                </div>
              </div>
              
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="col-xs-12">
            <button type="button" id="delete-media-get" class="btn btn-primary pull-left" disabled='disabled' >削除</button>
            <button type="button" id="get-into-post" data-dismiss="modal" class="btn btn-primary" disabled='disabled' >投稿に挿入</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->start('script')?>
<?php echo $this->Html->script('ajaxupload.3.5'); ?>
<?php echo $this->Html->script('media'); ?>
<?php $this->end()?>