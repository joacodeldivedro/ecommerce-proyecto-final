<?php
class productController {
    private $productModel;
    private $categoryModel;
    // private $pageCounter; // COMENTAR esta línea
    
    public function __construct($db) {
        $this->productModel = new product($db);
        $this->categoryModel = new category($db);
    }
    
    public function index() {
        // $this->pageCounter->recordView('products'); // COMENTAR esta línea
        $products = $this->productModel->read();
        include 'views/products/list.php';
    }
}
?>