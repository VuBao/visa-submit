<?php
if((isset($_GET['month']) && $_GET['month'] != '') && (isset($_GET['year']) && $_GET['year'] != ''))
{
    $time = $_GET['year'].'-'.$_GET['month'].'-1';
    $date = strtotime($time);
}
else
{
    $date = strtotime(date('y-m-d')); 
}

$day = date('d', $date);
$month = date('m', $date);
$year = date('Y', $date);
$firstDay = mktime(0,0,0,$month, 1, $year);
$title = date('F', $firstDay);
$dayOfWeek = date('D', $firstDay);
$daysInMonth = cal_days_in_month(0, $month, $year);
$timestamp = strtotime('next Sunday');
$weekDays = array();

for($i=0;$i<7;$i++)
{
    $weekDays[] = date('D', $timestamp);
    $timestamp = strtotime('+1 day', $timestamp);
}

$blank = date('w', strtotime("{$year}-{$month}-01"));
?>
<?php
    $adm_month = (isset($_GET['thang'])) ? (int)$_GET['thang'] : (int)date('m');
    $adm_year = (isset($_GET['nam'])) ? (int)$_GET['nam'] : (int)date('Y');
    
    $where_date = "";
    if($adm_month > 0 && $adm_year > 0) {
        $adm_days = cal_days_in_month(CAL_GREGORIAN, $adm_month, $adm_year);
        $adm_start = strtotime(sprintf("%04d-%02d-01 00:00:00", $adm_year, $adm_month));
        $adm_end = strtotime(sprintf("%04d-%02d-%02d 23:59:59", $adm_year, $adm_month, $adm_days));
        $where_date = " and (ngaytao >= $adm_start and ngaytao <= $adm_end)";
    } elseif($adm_year > 0) {
        $adm_start = strtotime(sprintf("%04d-01-01 00:00:00", $adm_year));
        $adm_end = strtotime(sprintf("%04d-12-31 23:59:59", $adm_year));
        $where_date = " and (ngaytao >= $adm_start and ngaytao <= $adm_end)";
    }

    $adm_pv = $d->rawQueryOne("select count(id) as c from #_ungtuyen where id<>0 and tinhtrang = 2 $where_date");
    $adm_naitei = $d->rawQueryOne("select count(id) as c from #_ungtuyen where id<>0 and tinhtrang = 3 $where_date");
    $adm_visa = $d->rawQueryOne("select count(id) as c from #_ungtuyen where id<>0 and tinhtrang IN (4, 8, 9) $where_date");
    $adm_lamviec = $d->rawQueryOne("select count(id) as c from #_ungtuyen where id<>0 and tinhtrang IN (5, 10) $where_date");
