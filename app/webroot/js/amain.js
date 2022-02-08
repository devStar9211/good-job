// process form
$('#search-button').click(function(e){
    $('.glyphicon-collapse').toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
})
function resetForm($form) {
    $form.find('input:text, input:password, input:file, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
        .removeAttr('checked').removeAttr('selected');
}
$("#reset").click(function() {
    resetForm($('#form_site'));
    return false;
});
