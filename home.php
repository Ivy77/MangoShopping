<div class="container">
    <ul class="nav nav-tabs">
        <li class="<?php echo isset($_GET['categoryID']) ? '' : 'active' ?>"><a href="index.php?action=home">all</a></li>
        <?php foreach ($categories as $category): ?>
            <li class="<?php isset($_GET['categoryID']) && echo_active($_GET['categoryID'], $category['CategoryID']) ?>"><a href="index.php?action=home&categoryID=<?php echo $category['CategoryID']; ?>"><?php echo $category['CategoryName']; ?></a></li>
        <?php endforeach; ?>
    </ul>
    <?php foreach ($products as $item):?>
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
                    <p><?php echo $item['UnitPrice']; ?></p>
                    <p><?php echo $item['UnitCost']; ?></p>
                    <form method="post" action="index.php?action=addToCart">
                        <input hidden name="ProductName" value="<?php echo $item['ProductName']; ?>" title="">
                        <input hidden name="ProductDescription" value="<?php echo $item['ProductDescription']; ?>" title="">
                        <input hidden name="ProductID" value="<?php echo $item['ProductID']; ?>" title="">
                        <input hidden name="UnitPrice" value="<?php echo $item['UnitPrice']; ?>" title="">
                        <input hidden name="UnitCost" value="<?php echo $item['UnitCost']; ?>" title="">
                        <input hidden name="Picture" value="<?php echo $item['Picture']; ?>" title="">
                        <button class="btn btn-default add-to-cart">Add to Cart <span class="glyphicon glyphicon-shopping-cart"></span></button>
                    </form>

                </div>
            </li>

        </ul>
    <?php endforeach; ?>
</div>
