<?php if(!empty($config_js_validate)){  
if(!empty($config_js_validate['addMethod'])){    
    foreach ($config_js_validate['addMethod'] as $value) {
        echo '$.validator.addMethod("'. $value .'", function(value, element, regexpr) {          
            return regexpr.test(value);
        });';
    }
}
?>

$("#<?php echo $config_js_validate['form_id']; ?>").validate({
    rules:{
        <?php  
        foreach ($config_js_validate['fields'] as $key => $value) {
        ?>                     
            "<?php echo $key; ?>":{
                <?php  
                foreach ($value as $_key => $_value) {
                    echo $_key.':'.$_value[0].',';    
                }    
                ?>
            },
        <?php 
        }
        ?>
    },                  
    messages:{
        <?php  
        foreach ($config_js_validate['fields'] as $key => $value) {
        ?>                     
            "<?php echo $key; ?>":{
                <?php  
                foreach ($value as $_key => $_value) {
                    echo $_key . ":'" . '<label style="color: #dd4b39 !important;"  class="control-label" before="<i class=&quot;fa fa-times-circle-o&quot;></i> "><i class="fa fa-times-circle-o"></i>'.$_value[1].'</label>'."',";
                }
                ?>
            },
        <?php 
            
        }            
        ?>      
    }
});
<?php } ?>

