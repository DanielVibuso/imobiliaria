<title> Inicio </title>
<?php require_once("header.php"); ?>
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col-8">
    <h1>Lista de Clientes</h1>
    <form id="cadastrarCliente">
       <div class="form-group">
        <label for="">Nome</label>
        <input type="text" name="nome" class="form-control" id="nome" required>
      </div>
      <div class="form-group">
        <label for="">email</label>
        <input type="email" name="email" class="form-control" id="email">
      </div>
      <div class="form-group">
        <label for="cliente">telefone</label>
        <input type="text" name="telefone" class="form-control" id="telefone">
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
          <th scope="col">Apagar</th>
        </tr>
      </thead>
      <tbody id="clientesTbody">
       
      </tbody>
    </table>
   </div>
 </div>
</div>

<script>
	(function() {
   		listarClientes(0, 10)
	})();

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
            let tabelaClientes = document.querySelector("#clientesTbody")
            tabelaClientes.innerHTML = ""
      		  clientes.dados.forEach(function(cliente){
              let trElement = document.createElement("tr")
              let tdId = document.createElement("td")
              let tdNome = document.createElement("td")
              let tdEmail = document.createElement("td")
              let tdTelefone = document.createElement("td")
              let tdApagar = document.createElement("td")
              let btnApagar = document.createElement('button')
              trElement.id = "cliente_" + cliente.id
              trElement.dataset.id =  cliente.id
              trElement.dataset.email =  cliente.email
              trElement.dataset.telefone =  cliente.telefone
              tdId.innerHTML = cliente.id
              tdNome.innerHTML = cliente.nome
              tdEmail.innerHTML= cliente.email
              tdTelefone.innerHTML = cliente.telefone
              tdApagar.appendChild(btnApagar)
              btnApagar.innerHTML = "APAGAR"
              btnApagar.dataset.cliente_id = cliente.id
              btnApagar.classList.add("btn")
              btnApagar.classList.add("btn-primary")
              btnApagar.addEventListener("click", deletarCliente)
              trElement.appendChild(tdId)
              trElement.appendChild(tdNome)
              trElement.appendChild(tdEmail)
              trElement.appendChild(tdTelefone)
              trElement.appendChild(tdApagar)
              tabelaClientes.appendChild(trElement)
            })
            document.querySelector("#pagina_anterior").dataset.url = clientes.anterior
            document.querySelector("#proxima_pagina").dataset.url=clientes.proxima
   			 });
        })
        
	 }
 

  /*Cadastro de imovel */
  function cadastrarCliente(cliente){
       fetch(`http://localhost/imobiliaria/backend/cliente`, {
            method: 'POST',
            body: JSON.stringify(cliente),
            mode: 'no-cors',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(clientes) {
            if(data.status == 201){
              alert('Cliente cadastrado com sucesso')
              listarClientes(0, 10)
            }
             else
              alert('Cliente já cadastrado')
         });
        })
        
  }
  btnCadastrarCliente = document.querySelector("#cadastrarCliente")
  btnCadastrarCliente.addEventListener("submit", (e)=>{
     e.preventDefault()
      let myJson = {
              "nome": "",
              "email": "",
              "telefone" : "" 
      }
      myJson.nome = this.nome.value
      myJson.email = this.email.value
      myJson.telefone = this.telefone.value
        
      //console.log(myJson)
      cadastrarCliente(myJson)
  })

  function deletarCliente(){
       codigo = this.dataset.cliente_id
      fetch(`http://localhost/imobiliaria/backend/cliente/${codigo}`, {
            method: 'DELETE',
            headers: {
                'content-type': 'application/json'
            }
        })
        .then(function (data) { 
            return data.json().then(function(cliente) {
            if(data.status == 200){
              listarClientes(0, 10)
              alert('Cliente apagado com sucesso')
            }
             else
              alert('cliente não cadastrado')
         });
        })
  }

  /*configura paginacao*/
  document.querySelector("#proxima_pagina").addEventListener("click", function(){
    data = this.dataset.url.split("&")
    offset = data[0].split("=")[1]
    limit = data[1].split("=")[1]
    listarClientes(offset, limit)
  })

  document.querySelector("#pagina_anterior").addEventListener("click", function(){
    data = this.dataset.url.split("&")
    offset = data[0].split("=")[1]
    limit = data[1].split("=")[1]
    listarClientes(offset, limit)
  })



</script>
<?php require_once("footer.php"); ?>