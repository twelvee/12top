<div class="panel panel-default">
	<div class="panel-heading">
		Добавление виджета
	</div>
	<div class="row box-section">	
		<div class="col-sm-12">
					
				<form method="post" name="addwidget" id="addwidget" class="form-horizontal">
		            <div class="panel-tab-content tab-content">			
		                <div class="tab-pane active" id="tabhome">
							<div class="panel-body">
								<div class="form-group">
									<label class="control-label col-sm-2">Заголовок топа</label>
									<div class="col-sm-10">
										<input type="text" class="form-control width-550 position-left" name="title" required>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Тип</label>
									<div class="col-sm-10">
										<select name="type" class="form-control width-550 position-left" required>
											{foreach $type_options as $opt}
											<option value="{$opt[id]}">{$opt[name]}</option>
											{/foreach}
										</select>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Поле</label>
									<div class="col-sm-10">
										<select name="field" class="form-control width-550 position-left" required>

										</select>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Сортировка</label>
									<div class="col-sm-10">
										<select name="sortby" class="form-control width-550 position-left" required>
											<option value="valdesc">По убыванию</option>
											<option value="valasc">По возрастанию</option>
										</select>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Количество</label>
									<div class="col-sm-10">
										<input type="number" class="form-control width-200 position-left" name="count">
									</div>	
								</div>
							</div>
							<div class="panel-footer">
								<button type="submit" class="btn bg-teal btn-sm btn-raised position-left"><i class="fa fa-plus position-left"></i>Добавить виджет</button>
							</div>
						</div>
					</div>
				</form>
	  	</div>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		Список виджетов
	</div>
	<div class="row box-section">	
		<div class="col-sm-12">
			<div class="panel-body">
				<table class="table table-hover">
					<thead>
						<td>Название</td>
						<td>Тип</td>
						<td>Сортировка</td>
						<td>Количество</td>
						<td>Тег</td>
						<td>Управление</td>
					</thead>

					<tbody>
						{if $tableval!=""}
						{foreach $tableval as $tv}
						<tr>
							<td>{$tv[name]}</td>
							<td>{$tv[sel_table]}</td>
							<td>{$tv[sortby]}</td>
							<td>{$tv[count]}</td>
							<td>{$tv[tag]}</td>
							<td><a href="?mod=12top&action=widget&edit={$tv[id]}">Изменить</a> &nbsp; <a href="?mod=12top&action=widget&delete={$tv[id]}">Удалить</a></td>
						</tr>
						{/foreach}
						{else}
						<tr><td colspan="6">Виджетов не найдено. Добавьте свои с помощью формы выше.</td></tr>
						{/if}
					</tbody>
				</table>
			</div>
	  	</div>
	</div>
</div>

<script type="text/javascript">
	$("select[name='type']").on('change', function(){
		$.post('/engine/ajax/12top.php', { "action": "getFieldByType", "type": $(this).val() }, onSuccess);
	});
	function onSuccess(data){
		var info = JSON.Parse(data);
		if(info.error==false){
			$('select[name="field"]').html(data.fields);
		}else{
			alert('Ошибка получения данных.');
		}
	}
</script>