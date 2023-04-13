<template>
  <div class="container">
    <NavBarComponent />
    <clientes-busca @my-event="handleResultadoRequisicao" />
    <div class="content-table">
      <h2 class="text-lg font-medium mb-4">Resultado da pesquisa</h2>
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th></th>
            <th>Nome</th>
            <th>CPF</th>
            <th>Data de Nascimento</th>
            <th>Sexo</th>
            <th>Estado</th>
            <th>Cidade</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cliente in clientes" :key="cliente.id">
            <td>
              <a class="btn btn-sm btn-primary mr-2" :href="'/clientes/' + cliente.id + '/editar'">
                Editar
              </a>
            </td>
            <td>
              <a class="btn btn-sm btn-danger" @click="excluirCliente(cliente)">
                Excluir
              </a>
            </td>
            <td>{{ cliente.nome }}</td>
            <td>{{ cliente.cpf }}</td>

            <td>{{ cliente.data_nascimento }}</td>
            <td>{{ cliente.sexo }}</td>
            <td>{{ cliente?.endereco?.cidade }}</td>
            <td>{{ cliente?.endereco?.estado }}</td>
            
          </tr>
        </tbody>
      </table>
      <TailwindPagination :class="['justfy-center']" :data="clientesPaginate" @pagination-change-page="getClients" :limit="5" />

    </div>

  </div>
</template>
  
<script>
import axios from 'axios';
import ClientesBusca from './ClientesBusca.vue';
import Paginate from 'vuejs-paginate'
import Pagination from 'vue-pagination-2';
import { TailwindPagination } from 'laravel-vue-pagination';
import NavBarComponent from './NavBar.vue';

export default {
  data() {
    return {
      clientes: [],
      clientesPaginate: [],
      pagination: {},
      total: 0,
      perPage: 5,
      currentPage: 1,
      lastPage: 0,
      from: 0,
      to: 0,
      pagesNumber: [],
      clients: []
    };
  },
  components: {
    ClientesBusca,
    TailwindPagination,
    NavBarComponent
  },
  created() {
  },
  mounted() {
    this.getClients();
  },
  computed: {
    clientesPaginados() {
      const indexInicio = (this.paginaAtual - 1) * this.itensPorPagina;
      const indexFim = indexInicio + this.itensPorPagina;
      return this.clientes.slice(indexInicio, indexFim);
    },
    pageCount() {
      return Math.ceil(this.clientes.length / this.itensPorPagina);
    },

    isActived() {
      return this.pagination.current_page;
    }
  },
  methods: {
    getClients(page = 1) {

      axios.get(`/api/clientes?page=${page}`)
        .then(response => {
          this.clientes = response.data.data;
          this.clientesPaginate = response.data;

        })
        .catch(error => {
          console.log(error);
        });
    },
    handleResultadoRequisicao(resultado) {
      this.clientes = resultado.data;
      this.clientesPaginate = resultado;
    },
    excluirCliente(cliente) {
      if (confirm('Tem certeza que deseja excluir o cliente ' + cliente.nome + '?')) {
        axios.delete('/api/clientes/' + cliente.id)
          .then(response => {
            this.clientes = this.clientes.filter(c => c.id !== cliente.id);
          })
          .catch(error => {
            console.log(error);
          });
      }
    },
    paginarClientes(pageNum) {
      this.clientesPaginate.paginaAtual = pageNum;
    },
    changePage(page) {
      this.clientesPaginate.currentPage = page;
      this.getClients();
    }
  },
};
</script>

<style >
.table {
  width: 100%;
  margin-bottom: 1rem;
  background-color: #fff;
  border-collapse: collapse;
}

.table td,
.table th {
  padding: 0.75rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}

.table th {
  text-align: inherit;
  border-bottom: 2px solid #dee2e6;
  background-color: #f5f5f5;
}

.btn {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: 0.25rem;
}

.btn-primary {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.btn-danger {
  color: #fff;
  background-color: #dc3545;
  border-color: #dc3545;
  cursor: pointer;
}

.btn-primary:hover,
.btn-primary:focus {
  color: #fff;
  background-color: #0069d9;
  border-color: #0062cc;
}

.btn-danger:hover,
.btn-danger:focus {
  color: #fff;
  background-color: #c82333;
  border-color: #bd2130;
}

.btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 0.2rem;
}

.mr-2 {
  margin-right: 0.5rem;
}

.container {
  max-width: 936px;
  border: solid 2px gray;
  padding: 10px;
  gap: 10px;
  border-radius: 10px;
}

.content-table {
  border: solid 2px gray;
  border-radius: 10px;
  padding: 10px;
  gap: 18px;
  display: grid;
}

.justfy-center{
  justify-content: center;
}
tr:nth-child(even) {
  background-color: #f2f2f2;
}
</style>