<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
          <a href="<?php echo $action; ?>" data-toggle="tooltip" class="btn btn-primary" data-original-title="Добавить"><i class="fa fa-plus-circle"></i></a>
          <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Настройки модуля</h3>
      </div>
      <div class="panel-body">
          <table class="table table-responsive">
              <tr>
                  <th>Товар:</th>
                  <th>Тип:</th>
                  <th>Сортировка:</th>
                  <th>Название:</th>
                  <th>Действия:</th>
              </tr>
              <?php if (isset($custom_options)) { ?>
              <?php foreach ($custom_options as $option) { ?>
              <tr>
                  <form>
                      <td><?php echo $option['product_name']; ?></td>
                      <td>
                          <?php if ($option['type'] == 1) { ?>
                           Цвет
                          <?php } ?>
                          <?php if ($option['type'] == 2) { ?>
                           Текст
                          <?php } ?>
                      </td>
                      <td><?php echo $option['sort']; ?></td>
                      <td><?php echo $option['name']; ?></td>
                      <td>
                          <a href="<?php echo $option['edit_link']; ?>" data-toggle="tooltip" data-original-title="Редактировать" class="btn btn-info"><i class="fa fa-pencil"></i></a>
                          <a href="<?php echo $option['copy']; ?>" data-toggle="tooltip" data-original-title="Копировать" class="btn btn-primary"><i class="fa fa-copy"></i></a>
                          <a href="<?php echo $option['delete']; ?>" data-toggle="tooltip" data-original-title="Удалить" class="btn btn-warning"><i class="fa fa-trash-o"></i></a>
                      </td>
                  </form>
              </tr>
              <?php } ?>
              <?php } ?>
          </table>
      </div>
    </div>
	</div>
  </div>
<?php echo $footer; ?>