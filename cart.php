<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php foreach ($_SESSION['cart'] as $product):?>
                <?php $item = $product['product']; ?>
                <ul class="media-list">
                    <li class="media">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object" src="<?php echo $imagesDir . $item['Picture']; ?>" width="64" height="64" alt="...">
                            </a>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $item['ProductName'];?></h4>
                            <p><?php echo $item['ProductDescription']; ?></p>
                            <p><?php echo '$' . $item['UnitPrice']; ?></p>
                            <form method="post" action="index.php?action=updateCart" class="form-inline">
                                <input hidden name="ProductName" value="<?php echo $item['ProductName']; ?>" title="">
                                <input hidden name="ProductID" value="<?php echo $item['ProductID']; ?>" title="">
                                <input hidden name="UnitPrice" value="<?php echo $item['UnitPrice']; ?>" title="">
                                <input hidden name="UnitCost" value="<?php echo $item['UnitCost']; ?>" title="">
                                <input hidden name="Picture" value="<?php echo $item['Picture']; ?>" title="">
                                <div class="update-cart">
                                    <input name="num" type="number" class="form-control" title="" value="<?php echo $product['num']; ?>">
                                    <button class="btn btn-default">Update</button>
                                </div>
                            </form>
                            <p class="product-sum"><?php echo '$' . $item['UnitPrice'] * $product['num']; ?> </p>
                        </div>
                    </li>

                </ul>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-offset-10 col-md-2">
            <form method="post" action="index.php?action=checkout">
                <p class="product-total">Total (<?php echo getCartProductCount(); ?> items)</p>
                <p class="product-total">$<?php echo getCartProductTotal(); ?></p>
                <button class="btn btn-primary"><span class="glyphicon glyphicon-shopping-cart"></span> Proceed to checkout.</button>
            </form>
        </div>
    </div>
</div>
