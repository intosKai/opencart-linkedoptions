<?php

class ModelCatalogCustomOption extends Model {
	public function editCustomOption($data = array()) {
		if (isset($data['option_id'])) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "custom_option WHERE id=" . (int)$data['option_id']);
			$this->db->query("INSERT INTO " . DB_PREFIX . "custom_option SET id=" . (int)$data['option_id'] . ", product_id=" . (int)$data['product-id'] . ", type=" . (int)$data['select-type'] . ", sort=" . (int)$data['input-sort'] . ", name='" . $this->db->escape($data['input-name']) . "'");
		} else {
			$this->db->query("INSERT INTO " . DB_PREFIX . "custom_option SET product_id=" . (int)$data['product-id'] . ", type=" . (int)$data['select-type'] . ", sort=" . (int)$data['input-sort'] . ", name='" . $this->db->escape($data['input-name']) . "'");
		}
	}

	public function addValue($data = array()) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "custom_option_value SET product_id=" . (int)$data['value_product_id'] . ", option_id=" . (int)$data['value_option_id'] . ", sort=" . (int)$data['value_sort'] . ", value='" . $this->db->escape($data['value_name']) . "'");
	}

	public function deleteCustomOption($option_id) {
		if (isset($option_id)) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "custom_option_value WHERE option_id=" . (int)$option_id);
			$this->db->query("DELETE FROM " . DB_PREFIX . "custom_option WHERE id=" . (int)$option_id);
		}
	}

	public function deleteValue($value_id) {
		if (isset($value_id)) {
			$this->db->query("DELETE FROM " . DB_PREFIX . "custom_option_value WHERE id=" . (int)$value_id);
		}
	}

	public function getOptionValues($option_id) {
		$data = array();
		$query = null;
		if (isset($option_id)) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_option_value WHERE option_id=" . (int)$option_id);
		}
		if ($query) {
			foreach ($query->rows as $row) {
				$data[] = array(
					'value_id' => $row['id'],
					'product_id' => $row['product_id'],
					'option_id' => $row['option_id'],
					'sort' => $row['sort'],
					'value' => $row['value']
				);
			}
		}
		return $data;
	}

	public function getCustomOptions() {

		// получаем значения опция, имя опций, id продуктов, сортировки
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_option");


		$data = array();
		if ($query->num_rows > 0) {
			foreach ($query->rows as $row) {
				array_push($data, $row);
			}
		}

		return $data;
	}

	public function getCustomOption($option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_option WHERE id=" . (int)$option_id);

		$data = array();
		if ($query->num_rows > 0) {
			$data = $query->rows[0];
		}

		return $data;
	}

	public function copyOption($option_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_option WHERE id=" . (int)$option_id);

		if (isset($query->rows)) {
			$option = $query->rows[0];
			$this->db->query("INSERT INTO " . DB_PREFIX . "custom_option SET product_id=" . (int)$option['product_id'] . ", type=" . (int)$option['type'] . ", sort=" . (int)$option['sort'] . ", name='" . $this->db->escape($option['name']) . "'");
			$new_id = $this->db->getLastId();

			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_option_value WHERE option_id=" . (int)$option_id);
			foreach ($query->rows as $row) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "custom_option_value (product_id, option_id, sort, value) VALUES (" . (int)$row['product_id'] . ", " . $new_id . ", " . (int)$row['sort'] . ", '" . $this->db->escape($row['value']) . "')");
			}

			return $new_id;
		}
	}

	public function createDatabaseTables() {
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "custom_option` ( ";
		$sql .= "`id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`product_id` int(11) NOT NULL, ";
		$sql .= "`type` int(11) NOT NULL, ";
		$sql .= "`sort` int(11) NOT NULL, ";
		$sql .= "`name` varchar(32) NOT NULL, ";
		$sql .= "PRIMARY KEY (`id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";
		$this->db->query($sql);

		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "custom_option_value` ( ";
		$sql .= "`id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`product_id` int(11) NOT NULL, ";
		$sql .= "`option_id` int(11) NOT NULL, ";
		$sql .= "`sort` int(11) NOT NULL, ";
		$sql .= "`value` varchar(80) NOT NULL, ";
		$sql .= "PRIMARY KEY (`id`) ";
		$sql .= ") ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci; ";
		$this->db->query($sql);
	}


	public function dropDatabaseTables() {
		$sql = "DROP TABLE IF EXISTS `" . DB_PREFIX . "custom_option`;";
		$this->db->query($sql);
		$sql = "DROP TABLE IF EXISTS `" . DB_PREFIX . "custom_option_value`;";
		$this->db->query($sql);

	}

}