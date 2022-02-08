/*upload file*/
$(function(){
  var settings = {
      url: "/admin/posts/addmediaAjax",
      method: "POST",
      allowedTypes: "jpg,png,gif",
      fileName: "img_name",
      multiple: true,
      onSuccess: function (files, data, xhr) {
        $('#upload-media').removeClass('active');
        $('#media-list').addClass('active');

        $('#upload-files').removeClass('active');
        $('#upload-files').removeClass('in');

        $('#media-library').addClass('active');
        $('#media-library').addClass('in');

        var data = JSON.parse(data);

        var html ='<li id="li-'+data.id+'">';
              html += '<input type="hidden" name="value-id" id ="value-id" value="'+data.id+'">';
              html += '<input type="checkbox" id="cb' + data.id + '" name="check_image[]" value="'+data.avatar+'" checked/>';
              html += '<label for="cb' + data.id + '"><img src="'+data.avatar+'"/></label>';
            html += '</li>';

        var htmlGet ='<li id="li-get-'+data.id+'">';
              htmlGet += '<input type="hidden" name="value-id-get" id ="value-id-get" value="'+data.id+'">';
              htmlGet += '<input type="checkbox" id="cbget' + data.id + '" name="check_image_get[]" value="'+data.avatar+'"/>';
              htmlGet += '<label for="cbget' + data.id + '"><img src="'+data.avatar+'"/></label>';
            htmlGet += '</li>';

        $('#list-media ul').prepend(html);
        $('#list-media-get ul').prepend(htmlGet);
        
        $('#insert-into-post').attr('disabled', false);
        $('#delete-media').attr('disabled', false);
        check_image();
      },
      onError: function (files, status, errMsg) {
      }
  }
  $("#upload").uploadFile(settings);

  // var btnUpload=$('#upload');
  // var status=$('#status');
  // new AjaxUpload(btnUpload, {
  //   multiple: true,
  //   action: '/admin/posts/addmediaAjax',
  //   name: 'uploadfile',
  //   onSubmit: function(file, ext){
  //      if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
  //       // extension is not allowed 
  //       status.text('Only JPG, PNG or GIF files are allowed');
  //       return false;
  //     }
  //     status.text('Uploading...');
  //   },
  //   onComplete: function(file, response){
  //     //On completion clear the status
  //     status.text('');

  //     //Add uploaded file to list
  //     if(response != "error"){
  //       $('#upload-media').removeClass('active');
  //       $('#media-list').addClass('active');

  //       $('#upload-files').removeClass('active');
  //       $('#upload-files').removeClass('in');

  //       $('#media-library').addClass('active');
  //       $('#media-library').addClass('in');

  //       var data = JSON.parse(response);

  //       var html ='<li id="li-'+data.id+'">';
  //             html += '<input type="hidden" name="value-id" id ="value-id" value="'+data.id+'">';
  //             html += '<input type="checkbox" id="cb' + data.id + '" name="check_image[]" value="'+data.avatar+'" checked/>';
  //             html += '<label for="cb' + data.id + '"><img src="'+data.avatar+'"/></label>';
  //           html += '</li>';

  //       var htmlGet ='<li id="li-get-'+data.id+'">';
  //             htmlGet += '<input type="hidden" name="value-id-get" id ="value-id-get" value="'+data.id+'">';
  //             htmlGet += '<input type="checkbox" id="cbget' + data.id + '" name="check_image_get[]" value="'+data.avatar+'"/>';
  //             htmlGet += '<label for="cbget' + data.id + '"><img src="'+data.avatar+'"/></label>';
  //           htmlGet += '</li>';

  //       $('#list-media ul').prepend(html);
  //       $('#list-media-get ul').prepend(htmlGet);
        
  //       $('#insert-into-post').attr('disabled', false);
  //       $('#delete-media').attr('disabled', false);
  //       check_image();
  //     } else{
  //       status.text('Lỗi không tải lên được');
  //     }
  //   }
  // });

  var btnUploadGet=$('#uploadGet');
  var statusGet=$('#statusGet');
  new AjaxUpload(btnUploadGet, {
    action: '/admin/posts/addAvatar',
    name: 'uploadfile',
    onSubmit: function(file, ext){
       if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
        // extension is not allowed 
        statusGet.text('Only JPG, PNG or GIF files are allowed');
        return false;
      }
      statusGet.text('Uploading...');
    },
    onComplete: function(file, response){
      //On completion clear the status
      statusGet.text('');

      //Add uploaded file to list
      if(response != "error"){
        $('#upload-media-get').removeClass('active');
        $('#media-list-get').addClass('active');

        $('#upload-files-get').removeClass('active');
        $('#upload-files-get').removeClass('in');

        $('#media-library-get').addClass('active');
        $('#media-library-get').addClass('in');

        var data = JSON.parse(response);

        var html ='<li id="li-'+data.id+'">';
              html += '<input type="hidden" name="value-id" id ="value-id" value="'+data.id+'">';
              html += '<input type="checkbox" id="cb' + data.id + '" name="check_image[]" value="'+data.avatar+'"/>';
              html += '<label for="cb' + data.id + '"><img src="'+data.avatar+'"/></label>';
            html += '</li>';

        var htmlGet ='<li id="li-get-'+data.id+'">';
              htmlGet += '<input type="hidden" name="value-id-get" id ="value-id-get" value="'+data.id+'">';
              htmlGet += '<input type="checkbox" id="cbget' + data.id + '" name="check_image_get[]" value="'+data.avatar+'" checked/>';
              htmlGet += '<label for="cbget' + data.id + '"><img src="'+data.avatar+'"/></label>';
            htmlGet += '</li>';
            
        $('#list-media ul').prepend(html);
        $('#list-media-get ul').prepend(htmlGet);
        
        
        $('#get-into-post').attr('disabled', false);
        $('#delete-media-get').attr('disabled', false);
        check_image();
      } else{
        status.text('Lỗi không tải lên được');
      }
    }
  });
});
/*/upload file*/

