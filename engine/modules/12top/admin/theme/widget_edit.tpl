<div class="panel panel-default">
	<div class="panel-heading">
		Редактирование виджета
	</div>
	<div class="row box-section">	
		<div class="col-sm-12">
					
				<form method="POST" id="editwidget" class="form-horizontal">
		            <div class="panel-tab-content tab-content">			
		                <div class="tab-pane active" id="tabhome">
							<div class="panel-body">
								<div class="form-group">
									<label class="control-label col-sm-2">Заголовок топа</label>
									<div class="col-sm-10">
										<input type="text" class="form-control width-550 position-left" name="title" value="{$name}" required>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Тип</label>
									<div class="col-sm-10">
										<select name="type" class="form-control width-550 position-left" required>
											{foreach $type_options as $opt}
											<option value="{$opt[id]}" {if $opt[id]==$type}selected{/if}>{$opt[name]}</option>
											{/foreach}
										</select>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Сортировка</label>
									<div class="col-sm-10">
										<select name="sortby" class="form-control width-550 position-left" required>
											<option value="valdesc" {if $sortby=="valdesc"}selected{/if}>По убыванию</option>
											<option value="valasc" {if $sortby=="valasc"}selected{/if}>По возрастанию</option>
										</select>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Количество</label>
									<div class="col-sm-10">
										<input type="number" class="form-control width-200 position-left" name="count" min="1" value="{$count}" required>
									</div>	
								</div>
							</div>
							<div class="panel-footer">
								<button type="submit" name="editwidget" class="btn bg-teal btn-sm btn-raised position-left"><i class="fa fa-plus position-left"></i>Изменить виджет</button>
							</div>
						</div>
					</div>
				</form>
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