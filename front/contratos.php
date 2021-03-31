<title> Inicio </title>
<?php require_once("header.php"); ?>
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col-8">
      <h1>Lista de Contratos</h1>
      <form id="cadastrarContrato">
         <div class="form-group">
          <label for="imovel">Imovel</label>
          <select class="form-select" aria-label="Selecione o imovel" name="imovel" id="imovel" required>
            <option></option>
          </select>
        </div>
        <div class="form-group">
          <label for="imovel">Proprietario</label>
          <select class="form-select" aria-label="Selecione o prprietario" name="proprietario" id="proprietario" required disabled>
            <option></option>
          </select>
        </div>
        <div class="form-group">
          <label for="repasse">Dia do repasse</label>
          <select class="form-select" aria-label="Selecione o prprietario" name="repasse" id="repasse" required disabled>
            <option></option>
          </select>
        </div>
         <div class="form-group">
          <label for="cliente">Cliente</label>
          <select class="form-select" aria-label="Selecione o cliente" name="cliente" id="cliente" required>
            <option></option>
          </select>
        </div>
        <div class="form-group">
          <label for="">Início</label>
          <input type="date" name="data_inicio" class="form-control" id="data_inicio" required>
        </div>
        <div class="form-group">
          <label for="">fim</label>
          <input type="date" name="data_fim" class="form-control" id="data_fim" required>
        </div>
        <div class="form-group">
          <label for="">taxa administração</label>
          <input type="number" 
                 name="taxa_admin" 
                 class="form-control" 
                 id="taxa_admin" 
                 step="0.00" 
                 min="0.00" required>
        </div>
        <div class="form-group">
          <label for="">Valor Aluguel</label>
          <input type="number"
                 name="valor_aluguel" 
                 class="form-control" 
                 id="valor_aluguel" 
                 step="0.00" 
                 min="0.00" required>
        </div>
        <div class="form-group">
          <label for="">Valor Condominio</label>
          <input type="number" 
                 name="valor_condominio" 
                 class="form-control" 
                 id="valor_condominio" 
                 step="0.00" 
                 min="0.00" required>
        </div>
        <div class="form-group">
          <label for="">Valor Iptu</label>
          <input type="number" 
                 name="valor_iptu" 
                 class="form-control" 
                 id="valor_iptu" 
                 step="0.00" 
                 min="0.00" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>

      <button class="btn" id="pagina_anterior">Anterior</button>
      <button class="btn" id="proxima_pagina">Próxima</button>
      <!--Tabela contratos-->
      <table class="table">
        <thead>
          <tr>
            <th scope="col">codigo</th>
            <th scope="col">imovel</th>
            <th scope="col">proprietario</th>
            <th scope="col">cliente</th>
            <th scope="col">inicio</th>
            <th scope="col">fim</th>
            <th scope="col">taxa administração</th>
            <th scope="col">valor aluguel</th>
            <th scope="col">valor condominio</th>
            <th scope="col">valor iptu</th>
            <th colspan="3"><center>Ações</center></th>
          </tr>
        </thead>
        <tbody id="contratosTbody">
         
        </tbody>
      </table>


      <!-- Modal Mensalidades -->
      <div class="modal fade" id="mensalidadesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Mensalidades</h5>
            </div>
            <div class="modal-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">id</th>
                      <th scope="col">valor</th>
                      <th scope="col">vencimento</th>
                      <th scope="col">status</th>
                    </tr>
                  </thead>
                  <tbody id="mensalidadesTbody">
                   
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal Repasses -->
      <div class="modal fade" id="repassesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Repasses</h5>
            </div>
            <div class="modal-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Id Mens</th>
                      <th scope="col">Valor</th>
                      <th scope="col">Vencimento</th>
                      <th scope="col">status</th>
                    </tr>
                  </thead>
                  <tbody id="repassesTbody">
                   
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>

  </div>
</div>
</div>


