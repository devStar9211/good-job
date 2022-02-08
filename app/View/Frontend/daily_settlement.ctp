<?php
$this->start('css');
echo $this->Html->css([
    '/assets/components/responsive-table/responsive-tables.css',
    '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
    '/assets/css/g_css.css'
]);
$this->end();

$this->start('script');
echo $this->Html->script([
    '/assets/components/responsive-table/responsive-tables.js',
    '/assets/components/bootstrap-datetimepicker/moment-with-locales.min.js',
    '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
    '/assets/js/g_script/g_script.js',
    '/assets/js/g_script/g_front.js',
    '/assets/js/format_number.js'
]);
?>
<script type="text/javascript">
    $(document).ready(function () {
        (function ($) {
            $.fn.fixMe = function () {
                return this.each(function () {
                    var $this = $(this),
                        $t_fixed;
                    function init() {
                        $this.wrap('<div class="table-container" />');
                        $t_fixed = $this.clone();
                        $t_fixed.find("tbody").remove().end().addClass("fixed").insertBefore($this);
                        $t_fixed.wrap('<div class="table-fixed"/>');
                        $this.wrap('<div class="table-scroll" />');
                        resizeFixed();
                    }
                    function resizeFixed() {
                        $t_fixed.find("th").each(function (index) {
                            $(this).css("width", $this.find("th").eq(index).outerWidth() + "px");
                        });
                        $t_fixed.css('width', $this.outerWidth() + "px");
                        $t_fixed.closest('.table-fixed').css('width', $t_fixed.closest('.table-container').outerWidth() + 'px');
                    }
                    function scrollFixed() {
                        var offset = $(this).scrollTop(),
                            tableOffsetTop = $this.offset().top,
                            tableOffsetBottom = tableOffsetTop + $this.height() - $this.find("thead").height();
                        if (offset < tableOffsetTop || offset > tableOffsetBottom)
                            $t_fixed.closest('.table-fixed').hide();
                        else if (offset >= tableOffsetTop && offset <= tableOffsetBottom && $t_fixed.is(":hidden"))
                            $t_fixed.closest('.table-fixed').show();
                    }
                    function scrollable_horizontal() {
                        var scrollLeft = $(this).scrollLeft();
                        $t_fixed.css('margin-left', '-' + (scrollLeft) + 'px')
                    }
                    $(window).resize(resizeFixed);
                    $(window).scroll(scrollFixed);
                    init();
                    $t_fixed.closest('.scrollable').scroll(scrollable_horizontal);
                });
            };
        })(jQuery);
        $(document).ready(function () {
            $(".scrollable table, .pinned table").fixMe();

        });

        var scrollable_tr = $(".table-daily-statement .scrollable tbody tr");
        var pinned_tr = $(".table-daily-statement .pinned tbody tr");
        $('.table-daily-statement tbody tr').mouseenter(function () {
            index = $(this).index();
            scrollable_tr.eq(index).addClass('hover');
            pinned_tr.eq(index).addClass('hover');
        }).mouseleave(function () {
            if (typeof scrollable_tr != 'undefined' && typeof pinned_tr != 'undefined') {
                scrollable_tr.eq(index).removeClass('hover');
                pinned_tr.eq(index).removeClass('hover');
            }
        });
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            $('body').addClass('is_mobile');
            var original = $("table.responsive");
            var width = original.data('mobile_fixed');
            original.closest(".res-table-wrapper").css('padding-left', '0');
            original.closest(".res-table-wrapper").find(".pinned").css({'display': 'none', 'width': width + 'px'});
//            original.closest(".res-table-wrapper").find(".pinned table td:first-child, .pinned table th:first-child").remove();

            scrollable_tr.closest('.scrollable').scroll(function () {
                var offset_left = $(this).scrollLeft();
                if (offset_left >= width) {
                    original.closest(".res-table-wrapper").find(".pinned").css({'display': 'block'});
                } else {
                    original.closest(".res-table-wrapper").find(".pinned").css({'display': 'none'});
                }
                if(check_scroll(scrollable_tr.closest('.res-table-wrapper'))){
                    original.closest(".res-table-wrapper").find(".table-fixed").show();
                }
            });

            var rt = true;
            function check_scroll(element) {
                var offset = $(window).scrollTop(),
                    tableOffsetTop = element.offset().top,
                    tableOffsetBottom = tableOffsetTop + element.height() - element.find("thead").height();
                if (offset < tableOffsetTop || offset > tableOffsetBottom)
                    rt = false;
                else if (offset >= tableOffsetTop && offset <= tableOffsetBottom)
                    rt = true;
                return rt;
            }

        }
    });
</script>

<?php
$this->end();

$data = isset($data) ? $data : array();
?>


<div class="settlement-container my-container">
    <div class="container-header">
        <div class="row">
            <div class="col-xs-12">
                <h4 class="no-margin"><?php echo __('daily settlement') . ' (' . date('m') . '/' . date('d') . __('更新') . ')' ?></h4>
                <hr class="no-margin">
            </div>
            <div class="col-sm-6 mt10">
                <div class="form-group">
                    <div class='input-group date'>
                        <?php echo $this->Form->input('year-month', array('div' => false, 'label' => false, 'type' => 'text', 'id' => 'monthYearSelect4', 'class' => 'form-control', 'role' => 'settlement-time', 'data-source' => $this->Html->url(array('controller' => 'Frontend', 'action' => 'get_daily_data')), 'placeholder' => __('年 - 月'), 'value' => (!empty($year) && !empty($month) ? $year . '-' . $month : ''))) ?>
                        <label for="monthYearSelect4" class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-body">
        <div class="table-wrap dataTables_wrapper form-inline dt-bootstrap">
            <div class="loader">
                <div class="text-loader">Loading</div>
            </div>
            <div class="clearfix">
                <div class="table-responsive table-daily-statement" role="data-wrap">
                    <?php echo $this->element('daily_settlement_data', array('data' => $data, 'gridConfig' => $gridConfig)) ?>
                </div>
            </div>
        </div>
    </div>
</div>