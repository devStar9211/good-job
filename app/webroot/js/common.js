(function($) {
  //header検索欄表示切り替え
  $(".header-search-area .search-area-toggle").on('click', function() {
    $(".header-search-area .header-search-forms").slideToggle(500);
  });

   // For Mobile Nav Menu
   $(document).ready(function() {   
      var sideslider = $('[data-toggle=collapse-side]');
      var sel = sideslider.attr('data-target');
      var sel2 = sideslider.attr('data-target-2');
      sideslider.on("click touchend", function(event){
          $(sel).toggleClass('in');
          $(sel2).toggleClass('out');
      });
      $(".navbar-toggle").on("click touchend", function (event) {
          $(this).toggleClass("active");
        if($("button.navbar-toggle").hasClass("active")){
        $(".header-logo").css("z-index", "-1");
        $('.swiperCarousel').css('top', '50px');
      }else{
        $('.swiperCarousel').css('top', '0px');
        setTimeout(function(){ 
          $(".header-logo").css("z-index", "0");
        }, 100);
      }
      });
   });
   
  // For Japan Map
  $(function(){

    var $mapObj = $("#map");
    $mapObj.on("load", function(){
      var $iframeCanvas = $('iframe:first').contents().find("#japan-map-container > canvas");
      $mapObj.attr({
        width : $iframeCanvas.attr("width"),
        height : $iframeCanvas.attr("height")
      });
    });
  });  
  // scroll to Top Button  
  $(window).scroll(function() {
      if ($(this).scrollTop() >= 500) {        // Count windows scroll amount
          $('#return-to-top').fadeIn(200);    
      } else {
          $('#return-to-top').fadeOut(200);   
      }
  });
  $('#return-to-top').click(function() {      
      $('body,html').animate({
          scrollTop : 0                       // Scroll to top of body
      }, 900);
  });
  // For Area Menu Re-Generate
  $(function(){
        $(".navbar-nav > li.area > ul > li").each(function(){
           var h_value = $(this).find("a").attr("href");
           var region_id = h_value.match(/\d+/)[0];
           $(this).addClass("region_"+region_id);           
        });
        $('.navbar-nav > li.area > ul > li').slice(0, 7).wrapAll('<ul class="hokkaido_tohoku"></ul>');
        $('.navbar-nav > li.area > ul > li').slice(0, 7).wrapAll('<ul class="kanto"></ul>');
        $('.navbar-nav > li.area > ul > li').slice(0, 9).wrapAll('<ul class="chuubu"></ul>');
        $('.navbar-nav > li.area > ul > li').slice(0, 7).wrapAll('<ul class="kansai"></ul>');
        $('.navbar-nav > li.area > ul > li').slice(0, 9).wrapAll('<ul class="chuugoku_shikoku"></ul>');
        $('.navbar-nav > li.area > ul > li').slice(0, 8).wrapAll('<ul class="kyuushuu_okinawa"></ul>');
    if(typeof tm_hokkaido !== "undefined"){
      $('ul.hokkaido_tohoku').prepend('<span>'+tm_hokkaido+'</span>');
    }else{
      $('ul.hokkaido_tohoku').prepend('<span>Hokkaido.Tohoku</span>');
    }
    if(typeof tm_kanto !== "undefined"){
      $('ul.kanto').prepend('<span>'+tm_kanto+'</span>');
    }else{
      $('ul.kanto').prepend('<span>Kanto</span>');
    }
    if(typeof tm_chubu !== "undefined"){
      $('ul.chuubu').prepend('<span>'+tm_chubu+'</span>');
    }else{
      $('ul.chuubu').prepend('<span>Chubu</span>');
    }
    if(typeof tm_kansai !== "undefined"){
      $('ul.kansai').prepend('<span>'+tm_kansai+'</span>');
    }else{
      $('ul.kansai').prepend('<span>Kansai</span>');
    }
    if(typeof tm_chuguku !== "undefined"){
      $('ul.chuugoku_shikoku').prepend('<span>'+tm_chuguku+'</span>');
    }else{
      $('ul.chuugoku_shikoku').prepend('<span>Chugoku.Shikoku</span>');
    }
    if(typeof tm_kyushu !== "undefined"){
      $('ul.kyuushuu_okinawa').prepend('<span>'+tm_kyushu+'</span>');
    }else{
      $('ul.kyuushuu_okinawa').prepend('<span>Kyushu.Okinawa</span>');
    }
  });  
  function checkTopTemplatePage(){
    if($("body").hasClass("home_page")){
      return true;
    }else if($("body").hasClass("layout_template_page_2")){
      return true;
    }else if($("body").hasClass("layout_template_page_1")){
      return true;
    }else{
      return false;
    }
  }
  /*
  //headerのbg透過切り替え
  $(window).scroll(function () {
  if (checkTopTemplatePage() === true) {
    if($(window).scrollTop() >= 400) {
      $("header").css("background","#fff","important");
      $("header").css("border-bottom","1px solid #c0c0c0","important");
    } else {
      $("header").css("background","none","important")
      $("header").css("border-bottom","none","important");
    }
  }
  });
  */

  // Modal Dialogue Focus set on Plain Text Search
   $(document).ready(function(){
  if(checkTopTemplatePage() === false){
    $("header").css("background","#fff","important");
    $("header").css("border-bottom","1px solid #c0c0c0","important");
  }
    $('#header-search-modal').on('shown.bs.modal', function () {
      $('#plain-text-search').focus();
    });
   });
  
  $(document).ready(function(){
  //クリックイベント
    $('.information_title').click(function(){
      //class="row"をスライドで表示/非表示する
      $(this).children().stop(true, true).slideToggle();
    });
  });

  //Writter Banner Add Heright Setting   
  $(document).ready(function(){
    // run test on initial page load
    checkSize();

    // run test on resize of the window
    $(window).resize(checkSize);
    function checkSize(){
      if ($(window).width() <= 800){   
        $(".writer-add-banner").css("display", "none");
      }else{
        $(".writer-add-banner").css("display", "block");
      }
    }
    
    var bannerHeight = $(".writer-add-banner").height();
    var topImageHeight =  $(".thumbnail").outerHeight();
    if(topImageHeight > bannerHeight){
      $(".writer-add-banner").css({'height': topImageHeight});
    }
  });

  /* list searchの検索項目操作 */
  var default_region_id = '';
  var default_category_id = '';

  $('#region-current-value').on('click touchend', function() {
    if($('#region-selector').css('display') == 'none') {
      setTimeout(function(){
        $('#region-selector').slideDown(200);
      }, 100);
    }
  });

  $('#category-current-value').on('click touchend', function() {
    if($('#category-selector').css('display') == 'none') {
      setTimeout(function(){
        $('#category-selector').slideDown(200);
      }, 100);
    }
  });

  $(document).on('click touchend', function(event) {
    closeRegionSelector(event);
    closeCategorySelector(event);
  });
  var documentClick = null;
  $(document).on('touchstart', function() {
      documentClick = true;
  });
  $(document).on('touchmove', function() {
      documentClick = false;
  });
  $('#region-selector ul li').on('click touchend', function(){
    setDefaultRegionAndCategory();
    $('#region-current-value').text($(this).data('region-name'));
    $('#region-id-input').val($(this).data('region-id'));
    submitBtnUpdate();
    closeRegionSelector();
  });

  $('#category-selector ul li').on('click touchend', function(){
    setDefaultRegionAndCategory();
    $('#category-current-value').text($(this).data('category-name'));
    $('#category-id-input').val($(this).data('category-id'));
    submitBtnUpdate();
    closeCategorySelector();
  });

  function closeRegionSelector(event) {
  if(event === undefined) {
       event = 'undefined';
  } 
  if (event.type == "click") {documentClick = true;}
    if(event == 'undefined' || !$(event.target).closest('#region-selector').length) {
      if($('#region-selector').css('display') != 'none' && documentClick === true) {
        $('#region-selector').slideUp(200);
        scrollBack();
      }
    }
  }

  function closeCategorySelector(event) {
  if(event === undefined) {
       event = 'undefined';
  } 
  if (event.type == "click") {documentClick = true;}
    if(event == 'undefined' || !$(event.target).closest('#category-selector').length) {
      if($('#category-selector').css('display') != 'none' && documentClick === true) {
        $('#category-selector').slideUp(200);
        scrollBack();
      }
    }
  }

  function setDefaultRegionAndCategory() {
    if(default_region_id == '') {
      default_region_id = $('#region-id-input').attr('value');
    }
    if(default_category_id == '') {
      default_category_id = $('#category-id-input').attr('value');
    }
  }

  function submitBtnUpdate() {
    if($('#region-id-input').attr('value') != default_region_id || $('#category-id-input').attr('value') != default_category_id) {
      $('.search-form-wrapper button.search-submit').prop('disabled', false);
    } else {
      $('.search-form-wrapper button.search-submit').prop('disabled', true);
    }
  }
  function scrollBack(){
    if($(window).width() < 600 && $('body').hasClass('list_page')) {
        $('body,html').animate({
            scrollTop : 10                      
        }, 100);
    }
  }
  /* end list search functions */

    //モバイル時サイドバー処理
/*  $(function() {
    moveMobileSidebar();
  });
  $(document).bind('articleAutoLoad', function(){
    moveMobileSidebar();
  });
  function moveMobileSidebar() {
    if($(window).width() < 600 && $('body').hasClass('detail_page')) {
      $('#mobile-special-carousel').clone().appendTo('#wrapper .center .main');
      $('#sidebar_ranking').clone().appendTo('#wrapper .center .main');
    }
  } */
  $(function() {
    hideMainSidebar();
  });
 $(document).ready(function(){
   hideMainSidebar();
 })
 $(window).bind("resize", function() {
    hideMainSidebar();
 });
  function hideMainSidebar() {
    if($(window).width() < 1024 && $('body').hasClass('detail_page')) {
      $("#sidebar").hide();
    }else{
      $("#sidebar").show();
    }
  }
  // Touch Slider #Bootstrap default slider become Touch Friendly # Also included special banner slider
  $("#top-carousel, #mobile-special-carousel").on("touchstart", function(event){
        var xClick = event.originalEvent.touches[0].pageX;
    $(this).one("touchmove", function(event){
        var xMove = event.originalEvent.touches[0].pageX;
        if( Math.floor(xClick - xMove) > 5 ){
            $("#top-carousel, #mobile-special-carousel").carousel('next');
        }
        else if( Math.floor(xClick - xMove) < -5 ){
            $("#top-carousel, #mobile-special-carousel").carousel('prev');
        }
    });
    $("#top-carousel, #mobile-special-carousel").on("touchend", function(){
            $(this).off("touchmove");
    });
 }); 

// For Category Slide Auto hide
var targets = $("#category-slide-wrapper");
var mywindow = $(window);
var mypos = mywindow.scrollTop();
var up = false;
var newscroll;
mywindow.bind('scroll touchmove', function () {
    newscroll = mywindow.scrollTop();
      if (newscroll > mypos && !up && newscroll >300 ) {
            targets.removeClass("slideDownCatNav");
            targets.addClass("slideUpCatNav");
            up = !up;
    } else if(newscroll < mypos && up) {
            if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                return;
            }else{
                targets.removeClass("slideUpCatNav");
                targets.addClass("slideDownCatNav");
                up = !up;                    
            }
    } 
    mypos = newscroll;                    
});     
// For List Page Accordain
 $(document).ready(function(){
   if($('body').hasClass('list_page')){
    addAccord();  
   }
 })
 $(window).bind("resize", function() {
   if($('body').hasClass('list_page')){
    addAccord();  
   }
 });
 function addAccord(){
    if($(window).width() < 600) {
      if(!$('#equal-category, #child-category').hasClass('collapse')){
        $('#equal-category, #equal-regions').addClass('collapse');
        $('#child-category, #child-regions').addClass('collapse in');
      }
    }else {      
      if($('#equal-category, #child-category').hasClass('collapse')){
        $('#equal-category, #equal-regions, #child-category, #child-regions').removeClass('collapse in');
      }   
    }   
 }

  // For Sticky Div 
  function sticky_container() {
      var window_top = $(window).scrollTop();
      var div_top = $('#sidebar').height();
      if (window_top > div_top) {
          $('#sidebar_ranking').addClass('sticky-panel');
          $('.sidebar_list_area').css('min-height', '80px');
      } else {
          $('#sidebar_ranking').removeClass('sticky-panel');
          $('.sidebar_list_area').css('min-height', '100px');
      }
  }
  $(window).bind('scroll', function(){
    if($('body').hasClass('detail_page') && $(window).width() > 1024){
      sticky_container();
    }
  }) 

 // ページ内リンク移動
if($('body').hasClass('layout_page_default_noindex')){
	$('#page_booking .booking-category a').click(function() {
		var speed = 100;
		var href= $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var headerHeight = 45; //固定ヘッダーの高さ
		var position = target.offset().top - headerHeight; //ターゲットの座標からヘッダの高さ分引く
		$('body, html').animate({scrollTop:position}, speed, 'swing');
		return false;
	});
}
if($('body').hasClass('detail_page')){
	$('.contents_text a').click(function() {
		var speed = 100;
		var href= $(this).attr("href");
		var target = $(href == "#" || href == "" ? 'html' : href);
		var headerHeight = 45; //固定ヘッダーの高さ
		var position = target.offset().top - headerHeight; //ターゲットの座標からヘッダの高さ分引く
		$('body, html').animate({scrollTop:position}, speed, 'swing');
		return false;
	});
}

})(jQuery);
