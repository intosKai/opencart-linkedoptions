<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
           class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>

      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php var_dump($test); ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Настройки опции</h3>
      </div>
      <div class="panel-body">
        <form class="form-horizontal" id="optionForm" action="<?php echo $action; ?>" method="post"
              enctype="multipart/form-data">
          <div class="form-group">
            <input name="product-id" id="product-id" type="hidden"
                   value="<?php echo $custom_option['product_id']; ?>">
            <?php if (isset($custom_option['option_id'])) { ?>
            <input name="option_id" id="option_id" type="hidden"
                   value="<?php echo $custom_option['option_id']; ?>">
            <?php } ?>
            <label class="col-sm-2 control-label" for="input-product">Товар:</label>
            <div class="col-sm-10">
              <input class="form-control" type="text" name="input-product" id="input-product"
                     value="<?php echo $custom_option['product_name']?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="select-type">Тип:</label>
            <div class="col-sm-10">
              <select class="form-control" id="select-type" name="select-type">
                <option
                <?php if ($custom_option['type'] == 1) echo 'selected'; ?> value="1">Цвет</option>
                <option
                <?php if ($custom_option['type'] == 2) echo 'selected'; ?> value="2">Текст</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort">Сортировка:</label>
            <div class="col-sm-10">
              <input class="form-control" type="number" id="input-sort" name="input-sort"
                     value="<?php echo $custom_option['sort']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name">Название:</label>
            <div class="col-sm-10">
              <input class="form-control" type="text" id="input-name" name="input-name"
                     value="<?php echo $custom_option['name']; ?>">
            </div>
          </div>
          <div class="form-group">
            <button type="submit" form="optionForm" data-toggle="tooltip"
                    title="<?php echo $button_save; ?>" class="btn btn-primary"
                    style="margin-right: 15px; float: right">Сохранить
            </button>
          </div>
        </form>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Значения опции</h3>
      </div>
      <div class="panel-body">
        <form id="valuesForm" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
          <table class="table table-responsive">
            <tr>
              <th>Продукт</th>
              <th>Сортировка</th>
              <th>Значение</th>
              <th>Действия</th>
            </tr>
            <?php if (isset($option_values)) { ?>
            <?php foreach($option_values as $value) { ?>
            <tr>
              <td><?php echo $value['product_name']; ?></td>
              <td><?php echo $value['sort']; ?></td>
              <td><?php echo $value['value']; ?></td>
              <td>
                <a href="<?php echo $value['delete']; ?>" class="btn btn-danger">Удалить</a>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
            <form action="<?php echo action_add; ?>" id="valueForm" method="post"
                  enctype="multipart/form-data">
              <tr>
                <td>
                  <input type="hidden" id="value_option_id" name="value_option_id"
                         value="<?php echo $custom_option['option_id']; ?>">
                  <input type="hidden" id="value_product_id" name="value_product_id">
                  <input class="form-control" type="text" name="value_product_name"
                         id="value_product_name" placeholder="Название товара" required>
                </td>
                <td>
                  <input class="form-control" type="number" name="value_sort" id="value_sort"
                         placeholder="0" required>
                </td>
                <td>
                  <input class="form-control" type="text" name="value_name" id="value_name"
                         placeholder="#FFF или текст" required>
                </td>
                <td>
                  <input type="submit" class="btn btn-primary" value="Добавить">
                </td>
              </tr>
            </form>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script>
    $('input[name=\'input-product\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['product_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('#product-id').val(item.value)
            $('#input-product').val(item.label)
        }
    });
    $('#value_product_name').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['product_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('#value_product_id').val(item.value)
            $('#value_product_name').val(item.label)
        }
    });
</script>