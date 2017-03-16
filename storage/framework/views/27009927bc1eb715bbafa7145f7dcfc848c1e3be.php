<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="/js/jquery-3.1.1.min.js"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <script src="/js/bootstrap.min.js"></script>
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/css/main.css" rel="stylesheet" type="text/css">
    <link href="/css/slide.css" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>
    <script type="text/javascript">
        bkLib.onDomLoaded(function () {
            nicEditors.allTextAreas()
        });
    </script>
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;
    </script>
    <title><?php $__env->startSection('page-title'); ?>
        <?php echo $__env->yieldSection(); ?></title>
</head>
<body>
<img src="/img/top-baner.png" width="100%" height="auto">
<nav class="navbar navbar-my">
    <a href="javascript:actionNav()">
        <i class="fa fa-bars"></i>
    </a>
    <ul class="nav navbar-nav navbar-right">
        <?php if(Auth::guest()): ?>
            <li><a href="<?php echo e(route('login')); ?>">Đăng nhập</a></li>
        <?php else: ?>
        <li class="dropdown top-menu">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo e(\Illuminate\Support\Facades\Auth::user()->fullname); ?>

                <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo e(route('logout')); ?>"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" style="color: black !important;">Đăng xuất</a></li>
            </ul>
        </li>
        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
            <?php echo e(csrf_field()); ?>

        </form>
        <?php endif; ?>
    </ul>
</nav>

<div class="main">
    <div id="mySidenav" class="sidenav">
        <div class="left-head">
            CÔNG VIỆC
        </div>
        <div class="list-menu">
            <div class="cate-menu">NGƯỜI DÙNG</div>
            <ul>
                <li><a href="<?php echo e(@route('user-index')); ?>">Người sử dụng</a></li>
                <li><a href="<?php echo e(@route('unit-index')); ?>">Ban - Đơn vị</a></li>
                <li><a href="<?php echo e(@route('viphuman-index')); ?>">Người chủ trì</a></li>
            </ul>
            <div class="cate-menu">Ý KIẾN CHỈ ĐẠO</div>
            <ul>
                <li><a href="<?php echo e(@route('sourcesteering-index')); ?>">Nguồn chỉ đạo</a></li>
                <li><a href="<?php echo e(@route('steeringcontent-index')); ?>">Nội dung chỉ đạo</a></li>
            </ul>
            <div class="cate-menu">XỬ LÝ CÔNG VIỆC</div>
            <ul>
                <li><a href="<?php echo e(@route('user-index')); ?>">Công việc đầu mối</a></li>
                <li><a href="<?php echo e(@route('user-index')); ?>">Công việc phối hợp</a></li>
                <li><a href="<?php echo e(@route('user-index')); ?>">Công việc mới được giao</a></li>
                <li><a href="<?php echo e(@route('user-index')); ?>">Nguồn chỉ đạo</a></li>
            </ul>
        </div>
    </div>
    <div id="content">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>
</body>
<script>
    var open = true;
    console.log(window.innerWidth + "/" + window.innerHeight)
    if (window.innerWidth < window.innerHeight || window.innerWidth < 800) {
        open = false;
    }
    function openNav() {
        document.getElementById("mySidenav").style.left = "0px";
        document.getElementById("content").style.marginLeft = "300px";
    }
    function closeNav() {
        document.getElementById("mySidenav").style.left = "-300px";
        document.getElementById("content").style.marginLeft = "0";
    }

    function actionNav() {
        if (open) {
            open = false;
            closeNav();
        } else {
            open = true;
            openNav();
        }
    }
</script>
</html>