<script>
	(function() {
   		listarContratos(0, 10)
      listarImoveis(0, 1000)
      listarClientes(0, 1000)
      listarProprietarios(0, 1000)
	})();

	function listarContratos(offset, limit){
		 fetch(`http://localhost/imobiliaria/backend/contrato/offset=${offset}&limit=${limit}`, {
            method: 'GET',
            //body: JSON.stringify(myJson),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(contratos) {
            let tabelaContratos = document.querySelector("#contratosTbody")
            tabelaContratos.innerHTML = ""
      		  contratos.dados.forEach(function(contrato){
              let trElement = document.createElement("tr")
              let tdId = document.createElement("td")
              let tdImovel = document.createElement("td")
              let tdProprietario = document.createElement("td")
              let tdCliente = document.createElement("td")
              let tdInicio = document.createElement("td")
              let tdFim = document.createElement("td")
              let tdTaxaAdmin = document.createElement("td")
              let tdValAluguel = document.createElement("td")
              let tdValCondominio = document.createElement("td")
              let tdValIptu = document.createElement("td")
              let tdMensalidades = document.createElement("td")
              let tdRepasses = document.createElement("td")
              let tdApagar = document.createElement("td")
              let btnApagar = document.createElement('button')
              let btnMensalidades = document.createElement('button')
              let btnRepasses = document.createElement('button')
              trElement.id = "proprietario_" + contrato.id
              tdId.innerHTML = contrato.id
              tdImovel.innerHTML= contrato.imovel
              tdProprietario.innerHTML = contrato.proprietario
              tdCliente.innerHTML = contrato.cliente
              tdInicio.innerHTML = contrato.inicio
              tdFim.innerHTML = contrato.fim
              tdTaxaAdmin.innerHTML = contrato.taxa_admin
              tdValAluguel.innerHTML = contrato.valor_aluguel
              tdValCondominio.innerHTML = contrato.valor_condominio
              tdValIptu.innerHTML = contrato.valor_iptu
              tdMensalidades.appendChild(btnMensalidades)
              btnMensalidades.innerHTML = "MENSALIDADES"
              btnMensalidades.dataset.contrato_id = contrato.id
              btnMensalidades.classList.add("btn")
              btnMensalidades.classList.add("btn-primary")
              btnMensalidades.addEventListener("click", carregaModalMensalidades)
              tdRepasses.appendChild(btnRepasses)
              btnRepasses.innerHTML = "REPASSES"
              btnRepasses.dataset.contrato_id = contrato.id
              btnRepasses.classList.add("btn")
              btnRepasses.classList.add("btn-primary")
              btnRepasses.addEventListener("click", carregaModalRepasses)
              tdApagar.appendChild(btnApagar)
              btnApagar.innerHTML = "APAGAR"
              btnApagar.dataset.contrato_id = contrato.id
              btnApagar.classList.add("btn")
              btnApagar.classList.add("btn-primary")
              btnApagar.addEventListener("click", deletarContrato)
              trElement.appendChild(tdId)
              trElement.appendChild(tdImovel)
              trElement.appendChild(tdProprietario)
              trElement.appendChild(tdCliente)
              trElement.appendChild(tdInicio)
              trElement.appendChild(tdFim)
              trElement.appendChild(tdTaxaAdmin)
              trElement.appendChild(tdValAluguel)
              trElement.appendChild(tdValCondominio)
              trElement.appendChild(tdValIptu)
              trElement.appendChild(tdMensalidades)
              trElement.appendChild(tdRepasses)
              trElement.appendChild(tdApagar)
              tabelaContratos.appendChild(trElement)
            })
            document.querySelector("#pagina_anterior").dataset.url = contratos.anterior
            document.querySelector("#proxima_pagina").dataset.url = contratos.proxima
   			 });
        })
        
	 }

   /*carrega repasses*/
   function carregaModalRepasses(){
    $('#repassesModal').modal('show')
    listarRepasses(this.dataset.contrato_id)
   }

   function listarRepasses(codigo){
    id = codigo
    fetch(`http://localhost/imobiliaria/backend/repasse/${id}`, {
            method: 'GET',
            //body: JSON.stringify(myJson),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(repasses) {
            let tabelaRepasses = document.querySelector("#repassesTbody")
            repasses.dados.forEach(function(repasse){
              let trElement = document.createElement("tr")
              let tdIdMensalidade = document.createElement("td")
              let tdValor = document.createElement("td")
              let tdDataVencimento = document.createElement("td")
              let tdStatus = document.createElement("td")
              let tdPagar = document.createElement("td")
              let btnPagar = document.createElement('button')
              tdIdMensalidade.innerHTML = repasse.mensalidade_id
              tdValor.innerHTML= repasse.valor
              tdDataVencimento.innerHTML = repasse.data_vencimento
              tdPagar.appendChild(btnPagar)
              btnPagar.innerHTML = repasse.status == "PENDENTE" ? "FAZER" : "FEITO"
              btnPagar.dataset.repasse_id = repasse.id
              btnPagar.dataset.mensalidade_id = repasse.mensalidade_id
              btnPagar.classList.add("btn")
              btnPagar.classList.add(repasse.status == "PENDENTE" ? "btn-primary" : "btn-success")
              btnPagar.addEventListener("click", fazerRepasse)
              trElement.appendChild(tdIdMensalidade)
              trElement.appendChild(tdValor)
              trElement.appendChild(tdDataVencimento)
              trElement.appendChild(tdPagar)
              tabelaRepasses.appendChild(trElement)
            })
         });
        })
   }

   /*carrega mensalidades*/
   function carregaModalMensalidades(){
    $('#mensalidadesModal').modal('show')
    listarMensalidades(this.dataset.contrato_id)
   }

   function listarMensalidades(codigo){
    id = codigo
    fetch(`http://localhost/imobiliaria/backend/mensalidade/${id}`, {
            method: 'GET',
            //body: JSON.stringify(myJson),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(mensalidades) {
            let tabelaMensalidades = document.querySelector("#mensalidadesTbody")
            mensalidades.dados.forEach(function(mensalidade){
              let trElement = document.createElement("tr")
              let tdId = document.createElement("td")
              let tdValor = document.createElement("td")
              let tdDataVencimento = document.createElement("td")
              let tdStatus = document.createElement("td")
              let tdPagar = document.createElement("td")
              let btnPagar = document.createElement('button')
              tdId.innerHTML = mensalidade.id
              tdValor.innerHTML= mensalidade.valor
              tdDataVencimento.innerHTML = mensalidade.data_vencimento
              tdPagar.appendChild(btnPagar)
              btnPagar.innerHTML = mensalidade.status == "PENDENTE" ? "PAGAR" : "PAGA"
              btnPagar.dataset.mensalidade_id = mensalidade.id
              btnPagar.classList.add("btn")
              btnPagar.classList.add(mensalidade.status == "PENDENTE" ? "btn-primary" : "btn-success")
              btnPagar.addEventListener("click", pagarMensalidade)
              trElement.appendChild(tdId)
              trElement.appendChild(tdValor)
              trElement.appendChild(tdDataVencimento)
              trElement.appendChild(tdPagar)
              tabelaMensalidades.appendChild(trElement)
            })
         });
        })
   }
 

  /*Cadastro de imovel */
  function cadastrarContrato(contratos){
       fetch(`http://localhost/imobiliaria/backend/contrato`, {
            method: 'POST',
            body: JSON.stringify(contratos),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(contratos) {
            if(data.status == 201){
              listarContratos(0, 10)
              alert('Contrato cadastrado com sucesso')
            }
             else
              alert('Conflito de contrato ')
         });
        })
        
  }
  btnCadastrarContrato = document.querySelector("#cadastrarContrato")
  btnCadastrarContrato.addEventListener("submit", (e)=>{
     e.preventDefault()
      let myJson = {
              "proprietario_id": "",
              "imovel_id": "",
              "cliente_id": "",
              "data_fim": "",
              "taxa_admin": "",
              "valor_aluguel": "",
              "valor_condominio": "",
              "valor_iptu": "",
              "data_inicio": "",
              "dia_repasse": ""
      }
      myJson.proprietario_id = this.proprietario.value
      myJson.imovel_id = this.imovel.value
      myJson.cliente_id = this.cliente.value
      myJson.data_fim = this.data_fim.value
      myJson.taxa_admin = this.taxa_admin.value
      myJson.valor_aluguel = this.valor_aluguel.value
      myJson.valor_condominio = this.valor_condominio.value
      myJson.valor_iptu = this.valor_iptu.value
      myJson.data_inicio = this.data_inicio.value
      myJson.dia_repasse = this.repasse.value
        
      //console.log(myJson)
      cadastrarContrato(myJson)
  })

  function deletarContrato(){
       codigo = this.dataset.contrato_id
      fetch(`http://localhost/imobiliaria/backend/contrato/${codigo}`, {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(contrato) {
            if(data.status == 200){
              listarContratos(0, 10)
              alert('Contrato apagado com sucesso')
            }
             else
              alert('Contrato não cadastrado')
         });
        })
  }

   function pagarMensalidade(){
       codigo = this.dataset.mensalidade_id
       if(this.innerHTML == "PAGA")
          return
       btn = this
        let myJson = {
              "novo_status": "PAGA"
            }

      fetch(`http://localhost/imobiliaria/backend/mensalidade/${codigo}`, {
            method: 'PUT',
            body: JSON.stringify(myJson),
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(contrato) {
            if(data.status == 200){
              alert('Mensalidade paga com sucesso')
              btn.classList.remove('btn-primary')
              btn.classList.add('btn-success')
              btn.innerHTML = "PAGA"
            }
             else
              alert('Falhou')
         });
        })
  }

  function fazerRepasse(){
       codigo = this.dataset.repasse_id
       mensalidadeId= this.dataset.mensalidade_id
       if(this.innerHTML == "FEITO")
          return
       btn = this
        let myJson = {
              "novo_status": "FEITO",
              "mensalidade_id": mensalidadeId
            }

      fetch(`http://localhost/imobiliaria/backend/repasse/${codigo}`, {
            method: 'PUT',
            body: JSON.stringify(myJson),
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(repasse) {
            if(repasse.status == "Repasse alterado com sucesso"){
              alert('repasse feito com sucesso')
              btn.classList.remove('btn-primary')
              btn.classList.add('btn-success')
              btn.innerHTML = "FEITO"
            }
             else
             {
              alert(repasse.status)
             }
         });
        })
  }

  function listarImoveis(offset, limit){
     fetch(`http://localhost/imobiliaria/backend/imovel/offset=${offset}&limit=${limit}`, {
            method: 'GET',
            //body: JSON.stringify(myJson),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(imoveis) {
            let selectImoveis = document.querySelector("#imovel")
            imoveis.dados.forEach(function(imovel){
              let option = document.createElement('option')
              option.value = imovel.id
              option.innerHTML =  imovel.endereco
              selectImoveis.appendChild(option)
            })
         });
        })
    }

     function listarClientes(offset, limit){
     fetch(`http://localhost/imobiliaria/backend/cliente/offset=${offset}&limit=${limit}`, {
            method: 'GET',
            //body: JSON.stringify(myJson),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(clientes) {
            let selectClientes = document.querySelector("#cliente")
            clientes.dados.forEach(function(cliente){
              let option = document.createElement('option')
              option.value = cliente.id
              option.innerHTML =  cliente.email
              selectClientes.appendChild(option)
            })
         });
        })   
     }

     function listarProprietarios(offset, limit){
     fetch(`http://localhost/imobiliaria/backend/proprietario/offset=${offset}&limit=${limit}`, {
            method: 'GET',
            //body: JSON.stringify(myJson),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(proprietarios) {
            let selectProprietarios = document.querySelector("#proprietario")
            let selectDiaRepasse = document.querySelector("#repasse")
            proprietarios.dados.forEach(function(proprietario){
              let option = document.createElement('option')
              option.value = proprietario.id
              option.innerHTML =  proprietario.email
              selectProprietarios.appendChild(option)

              let optionRepasse = document.createElement('option')
              optionRepasse.value = proprietario.dia_repasse
              optionRepasse.innerHTML = proprietario.dia_repasse
              selectDiaRepasse.appendChild(optionRepasse)
            })
         });
        })   
     }


     /*selecionando proprietario de acordo com o imovel selecionado*/
     selectImoveis = document.querySelector("#imovel")
     selectImoveis.addEventListener("change", function(){
        selectProprietario = document.querySelector("#proprietario")
        selectRepasse = document.querySelector("#repasse")
        selectProprietario.selectedIndex = selectImoveis.selectedIndex
        selectRepasse.selectedIndex = selectProprietario.selectedIndex
     })


  /*configura paginacao*/
  document.querySelector("#proxima_pagina").addEventListener("click", function(){
    data = this.dataset.url.split("&")
    offset = data[0].split("=")[1]
    limit = data[1].split("=")[1]
    listarContratos(offset, limit)
  })

  document.querySelector("#pagina_anterior").addEventListener("click", function(){
    data = this.dataset.url.split("&")
    offset = data[0].split("=")[1]
    limit = data[1].split("=")[1]
    listarContratos(offset, limit)
  })


  
</script>
<?php require_once("footer.php"); ?>