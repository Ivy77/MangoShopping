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
                            <p><?php echo '$' . $item['UnitPrice']; ?> x <?php echo $product['num']; ?></p>
                            <p class="product-sum"><?php echo '$' . $item['UnitPrice'] * $product['num']; ?> </p>
                        </div>
                    </li>

                </ul>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form method="post" action="index.php?action=doCheckout">
                <!-- customername -->
                <div class="form-group">
                    <label for="customername">customername</label>
                    <input type="text" class="form-control" name="customername" id="customername" value="<?php if (isset($_SESSION['user'])) echo $_SESSION['user']['CustomerName'] ?>"/>
                </div>
                <!-- email -->
                <div class="form-group">
                    <label for="email">email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?php if(isset($_SESSION['user'])) echo $_SESSION['user']['Email'] ?>"/>
                </div>

                <div class="form-group">
                    <label for="phonenumber">Enter your phone number</label>
                    <input type="text" class="form-control" name="phonenumber" id="phonenumber" value="<?php if (isset($_SESSION['user'])) echo $_SESSION['user']['PhoneNumber'] ?>"/>
                </div>

                <div class="form-group">
                    <label for="address">Enter your address</label>
                    <input type="text" class="form-control" name="address" id="address" value="<?php if (isset($_SESSION['user'])) echo $_SESSION['user']['Address'] ?>"/>
                </div>

                <div class="form-group">
                    <label for="creditcard">Enter your credit card</label>
                    <input type="text" class="form-control" name="creditcard" id="creditcard"/>
                </div>

                <div class="form-group">
                    <label for="prefdeliverydate">Enter your pref delivery date</label>
                    <input type="date" class="form-control" name="prefdeliverydate" id="prefdeliverydate"/>
                </div>
                <input title="" hidden name="payment" value="<?php echo getCartProductTotal(); ?>">
                <p class="product-total">Total (<?php echo getCartProductCount(); ?> items)</p>
                <p class="product-total">$<?php echo getCartProductTotal(); ?></p>
                <button class="btn btn-primary pull-right"><span class="glyphicon glyphicon-shopping-cart"></span> Checkout.</button>
            </form>
        </div>
    </div>
</div>
