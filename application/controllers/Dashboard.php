<?php 

class Dashboard extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Dashboard';
		
		$this->load->model('model_products');
		$this->load->model('model_orders');
		$this->load->model('model_users');
		$this->load->model('model_stores');
		$this->load->model('model_customers');
		$this->load->model('model_medicines');
	}

	/* 
	* It only redirects to the manage category page
	* It passes the total product, total paid orders, total users, and total stores information
	into the frontend.
	*/
	public function index()
	{
		$this->data['total_products'] = $this->model_products->countTotalProducts();
		$this->data['total_paid_orders'] = $this->model_orders->countTotalPaidOrders();
		$this->data['total_users'] = $this->model_users->countTotalUsers();
		$this->data['total_stores'] = $this->model_stores->countTotalStores();
		$this->data['total_orders'] = $this->model_orders->countTotalOrders();
		$this->data['total_brands'] = $this->model_products->countTotalbrands();
		$this->data['total_category'] = $this->model_products->countTotalcategory();
		$this->data['total_attribures'] = $this->model_products->countTotalattribures();


		$this->data['total_customers'] = $this->model_customers->getActiveCustomerData();
		$this->data['total_medicines'] = $this->model_medicines->getActiveMedicinesData();
		// $this->data['total_stores'] = $this->model_stores->countTotalStores();

		$user_id = $this->session->userdata('id');
		$is_admin = ($user_id == 1) ? true :false;

		$this->data['is_admin'] = $is_admin;
		$this->render_template('dashboard', $this->data);
	}

	public function getUserMedicineStats($userId)
	{
		$data = $this->model_customers->getUserMedicineStats($userId);
		echo json_encode($data);
	}

	public function countTotalmedineGiven() {
		
		$data = $this->model_orders->count_total_medicine_given();
		echo json_encode($data);
	}
	
	public function countTotalmedicineTaken() {

		$data = $this->model_products->count_total_medicine_taken();
		echo json_encode($data);
	}

	public function most_ordered_product() {

        $result = $this->model_products->get_most_ordered_product();

        // if ($result) {
        //     // Display the most ordered product's details
        //     echo 'Product Name: ' . $result->name;
        //     echo '<br>Order Count: ' . $result->order_count;
        // } else {
        //     echo 'No orders found.';
        // }
		echo json_encode($result);
    }
	
	public function getMostOrderedProductByQuantity() {

        $result = $this->model_products->get_most_ordered_product_by_quantity();

		echo json_encode($result);
    }

	public function getMedicineStock(){
		
		$result = $this->model_medicines->get_medicine_stock();

		echo json_encode($result);
	}

	public function getTopCustomersWithProducts(){
		
		$result = $this->model_customers->get_top_customers_with_products();

		echo json_encode($result);
	}
}