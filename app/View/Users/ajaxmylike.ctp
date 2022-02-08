<?php foreach ($list_post as $post):?>
    <!--begin item-->

        <div class="list-mylike">
            <div class="row">
                <div class="mylike col-xs-12">
                    <a href="<?php echo $this->Html->url(array( 'controller' => 'usermodels', 'action' => 'timeline',  $post['User']['id']), true); ?>">
                        <img class="img-circle" src="<?php echo $post['User']['profile_picture']; ?>"/>
                    </a>
                    <h4>
                        <a href="<?php echo $this->Html->url(array( 'controller' => 'usermodels', 'action' => 'timeline',  $post['User']['id']), true); ?>"><?php echo $post['User']['username']?></a>
                    </h4>
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
