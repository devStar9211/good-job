
   <form method="post">
        <span class="text-warning">本当に退会されますか？<br/>
            退会すると、全てのMy Like!データが失われてしまいます。<br/>
            元に戻すことはできませんので、ご了承ください。
        </span>
        <span class="login-line col-xs-12 center-block">
            <a  href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'mylike'), true) ?>" class="submit-button bg-black reset-m"> <span class="arrive-title text-uppercase">いいえ</span></a>
        </span>
        <span class="login-del col-xs-12 center-block">
            <button class="submit-button bg-white reset-m" type="submit"> <span class="arrive-title text-uppercase ">はい</span></button>
        </span>
   </form>
