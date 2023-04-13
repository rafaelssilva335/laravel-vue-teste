<template>
    <div class="container-nav">
      <nav>
        <ul class="flex">
          <li class="mr-6" :class="{ 'ativo': urlAtual === '/clientes' }">
            <a class="text-blue-500 hover:text-blue-800" href="/clientes">Listar Clientes</a>
          </li>
          <li class="mr-6" :class="{ 'ativo': urlAtual === '/clientes/create' }">
            <a class="text-blue-500 hover:text-blue-800" href="/clientes/create">Novo Cliente</a>
          </li>
        </ul>
      </nav>
    </div>
  </template>
  <script>
  export default {
    computed: {
      urlAtual() {
        return window.location.pathname;
      },
    },
  };
  </script>
  <style scoped>
  .container-nav {
    border-radius: 10px;
    border: solid 2px gray;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 40px;
    width: 100%;
  }
  .ativo {
    background-color: #e2e8f0;
    font-weight: bold;
  }
  </style> 