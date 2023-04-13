<script>
import axios from 'axios';
import NavBarComponent from './NavBar.vue';

export default {
    data() {
        return {
            form: ({
                nome: '',
                cpf: '',
                data_nascimento: '',
                sexo: '',
                endereco: '',
                estado: '',
                cidade: '',
                get cpfFormatado() {
                    if (!this.cpf) {
                        return this.cpf;
                    }                    return this.cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
                },
                set cpfFormatado(value) {
                    this.cpf = value.replace(/\D/g, '');
                },
                formatCpf() {
                    let cleaned = this.cpf.replace(/[^\d]/g, '');

                    if (cleaned.length > 11) {
                        cleaned = cleaned.slice(0, 11);
                    }

                    this.cpf = cleaned;
                    this.cpfFormatado = this.cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');

                },
            }),
            errors: ({}),
            clienteId: null,
            estados: [],
            cidades: [],
            selectedEstado: '',
            selectedCidade: ''
        };
    },
    components: {
        NavBarComponent
    },
    created() {
        this.clienteId = this.$page.props.id;
        
        this.buscarCliente();
        this.carregarEstados();
    },
    methods: {
        buscarCliente() {
            axios.get(`/api/clientes/0`)
                .then(response => {
                    const cliente = response.data;
                    this.form.nome = cliente.nome;
                    this.form.cpf = cliente.cpf;
                    this.form.data_nascimento = cliente.data_nascimento;
                    this.form.sexo = cliente.sexo;
                    this.form.endereco = cliente.endereco;
                    this.form.estado = cliente.estado;
                    this.form.cidade = cliente.cidade;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        submitForm() {
            axios.get(`/api/clientes-search`, {params:this.form})
                .then(response => {
                    this.$emit('my-event', response.data);
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        const newErrors = (error.response.data.errors);
                        this.errors = newErrors;
                    } else {
                        console.log(error);
                    }
                });
        },
        carregarEstados() {
            axios.get('/api/estados')
                .then(response => {
                    this.estados = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        carregarCidades() {
            axios.get('/api/cidades/' + this.selectedEstado)
                .then(response => {
                    this.cidades = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        limpar: function () {
            this.form.nome = '';
            this.form.cpf = '';
            this.form.data_nascimento = '';
            this.form.sexo = '';
            this.form.endereco = '';
            this.form.estado = '';
            this.form.cidade = '';

            this.submitForm();
        },
    },
};
</script>

<template>
        <div>
            <img src="/images/servlet.png"
                alt="">
            <div class="cadastro">
                <h2 class="text-lg font-medium mb-4">Consulta cliente</h2>
                <form @submit.prevent="submitForm">
                    <div class="input-container">
                        <div class="input-content input-content-cpf">
                            <label for="cpf">CPF:</label>
                            <div class="input-alert">
                                <input v-model="form.cpfFormatado" @input="form.formatCpf" type="text" id="cpf"
                                    name="cpf" />
                                <span class="error-message" v-if="errors?.cpf">{{ errors?.cpf[0] }}</span>
                            </div>
                        </div>
                        <div class="input-content input-content-nome">
                            <label for="nome">Nome :</label>
                            <div class="input-alert input-alert-nome">
                                <input v-model="form.nome" type="text" id="nome" name="nome" />
                                <span class="error-message" v-if="errors?.nome">{{ errors?.nome[0] }}</span>
                            </div>
                        </div>
                        <div class="input-content input-content-nascimento">
                            <label for="data_nascimento">Data Nascimento:</label>
                            <div class="input-alert">
                                <input v-model="form.data_nascimento" type="date" id="data_nascimento"
                                    name="data_nascimento" />
                                <span class="error-message" v-if="errors?.data_nascimento">{{ errors?.data_nascimento[0]
                                }}</span>
                            </div>
                        </div>
                        <div class="input-content input-content-sexo">
                            <label for="">Sexo:</label>
                            <div class="input-alert input-alert-sexo">
                                <label>
                                    <input name="sexo" type="radio" value="M" v-model="form.sexo" />
                                    Masculino
                                </label>

                                <label>
                                    <input name="sexo" type="radio" value="F" v-model="form.sexo" />
                                    Feminino
                                </label>
                                <span class="error-message error-message-sexo" v-if="errors?.sexo">{{
                                    errors?.sexo[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="input-container">
                        <div class="input-content input-content-no-wrap">
                            <label for="    ">Endereço:</label>
                            <div class="input-alert input-alert-endereco">
                                <input v-model="form.endereco" type="text" id="endereco" name="endereco" />
                                <span class="error-message error-message-endereco" v-if="errors?.endereco">
                                    {{ errors?.endereco[0] }}
                                </span>
                            </div>
                        </div>

                        <div class="input-content input-content-no-wrap">
                            <label for="estado">Estado:</label>
                            <div class="input-alert input-alertes-estado">
                                <select v-model="selectedEstado" @change="carregarCidades" id="estado" name="estado">
                                    <option value="">Selecione</option>
                                    <option v-for="estado in estados" :value="estado.estado" :key="estado">{{ estado.estado
                                    }}</option>

                                </select>
                                <span class="error-message error-message-estado" v-if="errors?.estado">{{
                                    errors?.estado[0] }}</span>
                            </div>
                        </div>

                        <div class="input-content input-content-no-wrap">
                            <label for="cidade">Cidade:</label>
                            <div class="input-alert input-alertes-cidade">
                                <select v-model="form.cidade" id="cidade" name="cidade">
                                    <option value="">Selecione</option>
                                    <option v-for="cidade in cidades" :value="cidade.cidade" :key="cidade">{{ cidade.cidade
                                    }}</option>
                                </select>
                                <span class="error-message error-message-cidade" v-if="errors?.cidade">{{
                                    errors?.cidade[0] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="neighborhood" class="block text-gray-700 font-medium mb-2">
                        </label>
                    </div>
                    <div class="content-btns">
                        <button class="btn" type="submit">Pesquisar</button>
                        <button class="btn default" type="button" @click="limpar()">Limpar</button>
                    </div>
                </form>
            </div>
        </div>
</template>

<style>
#app {
    width: 100%;
    display: flex;
    justify-content: center;
}

.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}

.container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.content {
    border: solid 2px gray;
    border-radius: 10px;
    padding: 17px 13px;
    width: 936px;
}

.content img {
    height: 90px;
}

.cadastro {
    border: solid 2px gray;
    border-radius: 10px;
    padding: 14px 7px;

    min-width: calc(936px - 20px);
}

.input-container {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.input-content {
    flex-wrap: wrap;
    display: flex;
    flex-direction: row;
    justify-content: flex-start;
    align-items: center;
    gap: 10px;
}

.input-content-no-wrap {
    flex-wrap: nowrap;
}

.input-content-cpf {
    max-width: 163px;
}

.input-content-nome {
    max-width: 218px;
}

.content-btns {
    display: flex;
    flex-direction: row;
    justify-content: flex-end;
    align-items: center;
    gap: 10px;
}

label {
    font-size: 14px;
    font-weight: 700;
}

input {
    height: 32px;
    border-radius: 10px;
    border: solid 2px gray;
    font-size: 12px !important;
}

#cpf {
    max-width: 122px;
    border-radius: 10px;
    border: solid 2px gray;
}

#nome {
    max-width: 161px;
    border-radius: 10px;
    border: solid 2px gray;
}

#data_nascimento {
    width: 111px;
    font-size: 13px;
    padding: 6px;
    border-radius: 10px;
    border: solid 2px gray;
}

input[name="sexo"] {
    width: 15px;
    height: 15px;
}

#endereco {
    width: 280px;
    max-width: 284px;
    border-radius: 10px;
    border: solid 2px gray;
}

#estado,
#cidade {
    border-radius: 10px;
    height: 32px;
    width: 100%;
    border: solid 2px gray;
    min-width: 154px;
    padding: 0 10px;
}

.btn {
    background-color: #1565c0;
    color: white;
    border-radius: 4px;
    padding: 5px 24px;
    font-size: 14px;
}

.default {
    background-color: #e0e0e0;
    color: black;
}

.error-message {
    margin-top: 5px;
    color: red;
    font-size: 0.8rem;

    position: absolute;
    padding-top: 26px;
}

.input-alert {
    display: flex;
    flex-wrap: wrap;
    max-width: 122px;
}

.input-alert-nome {
    max-width: 161px;
}

.input-alert-sexo {
    max-width: 160px;
}

.input-alert-endereco {
    max-width: 284px;
}

.input-alertes-estado {
    max-width: 154px;
}

.input-alertes-cidade {
    max-width: 154px;
}

.error-message-sexo {
    padding-top: 20px;
}

.error-message-endereco {
    padding-top: 25px;
}

img{
    height: 100px;
}
</style>
