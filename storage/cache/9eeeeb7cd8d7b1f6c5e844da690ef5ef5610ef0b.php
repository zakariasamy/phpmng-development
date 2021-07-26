<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php $__currentLoopData = $users['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div>
            <?php echo e($user->id); ?> -- 
            <?php echo e($user->name); ?>

        </div>
        <div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php echo Phpmng\Database\Database::links($users['current_page'], $users['pages'], 3); ?>

</body>
</html>
<?php /**PATH /var/www/phpmng/views/admin/dashboard.blade.php ENDPATH**/ ?>