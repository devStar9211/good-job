<div class="paging pagination-post">
	
	<ul class="pagination centerPaginate paging-list pull-left ">
	    <?php
	    if($this->Paginator->counter('{:pages}') > 1) {
	        if ($this->Paginator->hasPrev()) {
                echo $this->Paginator->prev(__('Prev'), array( 'tag' => 'li', 'disabledTag' => 'a'), null, array('class' => 'prev disabled', 'escape' => false));
            }
	        echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li' ,'currentClass' => 'active', 'currentTag' => 'a' , 'escape' => false));
	        if ($this->Paginator->hasNext()) {
                echo $this->Paginator->next(__('Next'), array( 'tag' => 'li', 'disabledTag' => 'a'), null, array('class' => 'next disabled' ,'tag' => 'li', 'escape' => false));
            }
	    }
	    ?>
	</ul>  
	<div class='total-item pull-right margin'>
		<?php echo $this->Paginator->counter(array(
            'format' => __('<div id="rows_info_pag_demo_grid1" class="-bottom10">{:count}件中{:start}-{:end}件表示(ページ{:page}/{:pages})</div>')
        ));?>
	</div>
</div>