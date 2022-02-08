<?php $this->start('css')?>
<?php echo $this->Html->css('../../assets/css/l_css'); ?>
<?php echo $this->Html->css('../../assets/css/g_css'); ?>
<?php $this->end()?>

<div id="home">
  <div class="container">
    <div class="row">
      <div class="col-md-3 col-xs-6 col-yellow">
        <!-- small box -->
        <div class="small-box bg-yellow">
          <div class="icon">
            <a href="/daily_settlement"><img src="/img/bt_nichiji.png"/></a>
          </div>
          <div class="inner">
            <p><a href="/daily_settlement">日次決算</a></p>
          </div>
        </div>
      </div>

      <!-- ./col -->
      <div class="col-md-3 col-xs-6 col-red">
        <!-- small box -->
        <div class="small-box bg-red">
          <div class="image">
            <a href="/budget_ranking"><img src="/img/bt_menu_ranking.png" alt=""></a>
          </div>
          <div class="inner">
            <p><a href="/budget_ranking">予算達成ランキング</a></p>
          </div>
        </div>
      </div>
      <!-- ./col -->

      <div class="col-md-3 col-xs-6 col-aqua">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="image">
            <a href="/comparisons"><img src="/img/bt_menu_ranking.png" alt=""></a>
          </div>
          <div class="inner">
            <p><a href="/comparisons">昨年対比ランキング</a></p>
          </div>
        </div>
      </div>
      <!-- ./col -->

      <div class="col-md-3 col-xs-6 col-green">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="icon">
            <a href="/my_page/"><i class="fa fa-user" aria-hidden="true"></i></a>
          </div>
          <div class="inner">
            <p><a href="/my_page/">マイページ</a></p>
          </div>
        </div>
      </div>
    </div>    
  </div>

  <div class="center center-block wrapper">
    <section class="">
      <section class="latest article-rectangle">
      <div class="container">
        <div class="row">
          <h2 class="section-title"><?php echo __('Bài viết mới nhất')?> <?= $this->Html->link(__('詳細を見る'), array('controller' => 'posts', 'action' => 'index',), array("class"=>"dropdown-toggle", "role"=>"button", "aria-haspopup"=>"true", "aria-expanded"=>"false")) ?></h2>
          <div class="article-list clearfix" id="article-list">
            <?=$this->element('post', array('posts' => $posts))?>
          </div>
        </div>
      </div>
      </section>
    </section>
  </div>
  <div class="container">
    <div class="row">
      <div class="ranking">
        <h2 class="section-title">予算達成率ランキング（<?php echo date('m / d')?> 更新）</h2>
        <?php $this->element('budget_ranking_data', array('data' => $data))?>
      </div>
    </div>
  </div>
</div>