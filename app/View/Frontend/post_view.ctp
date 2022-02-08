<div class="container">
  
  <nav id="breadcrumbs">
  <ol class="breadcrumbsList">
    <li itemprop="itemListElement" >
      <?php echo $this->Html->getCrumbs(' > ', array( 'text' => '<span itemprop="name">'.__('Home').'</span><meta property="position" content="1">', 'url' =>'/', 'escape' => false)); ?>
    </li>
    <li itemprop="itemListElement">
        <?php echo $this->Html->getCrumbs(' > ', array( 'text' => '<span itemprop="name">'.__('article list').'</span><meta property="position" content="2">', 'url' => array('controller' => 'posts', 'action' => 'index'), 'escape' => false)); ?>
      </li>
      <li itemprop="itemListElement" itemscope="">
        <span itemprop="name"><?=$post['Post']['title']?></span>
        <meta property="position" content="3">
      </li>
  </ol>
  </nav>

  <section class="main">
    <div class="main_area">
        <!-- article -->
        <article class="article single">
            <!-- thumbnail -->
            <div class="thumbnail neo-thumbnail">
            <img class="lazy-loaded alt="<?php echo $post['Post']['title']; ?>" src="<?php echo h($post['Post']['avatar']); ?>"></div>
            <div class="article_title"><h1 data-article-id="<?php echo h($post['Post']['id']); ?>"><?php echo h($post['Post']['title']); ?></h1></div>

            <div class="article_description">
              <p><?php echo $post['Post']['short_description']; ?></p>
            </div>  
            <div class="article-meta article-auther">
              <span aria-hidden="true"><?php echo date('F, Y', strtotime($post['Post']['created']));?></span>
              <i class="fa fa-pencil" aria-hidden="true"></i>
              <a href="javascript:void(0)" class="translator"><?=$post['Account']['username']?></a></aside>
            </div>        
          <!-- contents -->
          <div class="contents">
              <div class="contents_text"><?php echo $post['Post']['description']; ?> </div>
      </div>
          <!-- end .contents -->
      </article>
      <!-- end .article -->
  </div>
</section>
      <!-- Sidebar -->
      <section class="sidebar" id="sidebar">
         <h2 class="section-title"><?=__('Bài viết liên quan')?></h2>
        <div id="sidebar_ranking" class="ranking">
         
          <div class="tab-content">
            <ol id="tab-fast-climb" class="tab-pane active">
              <?php $ranking_number = 1;?>
              <?php foreach ($post_sames as $post_same): ?>
                <li>
                  <article class="sidebar_list_area" data-article-id="<?=$post_same['Post']['id']?>">
                    <a href="/posts/<?=$post_same['Post']['title']?>-<?=$post_same['Post']['id']?>" data-article-id="<?=$post_same['Post']['id']?>">
                      <div class="ranking_number_box">
                        <div class="ranking_number"><?=$ranking_number;?></div>
                      </div>
                      <p class="sidebar_list_image">
                        <img src="<?=$post_same['Post']['avatar']?>" alt="<?=$post_same['Post']['title']?>">                    
                      </p>
                      <p class="sidebar_list_title"><?=$post_same['Post']['title']?></p>
                    </a>
                  </article>
                </li>
                <?php $ranking_number++;?>
              <?php endforeach ?>
            </ol>
           
          </div>
        </div>
      </section>
      <!--end .sidebar -->
</div>
