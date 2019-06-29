<?php
class ModelCatalogCustomOptions extends Model {

	public function getCustomOption($product_id) {
	    $table_option_name = DB_PREFIX . 'custom_option';
	    $table_option_value_name = DB_PREFIX . 'custom_option_value';

	    // получаем значения опция, имя опций, id продуктов, сортировки
	    $query = $this->db->query("SELECT type, value, " . $table_option_value_name . ".product_id, " . $table_option_name . ".name, " . $table_option_name . ".sort as option_sort, " . $table_option_value_name . ".sort as value_sort FROM " . $table_option_value_name . " LEFT JOIN " . $table_option_name . " ON option_id = " . $table_option_name . ".id WHERE " . $table_option_name . ".product_id = " . $product_id . " ORDER BY option_sort, value_sort");


        $data = array();
        if ($query->num_rows > 0) {
            // создаем опции
            $last = 0;
            foreach ($query->rows as $row) {
                if (count($data) > 0 && strcmp($row['name'], $data[$last - 1]['name']) == 0) continue;
                else {
                    $last = array_push($data, array(
                        'name' => $row['name'],
                        'sort' => (int)$row['option_sort'],
                        'type' => (int)$row['type'],
                    ));
                }

            }

            for ($j = 0; $j < count($data); $j++) {
                $k = 0;
                foreach ($query->rows as $row) {
                    if ($data[$j]['name'] == $row['name']) {
                        $data[$j]['values'][$k++] = array(
                            'value' => $row['value'],
                            'sort' => $row['value_sort'],
                            'product_id' => $row['product_id']
                        );
                    }
                }
            }
        }

		return $data;
	}
	
}
