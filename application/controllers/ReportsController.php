<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ReportsController extends Admin_Controller 
{
    public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Reports';
		
		$this->load->model('Medicine_model');
	}

    public function generate_customer_report($customer_id) {
        $data['transactions'] = $this->Medicine_model->get_customer_transactions($customer_id);
        $html = $this->load->view('customer_report_pdf', $data, TRUE);
        require_once APPPATH . 'third_party/dompdf/vendor/autoload.php';
        // $this->load->library('pdf');
        echo '<pre>';
        print_r($data['transactions']);
        echo '</pre>';
        $this->pdf->loadHtml($html);
        $this->pdf->render();
        $this->pdf->stream("customer_report_$customer_id.pdf");
    }
    
}

?>