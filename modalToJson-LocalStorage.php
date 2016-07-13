
<div class="tab-pane fade in" id="valores">
	<div class="panel-body">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Valores
					<a href="#novo">
						<button class="btn btn-primary pull-right"  data-toggle="modal" data-target="#modal">Novo Valor</button>
					</a>
				</div>
				<div class="panel-body">
					<table  id="tabelaValores" data-toggle="table" 
						   data-sort-name="descricao" 
						   data-sort-order="desc">
					    <thead>
					    <tr>
					        <th data-field="segmento" data-sortable="true">Segmento</th>
					        <th data-field="descricao" data-sortable="true">Descrição</th>
					        <th data-field="obs"  data-sortable="true">Observação</th>
					        <th data-field="valor"  data-sortable="true">Valor</th>
					        <th data-field="ativo" data-sortable="true"  xdata-formatter="formatAtivo">Ativo</th>

						        <th data-field="action"
				                    data-align="center"
				                    xdata-formatter="botaoUpdateRemove"
				                    data-events="actionEvents">Ação</th>
					    </tr>
					    </thead>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<!-- Form -->
    <form class='form' id='modalValorForm'>
	<input type=hidden name=id class='form-control' placeholder= ''>	
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Novo Valor</h4>
            </div><!-- ./modal-header -->

            <div class="modal-body col-md-12">

				<div class="col-md-6">
					<div class="form-group">	
						<label for="segmento">Segmento</label>
						<input type=text name=segmento class='form-control' placeholder= ''>			

					</div>
				</div>
				

				<div class="form-group col-md-12">
					Descrição
					<input type=text name=descricao class='form-control' placeholder= ''>
				</div>



				<div class="form-group col-md-6">	
					Valor

					<div class="input-group col-md-12" for="valor">
					  <span class="input-group-addon">R$</span>
					  <input type=text name=valor class='form-control' placeholder= ''>
					  <span class="input-group-addon">,00</span>
					</div>

				</div>


				<div class="form-group col-md-6">	
					Ativo
					<div class="input-group col-md-12">
						<input type=checkbox name=ativo>
					</div>
				</div><!-- /12 -->

			</div><!-- ./modal-body -->
       		<div class="modal-footer">
                <button id='butModal' class='btn btn-primary text-center'>Cadastrar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        	</div><!-- ./modal-footer -->

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
	<form>
</div><!-- /.modal -->

	<script src="js/jquery-1.12.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-table.js"> //Tabela Dinamica </script>
	<script src="js/lockr.min.js"> //localStorage </script>

	<script>
	
    var $tabelaValores = $('#tabelaValores'); //JSON.parse
	var tamanhoStorage = retornaTotal(localStorage.condominioValores);
	console.log("Tam " + tamanhoStorage)
	condominioValores = [];
	if(tamanhoStorage > 0){
		condominioValores = Lockr.smembers("condominioValores");
	} 

	function adicionaDados(conteudo) {
		Lockr.sadd("condominioValores", conteudo);
		console.log("Conteudo : " + Lockr.smembers("condominioValores"));
		$tabelaValores.bootstrapTable('refresh');
	}

	function ConvertFormToJSON(form){
	    var array = jQuery(form).serializeArray();
		   	array.shift(); //remove o primeiro = Token!
	    var json = {};
	    
	    jQuery.each(array, function() {
	        if(this.name == "id" && this.value == "") 
	        	json["id"] = (retornaTotal(localStorage.condominioValores)+1);
		    else 
		    	json[this.name] = this.value || '';
	    });
	    
	    return json;
	}

	function retornaTotal(conteudo){
		if(conteudo == null) return 0; //'Falta informar o conteudo';
		if(!conteudo) return 0;
		obj = jQuery.parseJSON(conteudo);
	    var size = 0;
	    jQuery.each(obj.data, function() {
			size++;
	    })
		console.log("size " +size)
	    return size;
	}

 	$tabelaValores.bootstrapTable({
 		data: condominioValores,
 		load: condominioValores,
 		showRefresh:true, 
 		locale:'pt-BR', 
 		hideLoading:false, 
 		sidePagination:'data-side-pagination'
 	});

	</script>

	<script type="text/javascript">

		// Modal.onclose(function() {
		$("#butModal").on("click",function(event) {
			var frm = $('#modalValorForm');
			json = ConvertFormToJSON(frm);
		   	adicionaDados(json);
		  event.preventDefault();
		});

	    function showModal(title, row) {
		        row = row || {
		            nome: 'Novo Valor',
		            ativo: 1
		        }; // default row value

		        $modal.data('id', row.id);
		        $modal.data('ativo', row.ativo);
		        $modal.find('.modal-title').text(title);
		        for (var name in row) {
		            $modal.find('input[name="' + name + '"]').val(row[name]);
		            //input:checked
		            if ($modal.find('input[name="' + name + '"][type="checkbox"]') && row[name] == 1)
		            	$('input[name="' + name + '"]').prop('checked', true)
		            else
		            	$('input[name="' + name + '"]').prop('checked', false)
		            
		        }
		        $modal.modal('show');
	  }

	</script>

