<?php

class ControllerExtensionModuleCustomOptions extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/custom_options');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/customoption');
		$this->load->model('setting/setting');
		$this->load->model('catalog/product');

		// reset option_id
		$this->session->data['option_id'] = null;

		$data['heading_title'] = $this->language->get('heading_title');
		$data['entry_name'] = $this->language->get('entry_name');

		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_add'] = $this->language->get('button_add');

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], true),
			'separator' => false
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true),
			'separator' => ' :: '
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/custom_options', 'token=' . $this->session->data['token'], true),
			'separator' => ' :: '
		);

		$data['token'] = $this->session->data['token'];

		$data['action'] = $this->url->link('catalog/option_redactor', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->get['del'])) {
			$this->model_catalog_customoption->deleteCustomOption($this->request->get['del']);
		}

		$data['custom_options'] = $this->model_catalog_customoption->getCustomOptions();
		foreach ($data['custom_options'] as &$option) {
			$option['product_name'] = $this->model_catalog_product->getProduct($option['product_id'])['name'];
			$option['edit_link'] = $this->url->link('catalog/option_redactor', 'token=' . $this->session->data['token'] . '&option_id=' . $option['id'], true);
			$option['delete'] = $this->url->link('extension/module/custom_options', 'token=' . $this->session->data['token'] . '&del=' . $option['id'], true);
			$option['copy'] = $this->url->link('catalog/option_redactor', 'token=' . $this->session->data['token'] . '&copy=' . $option['id'], true);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/custom_options', $data));
	}

	public function install() {
		$this->load->model('catalog/customoption');
		$this->model_catalog_customoption->createDatabaseTables();
	}

	public function uninstall() {

		$this->load->model('catalog/customoption');
		$this->model_catalog_customoption->dropDatabaseTables();
	}
}

?>