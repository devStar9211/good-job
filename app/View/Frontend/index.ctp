<?php $this->start('css') ?>
<?php echo $this->Html->css('../../assets/css/l_css'); ?>
<?php echo $this->Html->css('../../assets/css/g_css'); ?>
<?php $this->end() ?>
<?php
$this->start('script');
echo $this->Html->script([
    '/assets/js/format_number.js'
]);
$this->end();
?>
<div id="home">
    <div class="container">
        <div class="row home-button">
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <a href="/daily_settlement">
                    <img src="/img/bt_nichiji.png" class="none"/>
                    <img src="/img/bt_nichiji_or.png" class="hover"/>
                </a>
            </div>
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <a href="/ranking">
                    <img src="/img/bt_ranking.png" class="none">
                    <img src="/img/bt_ranking_or.png" class="hover">
                </a>
            </div>
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <a href="/calendar">
                    <img src="/img/bt_calendar.png" class="none">
                    <img src="/img/bt_calendar_or.png" class="hover">
                </a>
            </div>
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <a href="https://www.caregiver-manual.com" target="_blank">
                    <img src="/img/bt_manual.png" class="none">
                    <img src="/img/bt_manual_or.png" class="hover">
                </a>
            </div>
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <a target="_blank" href="https://test.good-job.online/">
                    <img src="/img/bt_test.png" class="none">
                    <img src="/img/bt_test_or.png" class="hover">
                </a>
            </div>
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <div class="image">
                    <a href="https://www.caregiver-manual.com/contact-1">
                        <img src="/img/bt_contact.png" class="none">
                        <img src="/img/bt_contact_or.png" class="hover">
                    </a>
                </div>
            </div>
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <div class="image">
                    <a href="https://www.caregiver-manual.com/order">
                        <img src="/img/bt_order.png" class="none">
                        <img src="/img/bt_order_or.png" class="hover">
                    </a>
                </div>
            </div>
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <a href="https://shift.good-job.online/" target="_blank">
                    <img src="/img/bt_shift.png" class="none">
                    <img src="/img/bt_shift_or.png" class="hover">
                </a>
            </div>
            <div class="col col-md-1 col-xs-4 btn-item no-padding">
                <div class="image">
                    <a href="/my_page/">
                        <img src="/img/bt_mypage.png" class="none">
                        <img src="/img/bt_mypage_or.png" class="hover">
                    </a>
                </div>
            </div>


        </div>
    </div>

    <div class="center center-block wrapper">
        <section class="">
            <section class="latest article-rectangle">
                <div class="container">
                    <?php echo $this->element('flash-message'); ?>
                    <div class="">
                        <h2 class="section-title"><?php echo __('Bài viết mới nhất') ?> <?= $this->Html->link(__('詳細を見る'), array('controller' => 'posts', 'action' => 'index',), array("class" => "dropdown-toggle", "role" => "button", "aria-haspopup" => "true", "aria-expanded" => "false")) ?></h2>
                        <div class="article-list clearfix" id="article-list">
                            <?= $this->element('post', array('posts' => $posts)) ?>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <div class="container">
        <div class="">
            <div class="home-ranking ranking">
                <h2 class="section-title">
                    <span class="bg_rk_head_title">予算達成率ランキング（<?php echo date('m / d') ?> 更新）</span>
                </h2>
                <?php echo $this->element('budget_ranking_data', array('data' => $data)) ?>
            </div>
        </div>
    </div>
</div>


