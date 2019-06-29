<?php

class ControllerCatalogOptionRedactor extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/custom_options');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/customoption');
		$this->load->model('setting/setting');
		$this->load->model('catalog/product');

		// вытаскиваем выбранную опцию в сессию
		if (isset($this->request->get['option_id'])) {
			$this->session->data['option_id'] = $this->request->get['option_id'];
		}

		if (isset($this->request->get['copy'])) {
			$copy = $this->request->get['copy'];
			$this->session->data['option_id'] = $this->request->get['copy'];
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['entry_name'] = $this->language->get('entry_name');

		$data['button_cancel'] = $this->language->get('button_cancel');

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
		$data['breadcrumbs'][] = array(
			'text' => 'Редактор опций',
			'href' => $this->url->link('catalog', 'token=' . $this->session->data['token'] . '&option_id=' . $this->session->data['option_id'], true),
			'separator' => ' :: '
		);

		$data['token'] = $this->session->data['token'];

		$data['action'] = $this->url->link('catalog/option_redactor', 'token=' . $this->session->data['token'], true);
		$data['action_add'] = $this->url->link('catalog/option_redactor', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/module/custom_options', 'token=' . $this->session->data['token'], true);

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
			$this->model_catalog_customoption->deleteValue($this->request->get['del']);
		}

		if (isset($copy)) {
			$this->session->data['option_id'] = $this->model_catalog_customoption->copyOption($this->session->data['option_id']);

			$data['test'] = $this->session->data['option_id'];
		}


		if (isset($this->session->data['option_id'])) {
			$option = $this->model_catalog_customoption->getCustomOption($this->session->data['option_id']);
			$data['custom_option'] = array(
				'product_name' => $this->model_catalog_product->getProduct($option['product_id'])['name'],
				'type' => $option['type'],
				'sort' => $option['sort'],
				'name' => $option['name'],
				'product_id' => $option['product_id'],
				'option_id' => $option['id'],
			);
			$data['option_values'] = $this->model_catalog_customoption->getOptionValues($this->session->data['option_id']);
			foreach ($data['option_values'] as &$value) {
				$value['delete'] = $this->url->link('catalog/option_redactor', 'token=' . $this->session->data['token'] . '&option_id=' . $this->session->data['option_id'] . "&del=" . $value['value_id']);
				$value['product_name'] = $this->model_catalog_product->getProduct($value['product_id'])['name'];
			}

		} else {
			$data['custom_option'] = array(
				'product_name' => '',
				'type' => 1,
				'sort' => 0,
				'name' => '',
				'product_id' => -1,
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');


		if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['value_product_name']) && $this->validateSecondaryForm()) {
			$this->model_catalog_customoption->addValue($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('catalog/option_redactor', 'token=' . $this->session->data['token'], true));

		} else if (($this->request->server['REQUEST_METHOD'] == 'POST') && isset($this->request->post['select-type']) && $this->validateMainForm()) {
			$this->model_catalog_customoption->editCustomOption($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/module/custom_options', 'token=' . $this->session->data['token'], true));
		}
		$this->response->setOutput($this->load->view('catalog/option_redactor', $data));
	}

	protected function validateMainForm() {
		if (!$this->user->hasPermission('modify', 'catalog/option_redactor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		$arr = $this->request->post;

		if (strlen($arr['product-id']) < 1 || strlen($arr['select-type']) < 1 || strlen($arr['input-sort']) < 1 || strlen($arr['input-name']) < 1) {
			$this->error['warning'] = 'Не все поля заполнены!';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateSecondaryForm() {
		if (!$this->user->hasPermission('modify', 'catalog/option_redactor')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		$arr = $this->request->post;

		if (strlen($arr['value_product_id']) < 1 || strlen($arr['value_product_name']) < 1 || strlen($arr['value_sort']) < 1 || strlen($arr['value_name']) < 1) {
			$this->error['warning'] = 'Не все поля заполнены!';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>