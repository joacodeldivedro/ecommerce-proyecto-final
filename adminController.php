<?php
class adminController {
    private $categoryModel;
    private $productModel;
    
    public function __construct($db) {
        $this->categoryModel = new Category($db);
        $this->productModel = new Product($db);
    }
    
    public function categories() {
        $categories = $this->categoryModel->read();
        include 'views/admin/categories/list.php';
    }
    
    public function createCategory() {
        $error = "";
        if($_POST) {
            $this->categoryModel->name = $_POST['name'];
            $this->categoryModel->description = $_POST['description'];
            if($this->categoryModel->create()) {
                header("Location: index.php?action=admin_categories");
                exit;
            } else {
                $error = "Error al crear la categoría";
            }
        }
        include 'views/admin/categories/create.php';
    }
    
    public function editCategory() {
        $error = "";
        $category = $this->categoryModel->readById($_GET['id']);
        
        if($_POST) {
            $this->categoryModel->id = $_POST['id'];
            $this->categoryModel->name = $_POST['name'];
            $this->categoryModel->description = $_POST['description'];
            if($this->categoryModel->update()) {
                header("Location: index.php?action=admin_categories");
                exit;
            } else {
                $error = "Error al actualizar la categoría";
            }
        }
        include 'views/admin/categories/edit.php';
    }
    
    public function deleteCategory() {
        if(isset($_GET['id'])) {
            $this->categoryModel->id = $_GET['id'];
            $this->categoryModel->delete();
        }
        header("Location: index.php?action=admin_categories");
        exit;
    }
    
    public function products() {
        $products = $this->productModel->read();
        include 'views/admin/products/list.php';
    }
    
    public function createProduct() {
        $error = "";
        $categories = $this->categoryModel->read();
        
        if($_POST) {
            $this->productModel->name = $_POST['name'];
            $this->productModel->description = $_POST['description'];
            $this->productModel->price = $_POST['price'];
            $this->productModel->category_id = $_POST['category_id'];
            
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_name = $this->productModel->uploadImage($_FILES['image']);
                if($image_name) {
                    $this->productModel->image = $image_name;
                } else {
                    $error = "Error al subir la imagen";
                }
            }
            
            if(empty($error) && $this->productModel->create()) {
                header("Location: index.php?action=admin_products");
                exit;
            } else if(empty($error)) {
                $error = "Error al crear el producto";
            }
        }
        include 'views/admin/products/create.php';
    }
    
    public function editProduct() {
        $error = "";
        $product = $this->productModel->readById($_GET['id']);
        $categories = $this->categoryModel->read();
        
        if($_POST) {
            $this->productModel->id = $_POST['id'];
            $this->productModel->name = $_POST['name'];
            $this->productModel->description = $_POST['description'];
            $this->productModel->price = $_POST['price'];
            $this->productModel->category_id = $_POST['category_id'];
            
            if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_name = $this->productModel->uploadImage($_FILES['image']);
                if($image_name) {
                    $this->productModel->image = $image_name;
                }
            }
            
            if(empty($error) && $this->productModel->update()) {
                header("Location: index.php?action=admin_products");
                exit;
            } else if(empty($error)) {
                $error = "Error al actualizar el producto";
            }
        }
        include 'views/admin/products/edit.php';
    }
    
    public function deleteProduct() {
        if(isset($_GET['id'])) {
            $this->productModel->id = $_GET['id'];
            $this->productModel->delete();
        }
        header("Location: index.php?action=admin_products");
        exit;
    }
}
?>