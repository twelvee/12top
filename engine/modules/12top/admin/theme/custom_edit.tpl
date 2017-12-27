<div class="panel panel-default">
	<div class="panel-heading">
		Редактировать тип
	</div>
	<div class="row box-section">	
		<div class="col-sm-12">
					
				<form method="POST" id="editcustom" class="form-horizontal">
		            <div class="panel-tab-content tab-content">			
		                <div class="tab-pane active" id="tabhome">
							<div class="panel-body">
								<div class="form-group">
									<label class="control-label col-sm-2">Заголовок типа</label>
									<div class="col-sm-10">
										<input type="text" class="form-control width-550 position-left" name="title" value="{$name}" required>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Таблица (ex. ПРЕФИКС_users) </label>
									<div class="col-sm-10">
										<input type="text" value="{$sel_table}" class="form-control width-550 position-left" name="table" required> <i class="help-button visible-lg-inline-block text-primary-600 fa fa-question-circle position-right" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Для доп. полей новостей укажите dle_post    Для доп. полей пользователей укажите dle_users"></i>
									</div>	
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Тип ячейки в таблице</label>
									<div class="col-sm-10">
										<select name="type" class="form-control width-550 position-left">
											<option value="3" {if $type==3}selected{/if}>Кастомная ячейка</option>
											<option value="1" {if $type==1}selected{/if}>Доп поле пользователя</option>
											<option value="2" {if $type==2}selected{/if}>Доп поле новостей</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2">Ячейка</label>
									<div class="col-sm-10" id="queY">
										<input type="text" value="{$sel_value}" name="sel_value" class="form-control width-550 position-left" required>
									</div>	
								</div>
							</div>
							<div class="panel-footer">
								<button type="submit" name="editcustom" class="btn bg-teal btn-sm btn-raised position-left"><i class="fa fa-plus position-left"></i>Изменить тип виджета</button>
							</div>
						</div>
					</div>
				</form>
	  	</div>
	</div>
</div>

<script type="text/javascript">

	$("select[name='type']").on('change', function(){
		$.post('engine/ajax/12top.php', { "action": "getCustomize", "type": $(this).val() }, onSuccess);
	});
	function onSuccess(data){
		console.log(data);
		var info = JSON.parse(data);
		if(info.error==false){
			if(info.type==1 || info.type==2){
				$('#queY').html('<select name="sel_value" class="form-control width-550 position-left" required>'+info.xfields+'</select>');
				if(info.type==1) $('input[name="table"]').val('{$prefix}_users');
				else $('input[name="table"]').val('{$prefix}_post');
			}else{
				$('#queY').html('<input type="text" name="sel_value" class="form-control width-550 position-left" required>');
			}
		}else{
			alert('Ошибка получения данных.');
		}
	}
</script>