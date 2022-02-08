<?php if (isset($posts)): ?>
  <?php foreach ($posts as $post): ?>
  <article class="article-box" data-article-id='<?=$post['Post']['id']?>'>
    <a href="/posts/<?=$post['Post']['title']?>-<?=$post['Post']['id']?>" data-article-id='<?=$post['Post']['id']?>'>
      <div class="article-thumbnail-image">
         <img class="lazy" src="<?=$post['Post']['avatar']?>" alt="<?=$post['Post']['title']?>"> 
      </div> 

      <h3 class="article-title"><?=$post['Post']['title']?></h3>
      <p class="article-description"><?=$post['Post']['short_description']?> </p>
    </a>
    <aside class="article-meta article-auther">
      <span aria-hidden="true"><?php echo date('F, Y', strtotime($post['Post']['created']));?></span> 
      <i class="fa fa-pencil" aria-hidden="true"></i>               
      <a href="javascript:void(0)" class="translator"><?=$post['Account']['username']?></a></aside>
  </article>
  <?php endforeach ?>
<?php endif ?>