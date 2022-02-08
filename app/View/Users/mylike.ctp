<?php if(count($list_post)):?>
    <div class="list">
        <?php foreach ($list_post as $post):?>
        <!--begin item-->
        <div class="list-mylike">
            <div class="row">
                <div class="mylike col-xs-12">
                    
                        <a href="<?php echo $this->Html->url(array( 'controller' => 'usermodels', 'action' => 'timeline',  $post['User']['id']), true); ?>">
                            <img class="img-circle" src="<?php echo $post['User']['profile_picture']; ?>"/>
                        </a>
                        <h4><a href="<?php echo $this->Html->url(array( 'controller' => 'usermodels', 'action' => 'timeline',  $post['User']['id']), true); ?>"><?php echo $post['User']['username']?></a></h4>
                        <p>
                            <?php echo $post['User']['full_name']?>
                            <?php if($post['User']['website'] !='' || empty($post['User']['website'])):?>
                                <br><a target="_blank" href="<?php echo $post['User']['website']?>"><?php echo $post['User']['website']?></a>
                            <?php endif;?>
                        </p>
                    
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 model-posts-block model-posts-detail">
                    <div class="post-block">
                        <div class="model-img">
                            <a href="<?php echo $this->Html->url(array( 'controller' => 'usermodels', 'action' => 'detail',  $post['Post']['id']), true); ?>?page=mylike">
                                <img class="img-responsive center-block" src="<?php echo $post['Post']['thumbnail_url']?>"/>
                            </a>
                        </div>
                        <!-- <div class="main-info">
                            <img src="<?php //echo $this->webroot?><?php //echo 'images/heart-active.png';?>"/>
                            <span>いいね！<span style="color:#ff4650"><?php //echo $post['Post']['total_likes']?></span>件</span>
                        </div> -->
                    <div class="thumbnail clearfix">
                        <?php foreach ($post['Product'] as $promotion): ?>
                            <div class="col-xs-3 thumb-img">
                                <a href="<?php echo $this->App->affiliate_url($promotion['affiliate_url'],$post['User']['username'])?>" target="_blank">
                                    <img class="img-responsive center-block" src="<?php echo $promotion['product_img'] ?>"/>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    </div>
                </div>
            </div>
            </div>
        <!--end item-->
       <?php endforeach; ?>
    </div>
    <?php if($count_page > 1):?>
        <div class="row">
            <div class="col-xs-12 text-center">
                <button type="button" class="btn btn-default center-block" id="load_more_post" url="<?php echo $this->Html->url(array( 'controller' => 'users', 'action' => 'ajaxmylike' ), true); ?>">更新する</button>
            </div>
        </div>
    <?php endif;?>
<?php else:?>
    <div class="col-xs-12"><div class="static-page">表示するようにデーターはありません。</div></div>
<?php endif;?>
<script>
    var count_page = <?php echo $count_page?>;
    var current_page = 1;
</script>