//xu ly check anh
$(document).ready(function(e){

  //loai bo su kien click khi chan chon ảnh
  $('#insert-into-post').unbind();

  $(".img-check").click(function(){
    $(this).toggleClass("check");
  });
  check_image();

  //tim kiem media 
  $('#key-search-file').keyup(function(event) {
    var key = $('#key-search-file').val();
    var filter_date = $('#filter_date').val();
    $.ajax({
      url: '/admin/posts/searchMedia',
      type: 'GET',
      data: {
        'key': key,
        'filter_date': filter_date,
        'type': 'add'
      },
      beforeSend: function(){
        $('#list-media').html('<div class="loadding-media" class=""><i class="fa fa-refresh fa-spin"></i> 読み込み中</div>');
      },
      success: function( msg ) {
        $('#list-media').html(msg);
        check_image();
      }
    });
  }); 

  $('#key-search-file-get').keyup(function(event) {
    var key_get = $('#key-search-file-get').val();
    var filter_date_get = $('#filter_date_get').val();
    $.ajax({
      url: '/admin/posts/searchMedia',
      type: 'GET',
      data: {
        'key': key_get,
        'filter_date': filter_date_get,
        'type': 'get'
      },
      beforeSend: function(){
        $('#list-media-get').html('<div class="loadding-media" class=""><i class="fa fa-refresh fa-spin"></i> 読み込み中</div>');
      },
      success: function( msg ) {
        $('#list-media-get').html(msg);
        check_image();
      }
    });
  });

  $('#filter_date').change(function(event) {
    var key = $('#key-search-file').val();
    var filter_date = $('#filter_date').val();
    $.ajax({
      url: '/admin/posts/searchMedia',
      type: 'GET',
      data: {
        'key': key,
        'filter_date': filter_date,
        'type': 'add'
      },
      beforeSend: function(){
        $('#list-media').html('<div class="loadding-media" class=""><i class="fa fa-refresh fa-spin"></i> 読み込み中</div>');
      },
      success: function( msg ) {
        $('#list-media').html(msg);
        check_image();
      }
    });
  });
  
  $('#filter_date_get').change(function(event) {
    var key_get = $('#key-search-file-get').val();
    var filter_date_get = $('#filter_date_get').val();
    $.ajax({
      url: '/admin/posts/searchMedia',
      type: 'GET',
      data: {
        'key': key_get,
        'filter_date': filter_date_get,
        'type': 'get'
      },
      beforeSend: function(){
        $('#list-media-get').html('<div class="loadding-media" class=""><i class="fa fa-refresh fa-spin"></i> 読み込み中</div>');
      },
      success: function( msg ) {
        $('#list-media-get').html(msg);
        check_image();
      }
    });
  });

  $('#get-into-post').on('click',  function(event) {
    jQuery("input[name='check_image_get[]']:checked").each(function(){
      var src = jQuery(this).val();
      var data = '<img src="'+src+'" alt="..." class="img-thumbnail img-check">';
      $('#avatar').html(data);
      $('#file_avatar').val(src);
      jQuery(this).prop("checked", false);
    });

    $('#get-media').css({
      display: 'none'
    });

    $('#remove-image').css({
      display: 'block'
    });
    $('#get-into-post').attr('disabled', true);
    $('#delete-media-get').attr('disabled', true);
    $('#mediaModalGet').modal('hide');
  });
  $('#remove-image').click(function(event) {
    $('#avatar').html('');
    $('#get-media').css({
      display: 'block'
    });
    $('#remove-image').css({
      display: 'none'
    });
    $('#file_avatar').val('')
  });
});

