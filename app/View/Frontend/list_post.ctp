<?php $this->start('css')?>
<?php echo $this->Html->css('../../assets/css/l_css'); ?>
<?php $this->end()?>
<!-- <div class="container">
  <div class="">
    <nav id="breadcrumbs">
      <ol class="breadcrumbsList">
        <li itemprop="itemListElement" >
          <?php //echo $this->Html->getCrumbs(' > ', array( 'text' => __('<span itemprop="name">Home</span><meta property="position" content="1">'), 'url' =>'/', 'escape' => false)); ?>
        </li>

        <li itemprop="itemListElement" itemscope="">
          <span itemprop="name">posts</span>
          <meta property="position" content="3">
        </li>
      </ol>
    </nav>
  </div>
</div> -->

<div id="list-post">
  <!-- main -->
  <section class="">
    <div class="container">
      <div class="">
        <section class="latest article-rectangle">
          <h2 class="section-title"><?php echo __('Danh sách bài viết')?></h2>
            <div class="article-list clearfix" id="article-list">
              <?=$this->element('post', array('posts' => $posts))?>
            </div>
          <div class="ajax-load text-center col-md-12" style="display:none">
              <p><?=$this->html->image('/img/loader.gif')?><?php echo __('Loadding')?></p>
          </div>
        </section>
      </div>
    </div>
  </section>
  <!-- end .main -->
</div>

<?php $this->start('script') ?>
    <script type="text/javascript">
        var page = 1;
        var check = true;
        $(window).scroll(function () {
            if ($(window).scrollTop() + $(window).height() >= $(document).height() && check) {
                page++;
                $.ajax({
                    url: '/posts/loadPost',
                    type: "get",
                    data: {
                        'page': page
                    },
                    beforeSend: function () {
                        $('.ajax-load').show();
                    },
                })
                    .done(function (data) {
                        $('.ajax-load').hide();
                        $(".article-list").append(data);

                        if(data == ''){
                            check = false;
                        }
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        $('.ajax-load').html("");
                        check = false;
                    });
            }
        });
    </script>

<?php $this->end()?>