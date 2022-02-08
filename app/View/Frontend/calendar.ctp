<?php
$this->start('css');
echo $this->Html->css([
    '/assets/css/g_css.css',
    '/assets/css/n_style.css',
]);
$this->end();

?>
<div class="calendar-page my-container container">
    <div class="row mb10">
        <iframe src="https://calendar.google.com/calendar/embed?src=uusfg26ki4nq2cqesm9oaus5j4%40group.calendar.google.com&ctz=Asia/Tokyo" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
    </div>
</div>