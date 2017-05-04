<div class="container">
    <h2 class="page-header">Orders</h2>
    <?php foreach ($orders as $order):?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">$<?php echo $order['content']['Payment'] ?></h3>
            </div>
            <div class="panel-body">
                <p><?php echo $order['content']['Status'] ?></p>
                <ul>
                    <?php foreach ($order['items'] as $item): ?>
                        <li><?php echo $item['ProductName']; ?></li>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>
    <?php endforeach; ?>
    <?php if (count($orders) === 0): ?>
        <div class="alert alert-info">
            You have no orders.
        </div>
    <?php endif; ?>

</div>