//delete media
$('#delete-media').click(function(event) {
  $('#insert-into-post').attr('disabled', true);
  $('#mediaModal .close').css({
    display: 'none',
  });
  swal({
    title: "削除しても問題がありませんか。",
    text: "このファイルは復元できません",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "はい、削除します。",
    cancelButtonText: "いいえ、削除しません",
    closeOnConfirm: false
  },

  function(isConfirm) {
    if (isConfirm) {
      var array_id = [];
      var array_image = [];
      jQuery("input[name='check_image[]']:checked").each(function(){
        var id = jQuery(this).parent('li').children('#value-id').val();
        array_id[array_id.length]= id;
        array_image[array_image.length] = jQuery(this).val();
      });

      $.ajax({
        url: '/admin/Posts/deleteAjax',
        type: 'GET',
        data: {
          'array_id': array_id,
          'array_image': array_image,
        },
        success: function( msg ) {
          if (msg == 'success') {
            jQuery("input[name='check_image[]']:checked").each(function(){
              var id = jQuery(this).parent('li').children('#value-id').val();
              $('#li-'+id).remove();
              $('#li-get-'+id).remove();
            });

            $('#insert-into-post').attr('disabled', true);
            $('#delete-media').attr('disabled', true);
            swal("削除完了");
          }else{
            swal("エーラで削除できません。");
          }
          
        },   
      });
    } else {
      $('#insert-into-post').attr('disabled', false);
      return false;
    }
    $('#mediaModal .close').css({
      display: 'block',
    });
  });
  
});

//delete avatar
$('#delete-media-get').click(function(event) {
  $('#get-into-post').attr('disabled', true);
  $('#mediaModalGet .close').css({
      display: 'none',
    });
  swal({
    title: "削除しても問題がありませんか。",
    text: "このファイルは復元できません。",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: "btn-danger",
    confirmButtonText: "はい、削除します。",
    cancelButtonText: "いいえ、削除しません。",
    closeOnConfirm: false
  },
  function(isConfirm) {
    if (isConfirm) {
      var array_id = [];
      var array_image = [];
      jQuery("input[name='check_image_get[]']:checked").each(function(){
        var id = jQuery(this).parent('li').children('#value-id-get').val();
        array_id[array_id.length]= id;
        array_image[array_image.length] = jQuery(this).val();
        $('#li-'+id).remove();
        $('#li-get-'+id).remove();
      });
      $.ajax({
        url: '/admin/Posts/deleteAjax',
        type: 'GET',
        data: {
          'array_id': array_id,
          'array_image': array_image,
        },
        success: function( msg ) {
          if (msg == 'success') {
            jQuery("input[name='check_image_get[]']:checked").each(function(){
              var id = jQuery(this).parent('li').children('#value-id-get').val();
              $('#li-'+id).remove();
              $('#li-get-'+id).remove();
            });
            $('#get-into-post').attr('disabled', true);
            $('#delete-media-get').attr('disabled', true);
            swal("削除完了。");
          }else{
            swal("エーラで削除できません。");
          }
          
        },   
      });
    } else {
      $('#get-into-post').attr('disabled', false);
      return false;
    }
    $('#mediaModalGet .close').css({
      display: 'block',
    });
  });
});

//kiem tra check image
function check_image(){
  $("input[name='check_image[]']").change(function(){
    var check = '';
    jQuery("input[name='check_image[]']:checked").each(function(){
      check = check + ',' + jQuery(this).val();
    });
    if(check.length>1){
      // $('#insert-into-post').removeClass('disabled')
      $('#insert-into-post').attr('disabled', false);
      $('#delete-media').attr('disabled', false);
    }else{
      $('#insert-into-post').attr('disabled', true);
      $('#delete-media').attr('disabled', true);
    }
  });

  $("input[name='check_image_get[]']").change(function(){
    var new_check = jQuery(this);
    var check = '';
    var i = 0;
    jQuery("input[name='check_image_get[]']:checked").each(function(){
      check = check + ',' + jQuery(this).val();
      i++;
    });
    if(check.length>1){
      if (i>1) {
        jQuery("input[name='check_image_get[]']:checked").each(function(){
          jQuery(this).prop("checked", false);
        });
        new_check.prop("checked", true);
      }
      $('#get-into-post').attr('disabled', false);
      $('#delete-media-get').attr('disabled', false);
    }else{
      $('#get-into-post').attr('disabled', true);
      $('#delete-media-get').attr('disabled', true);
    }
  });
}
