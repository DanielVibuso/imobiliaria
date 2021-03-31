<title> Inicio </title>
<?php require_once("header.php"); ?>

<div class="container">
  <div class="row justify-content-md-center">
    <div class="col-8">
    <h1>Lista de Proprietarios</h1>
    <form id="cadastrarProprietario">
      <div class="form-group">
        <label for="">Nome</label>
        <input type="text" name="nome" class="form-control" id="nome" required>
      </div>
      <div class="form-group">
        <label for="">email</label>
        <input type="email" name="email" class="form-control" id="email" required>
      </div>
      <div class="form-group">
        <label for="cliente">telefone</label>
        <input type="text" name="telefone" class="form-control" id="telefone" required>
      </div>
      <div class="form-group">
        <label for="dia_repasse">Dia repasse</label>
        <input type="text" name="dia_repasse" class="form-control" id="dia_repasse" required>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <button class="btn" id="pagina_anterior">Anterior</button>
    <button class="btn" id="proxima_pagina">Próxima</button>
    <table class="table">
      <thead>
        <tr>
          <th scope="col">codigo</th>
          <th scope="col">nome</th>
          <th scope="col">email</th>
          <th scope="col">telefone</th>
          <th scope="col">Dia repasse</th>
          <th scope="col">Apagar</th>
        </tr>
      </thead>
      <tbody id="proprietariosTbody">
       
      </tbody>
    </table>
  </div>
</div>
</div>

<script>
	(function() {
   		listarProprietarios(0, 10)
	})();

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
            let tabelaProprietarios = document.querySelector("#proprietariosTbody")
            tabelaProprietarios.innerHTML = ""
      		  proprietarios.dados.forEach(function(proprietario){
              let trElement = document.createElement("tr")
              let tdId = document.createElement("td")
              let tdNome = document.createElement("td")
              let tdEmail = document.createElement("td")
              let tdTelefone = document.createElement("td")
              let tdDiaRepasse = document.createElement("td")
              let tdApagar = document.createElement("td")
              let btnApagar = document.createElement('button')
              trElement.id = "proprietario_" + proprietario.id
              trElement.dataset.id =  proprietario.id
              trElement.dataset.email =  proprietario.email
              trElement.dataset.telefone =  proprietario.telefone
              tdId.innerHTML = proprietario.id
              tdNome.innerHTML= proprietario.nome
              tdEmail.innerHTML= proprietario.email
              tdDiaRepasse.innerHTML = proprietario.dia_repasse
              tdTelefone.innerHTML = proprietario.telefone
              tdApagar.appendChild(btnApagar)
              btnApagar.innerHTML = "APAGAR"
              btnApagar.dataset.proprietario_id = proprietario.id
              btnApagar.classList.add("btn")
              btnApagar.classList.add("btn-primary")
              btnApagar.addEventListener("click", deletarProprietario)
              trElement.appendChild(tdId)
              trElement.appendChild(tdNome)
              trElement.appendChild(tdEmail)
              trElement.appendChild(tdTelefone)
              trElement.appendChild(tdDiaRepasse)
              trElement.appendChild(tdApagar)
              tabelaProprietarios.appendChild(trElement) 
            })
            document.querySelector("#pagina_anterior").dataset.url = proprietarios.anterior
            document.querySelector("#proxima_pagina").dataset.url=proprietarios.proxima
   			 });
        })
        
	 }
 

  /*Cadastro de imovel */
  function cadastrarProprietario(proprietario){
       fetch(`http://localhost/imobiliaria/backend/proprietario`, {
            method: 'POST',
            body: JSON.stringify(proprietario),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(proprietarios) {
            if(data.status == 201){
              listarProprietarios(0, 10)
              alert('Proprietario cadastrado com sucesso')
            }
             else
              alert('Proprietario já cadastrado')
         });
        })
        
  }
  btnCadastrarProprietario = document.querySelector("#cadastrarProprietario")
  btnCadastrarProprietario.addEventListener("submit", (e)=>{
     e.preventDefault()
      let myJson = {
              "nome" : "",
              "email": "",
              "telefone" : "",
              "dia_repasse": ""
      }
      myJson.nome = this.nome.value
      myJson.email = this.email.value
      myJson.telefone = this.telefone.value
      myJson.dia_repasse = this.dia_repasse.value
        
      //console.log(myJson)
      cadastrarProprietario(myJson)
  })

  function deletarProprietario(){
       codigo = this.dataset.proprietario_id
      fetch(`http://localhost/imobiliaria/backend/proprietario/${codigo}`, {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(proprietario) {
            if(data.status == 200){
              listarProprietarios(0, 10)
              alert('Proprietario apagado com sucesso')
            }
             else
              alert('proprietario não cadastrado')
         });
        })
  }

  /*configura paginacao*/
  document.querySelector("#proxima_pagina").addEventListener("click", function(){
    data = this.dataset.url.split("&")
    offset = data[0].split("=")[1]
    limit = data[1].split("=")[1]
    listarProprietarios(offset, limit)
  })

  document.querySelector("#pagina_anterior").addEventListener("click", function(){
    data = this.dataset.url.split("&")
    offset = data[0].split("=")[1]
    limit = data[1].split("=")[1]
    listarProprietarios(offset, limit)
  })



</script>
<?php require_once("footer.php"); ?>