?>
<!-- Main content -->
<section class="content mb-3">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between pt-3 pb-2 border-bottom mb-3">
            <h5 class="mb-0 font-weight-bold text-uppercase text-primary">
                <i class="fas fa-trophy text-warning mr-2"></i>BẢNG THÀNH TÍCH ỨNG VIÊN
            </h5>
            <form method="get" action="index.php" class="form-inline ml-auto mb-0">
                <div class="form-group mr-2 mb-0">
                    <label for="thang_stat_dash" class="mr-1 text-sm">Tháng:</label>
                    <select name="thang" id="thang_stat_dash" class="form-control form-control-sm text-sm" onchange="this.form.submit()">
                        <option value="0" <?= (!isset($adm_month) || $adm_month == 0) ? 'selected' : '' ?>>Tất cả các tháng</option>
                        <?php for($m=1;$m<=12;$m++) { ?>
                            <option value="<?=$m?>" <?= (isset($adm_month) && $adm_month == $m) ? 'selected' : '' ?>>Tháng <?=$m?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group mb-0">
                    <label for="nam_stat_dash" class="mr-1 text-sm">Năm:</label>
                    <select name="nam" id="nam_stat_dash" class="form-control form-control-sm text-sm" onchange="this.form.submit()">
                        <option value="0" <?= (!isset($adm_year) || $adm_year == 0) ? 'selected' : '' ?>>Tất cả các năm</option>
                        <?php 
                        $curY = (int)date('Y');
                        for($y=$curY-3;$y<=$curY+1;$y++) { ?>
                            <option value="<?=$y?>" <?= (isset($adm_year) && $adm_year == $y) ? 'selected' : '' ?>>Năm <?=$y?></option>
                        <?php } ?>
                    </select>
                </div>
            </form>
        </div>

        <div class="row mb-3">
            <!-- Trước phỏng vấn -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-sm" style="background-color: #00c0ef; color: white;">
                    <span class="info-box-icon"><i class="fas fa-comments"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold">Trước phỏng vấn</span>
                        <span class="info-box-number text-lg"><?=(int)@$adm_pv['c']?> <small>ứng viên</small></span>
                    </div>
                </div>
            </div>

            <!-- Đậu naitei -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-sm" style="background-color: #ffc107; color: #1f2d3d;">
                    <span class="info-box-icon"><i class="fas fa-award"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold">Đậu naitei</span>
                        <span class="info-box-number text-lg"><?=(int)@$adm_naitei['c']?> <small>ứng viên</small></span>
                    </div>
                </div>
            </div>

            <!-- Đăng ký xin visa -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-sm" style="background-color: #28a745; color: white;">
                    <span class="info-box-icon"><i class="fas fa-passport"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold">Đăng ký xin visa</span>
                        <span class="info-box-number text-lg"><?=(int)@$adm_visa['c']?> <small>ứng viên</small></span>
                    </div>
                </div>
            </div>

            <!-- Đang làm việc -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box shadow-sm" style="background-color: #1f2937; color: white;">
                    <span class="info-box-icon"><i class="fas fa-briefcase"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text font-weight-bold">Đang làm việc</span>
                        <span class="info-box-number text-lg"><?=(int)@$adm_lamviec['c']?> <small>ứng viên</small></span>
                    </div>
                </div>
            </div>
        </div>

        <h5 class="pb-2">Bảng điều khiển hệ thống</h5>
        <div class="row mb-2 text-sm">
            <div class="col-12 col-sm-6 col-md-3">
                <a class="my-info-box info-box" href="index.php?com=setting&act=capnhat" title="Cấu hình website">
                    <span class="my-info-box-icon info-box-icon bg-primary"><i class="fas fa-cogs"></i></span>
                    <div class="info-box-content text-dark">
                        <span class="info-box-text text-capitalize">Cấu hình website</span>
                        <span class="info-box-number">View more</span>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a class="my-info-box info-box" href="index.php?com=user&act=admin_edit" title="Tài khoản">
                    <span class="my-info-box-icon info-box-icon bg-danger"><i class="fas fa-user-cog"></i></span>
                    <div class="info-box-content text-dark">
                        <span class="info-box-text text-capitalize">Tài khoản</span>
                        <span class="info-box-number">View more</span>
                    </div>
                </a>
            </div>
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <a class="my-info-box info-box" href="index.php?com=user&act=admin_edit&changepass=1" title="Đổi mật khẩu">
                    <span class="my-info-box-icon info-box-icon bg-success"><i class="fas fa-key"></i></span>
                    <div class="info-box-content text-dark">
                        <span class="info-box-text text-capitalize">Đổi mật khẩu</span>
                        <span class="info-box-number">View more</span>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a class="my-info-box info-box" href="index.php?com=contact&act=man" title="Thư liên hệ">
                    <span class="my-info-box-icon info-box-icon bg-info"><i class="fas fa-address-book"></i></span>
                    <div class="info-box-content text-dark">
                        <span class="info-box-text text-capitalize">Thư liên hệ</span>
                        <span class="info-box-number">View more</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="content pb-4">
 <div class="container-fluid">
     <div class="card">
         <div class="card-header">
             <h5 class="mb-0">Thống kê truy cập tháng <?=$month?>/<?=$year?></h5>
         </div>
         <div class="card-body">
            <form class="form-filter-charts row align-items-center mb-1" action="index.php" method="get" name="form-thongke" accept-charset="utf-8">
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control select2" name="month" id="month">
                            <option value="">Chọn tháng</option>
                            <?php for($i=1; $i<=12 ;$i++) { ?>
                                <?php
                                if(isset($_GET['year'])) $selected = ($i==$_GET['month']) ? 'selected':'';
                                else $selected = ($i==date('m')) ? 'selected':'';
                                ?>
                                <option value="<?=$i?>" <?=$selected?>>Tháng <?=$i?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select class="form-control select2" name="year" id="year">
                            <option value="">Chọn năm</option>
                            <?php for($i=2000;$i<=date('Y')+20;$i++) { ?>
                                <?php
                                if(isset($_GET['year'])) $selected = ($i==$_GET['year']) ? 'selected':'';
                                else $selected = ($i==date('Y')) ? 'selected':'';
                                ?>
                                <option value="<?=$i?>" <?=$selected?>>Năm <?=$i?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><button type="submit" class="btn btn-success">Thống Kê</button></div>
                </div>
            </form>
            <div id="apexMixedChart"></div>
        </div>
    </div>
</div>
</section>

<script src="assets/apexcharts/apexcharts.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var apexMixedChart;
        var options = {
            colors: ['#3c8dbc'],
            chart:
            {
                id: 'apexMixedChart',
                height: 450,
                type: 'line',
                dropShadow:
                {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 20,
                    opacity: 0.2
                }
            },
            series: [{
                name: 'Thống kê truy cập tháng <?=$month?>',
                type: 'line',
                data: [
                <?php for($i = 1; $i <= $daysInMonth; $i++) {
                    $k = $i+1;
                    $begin = strtotime($year.'-'.$month.'-'.$i);
                    $end = strtotime($year.'-'.$month.'-'.$k);
                    $todayrc = $d->rawQueryOne("select count(*) as todayrecord from #_counter where tm >= ? and tm < ?",array($begin,$end));
                    $today_visitors = $todayrc['todayrecord']; ?>
                    <?=$today_visitors?>,
                <?php } ?>
                ]
            }],
            stroke: {
              curve: 'smooth'
          },
          grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            },
        },
        markers: {
            size: 1
        },
        dataLabels: {
            enabled: false
        },
        labels: [
        <?php for($i = 1; $i <= $daysInMonth; $i++) {
            $k = $i+1;
            $begin = strtotime($year.'-'.$month.'-'.$i);
            $end = strtotime($year.'-'.$month.'-'.$k);
            $todayrc = $d->rawQueryOne("select count(*) as todayrecord from #_counter where tm >= ? and tm < ?",array($begin,$end));
            $today_visitors = $todayrc['todayrecord']; ?>
            'D<?=$i?>',
        <?php } ?>
        ],
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        }
    }

    apexMixedChart = new ApexCharts(document.querySelector("#apexMixedChart"), options);
    apexMixedChart.render();
})
</script>