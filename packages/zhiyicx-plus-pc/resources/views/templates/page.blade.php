<?php if ($paginator->hasPages()) { ?>
<div class=" int_page">
    <div class="dy_pageCont">
        <?php if (! $paginator->onFirstPage()) { ?>
            <a class="page_arrow" href="<?php echo e($paginator->previousPageUrl()); ?>"><img src="/zhiyicx/plus-component-pc/images/ico_arro_left_able.png"></a>
        <?php } ?>
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach ($__currentLoopData as $element) {
    $__env->incrementLoopIndices();
    $loop = $__env->getLastLoop(); ?>
            <?php if (is_string($element)) { ?>
                <a class="page disabled"><?php echo e($element); ?></a>
            <?php } ?>
            <?php if (is_array($element)) { ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach ($__currentLoopData as $page => $url) {
        $__env->incrementLoopIndices();
        $loop = $__env->getLastLoop(); ?>
                    <?php if ($page == $paginator->currentPage()) { ?>
                        <a class="page_cur" href="javascript:void(0)"><?php echo e($page); ?></a>
                    <?php } else { ?>
                        <a class="page_a" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                    <?php } ?>
                <?php
    } $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php } ?>
        <?php
} $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php if ($paginator->hasMorePages()) { ?>
            <a class="page_arrow" href="<?php echo e($paginator->nextPageUrl()); ?>"><img src="/zhiyicx/plus-component-pc/images/ico_arro_right_able.png"></a>
        <?php } ?>
    </div>
</div>
<?php } ?>