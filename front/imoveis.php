<title> Inicio </title>
<?php require_once("header.php"); ?>

<div class="container">
  <div class="row justify-content-md-center">
    <div class="col-8">
    <h1>Lista de imoveis</h1>

    <form id="cadastrarImovel">
      <div class="form-group">
        <label for="">Endereco</label>
        <input type="text" name="endereco" class="form-control" id="endereco" required>
      </div>
      <div class="form-group">
        <label for="proprietario">proprietario</label>
       <select class="form-select" aria-label="Selecione o proprietario" name="proprietarios" id="proprietarios">
      </select>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <button class="btn" id="pagina_anterior">Anterior</button>
    <button class="btn" id="proxima_pagina">Próxima</button>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">codigo</th>
          <th scope="col">Endereço</th>
          <th scope="col">Proprietario</th>
          <th scope="col">Apagar</th>
        </tr>
      </thead>
      <tbody id="casasTbody">
       
      </tbody>
    </table>
    </div>
  </div>
</div>

<script>
	(function() {
   		listarImoveis(0, 10)
      listarProprietarios(0, 1000)
	})();

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
            return data.json().then(function(casas) {
            let tabelaCasas = document.querySelector("#casasTbody")
            tabelaCasas.innerHTML = ""
      		  casas.dados.forEach(function(casa){
              let trElement = document.createElement("tr")
              let tdId = document.createElement("td")
              let tdEndereco = document.createElement("td")
              let tdProprietario = document.createElement("td")
              let tdApagar = document.createElement("td")
              let btnApagar = document.createElement('button')
              trElement.id = "casa_" + casa.id
              trElement.dataset.id =  casa.id
              trElement.dataset.endereco =  casa.endereco
              trElement.dataset.proprietario =  casa.proprietario
              tdId.innerHTML = casa.id
              tdEndereco.innerHTML= casa.endereco
              tdProprietario.innerHTML = casa.proprietario
              tdApagar.appendChild(btnApagar)
              btnApagar.innerHTML = "APAGAR"
              btnApagar.dataset.casa_id = casa.id
              btnApagar.classList.add("btn")
              btnApagar.classList.add("btn-primary")
              btnApagar.addEventListener("click", deletarImovel)
              trElement.appendChild(tdId)
              trElement.appendChild(tdEndereco)
              trElement.appendChild(tdProprietario)
              trElement.appendChild(tdApagar)
              tabelaCasas.appendChild(trElement)
            })
            console.log(casas)
            document.querySelector("#pagina_anterior").dataset.url = casas.anterior
            document.querySelector("#proxima_pagina").dataset.url= casas.proxima
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
            let selectProprietarios = document.querySelector("#proprietarios")
            proprietarios.dados.forEach(function(proprietario){
              let option = document.createElement('option')
              option.value = proprietario.id
              option.innerHTML =  proprietario.email
              selectProprietarios.appendChild(option)
            })
         });
        })
        
  }

 

  /*Cadastro de imovel */
  function cadastrarImovel(imovel){
       fetch(`http://localhost/imobiliaria/backend/imovel`, {
            method: 'POST',
            body: JSON.stringify(imovel),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(proprietarios) {
            if(data.status == 201){
              listarImoveis(0, 10)
              alert('Imovel cadastrado com sucesso')
            }
             else
              alert('Imovel já cadastrado')
         });
        })
        
  }
  btnCadastrarImovel = document.querySelector("#cadastrarImovel")
  btnCadastrarImovel.addEventListener("submit", (e)=>{
     e.preventDefault()
      let myJson = {
              "endereco": "",
              "proprietario_id" : "" 
      }
      myJson.endereco = this.endereco.value
      myJson.proprietario_id = this.proprietarios.value
        
      //console.log(myJson)
      cadastrarImovel(myJson)
  })

  function deletarImovel(){
       codigo = this.dataset.casa_id
      fetch(`http://localhost/imobiliaria/backend/imovel/${codigo}`, {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(casa) {
            if(data.status == 200){
              listarImoveis(0, 10)
              alert('Imovel apagado com sucesso')
            }
             else
              alert('Imovel não cadastrado')
         });
        })
  }

    /*configura paginacao*/
  document.querySelector("#proxima_pagina").addEventListener("click", function(){
    console.log(this)
    data = this.dataset.url.split("&")
    offset = data[0].split("=")[1]
    limit = data[1].split("=")[1]
    listarImoveis(offset, limit)
  })

  document.querySelector("#pagina_anterior").addEventListener("click", function(){
    data = this.dataset.url.split("&")
    offset = data[0].split("=")[1]
    limit = data[1].split("=")[1]
    listarImoveis(offset, limit)
  })




</script>
<?php require_once("footer.php"); ?>

<style>
  *  {
    margin:0;
    padding:0;
}

html, body {height:100%;}

.footer {
    position:absolute;
    bottom:0;
    width:100%;
}
</style>