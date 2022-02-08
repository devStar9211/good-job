<form class="form-horizontal" method="POST">
    <div class=" row products form">
        <div class="form-group">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">アバター</label>
            <div class="controls col-xs-12 col-sm-8">
                <img src="<?php echo $current_user['profile_picture']!=''?$current_user['profile_picture']:DEFAULT_AVATAR?>" width="100" height="100"/>
            </div>
        </div>
        <div class="form-group required">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">名前</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[full_name]" class="form-control" maxlength="32" type="text"  value="<?php echo $current_user['full_name']?>" required="true">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">メール</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[email]" class="form-control" maxlength="32" type="text"  value="<?php echo $current_user['email']?>">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">インスタグラムユーザー名</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[instagram_username]" class="form-control" maxlength="32" type="text" disabled="true" value="<?php echo $current_user['instagram_username']?>">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">全てフォロー</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[instagram_username]" class="form-control" maxlength="32" type="text" disabled="true" value="<?php echo $current_user['total_follows']!=''?$current_user['total_follows']:0?>">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">モデル管理局</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[instagram_username]" class="form-control" maxlength="32" type="text" disabled="true" value="<?php echo $current_user['Manager']['username'].'-'.$current_user['Manager']['full_name']?>">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">同期は更新されました。</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[sync_updated]" class="form-control" maxlength="32" type="text" disabled="true" value="<?php echo date('Y/m/d H:i:s',  strtotime($current_user['sync_updated']))?>">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">最終同期</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[sync_updated]" class="form-control" maxlength="32" type="text" disabled="true" value="<?php echo date('Y/m/d H:i:s',  strtotime($current_user['updated']))?>">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <label for="full_name" class="control-label col-xs-12 col-sm-2">作成日</label>
            <div class="controls col-xs-12 col-sm-8">
                <input name="data[sync_updated]" class="form-control" maxlength="32" type="text" disabled="true" value="<?php echo date('Y/m/d',  strtotime($current_user['created']))?>">
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="form-group">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <input type="submit" class="btn btn-primary" value="変更する"/>
                <input type="reset" class="btn btn-default" value="リセット"/>
            </div>
        </div>
    </div>
</form>
