<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card" v-if="$gate.isAdmin()">
            <div class="card-header text-center">
              <div class="row-cols-2">
                <h3 class="card-title float-left">
                  Listagem de Contas do Facebook
                </h3>
              </div>
              <div class="row-cols-7">
                <button
                    type="button"
                    class="btn btn-sm btn-primary float-right"
                    @click="newModal"
                >
                  <i class="fa fa-plus-square"></i>
                  Criar novo
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <div class="float-right">
                <pagination
                    :data="facebookAccounts"
                    :limit="-1"
                    @pagination-change-page="getResults"
                >
                  <span slot="prev-nav">Anterior</span>
                  <span slot="next-nav">Próxima</span>
                </pagination>
              </div>
              <table class="table table-hover">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Nome</th>
                  <th>Login</th>
                  <th>Senha</th>
                  <th>Gênero</th>
                  <th>Ação</th>
                </tr>
                </thead>
                <tbody>
                <tr
                    v-for="facebookAccount in facebookAccounts.data"
                    :key="facebookAccount.id"
                >
                  <td>{{ facebookAccount.id }}</td>
                  <td>{{ facebookAccount.name }}</td>
                  <td>{{ facebookAccount.login }}</td>
                  <td>{{ facebookAccount.password }}</td>
                  <td>{{ facebookAccount.gender | gender }}</td>
                  <td>
                    <a href="#" @click="editModal(facebookAccount)">
                      <i class="fa fa-edit blue"></i>
                    </a>

                    <a
                        href="#"
                        @click="deleteFacebookAccount(facebookAccount.id)"
                    >
                      <i class="fa fa-trash red"></i>
                    </a>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center">
              <div class="float-right">
                <pagination
                    :data="facebookAccounts"
                    :limit="-1"
                    @pagination-change-page="getResults"
                >
                  <span slot="prev-nav">Anterior</span>
                  <span slot="next-nav">Próxima</span>
                </pagination>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>

      <div v-if="!$gate.isAdmin()">
        <not-found></not-found>
      </div>

      <!-- Modal -->
      <div
          class="modal fade"
          id="addNew"
          tabindex="-1"
          role="dialog"
          aria-labelledby="addNew"
          aria-hidden="true"
      >
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode">Criar Novo Conta</h5>
              <h5 class="modal-title" v-show="editmode">Atualizar Conta</h5>
              <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <!-- <form @submit.prevent="createFacebookAccount"> -->

            <form
                @submit.prevent="
                editmode ? updateFacebookAccount() : createFacebookAccount()
              "
            >
              <div class="modal-body">
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>Nome</label>
                      <input
                          v-model="form.name"
                          type="text"
                          name="name"
                          required="required"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('name') }"
                      />
                      <has-error :form="form" field="name"></has-error>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label>Login</label>
                      <input
                          v-model="form.login"
                          type="text"
                          name="login"
                          required="required"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('login') }"
                      />
                      <has-error :form="form" field="login"></has-error>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>Senha</label>
                      <input
                          v-model="form.password"
                          type="text"
                          name="password"
                          required="required"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('password') }"
                      />
                      <has-error :form="form" field="password"></has-error>
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-group">
                      <label>Gênero</label>
                      <select
                          name="gender"
                          v-model="form.gender"
                          id="gender"
                          required="required"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('gender') }"
                      >
                        <option :selected="form.gender === 'M'" value="M">
                          Masculino
                        </option>
                        <option :selected="form.gender === 'F'" value="F">
                          Feminino
                        </option>
                      </select>
                      <has-error :form="form" field="gender"></has-error>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-group">
                      <label>Secret 2fa</label>
                      <input
                          v-model="form.secret_2fa"
                          type="text"
                          name="secret_2fa"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('secret_2fa') }"
                      />
                      <has-error :form="form" field="secret_2fa"></has-error>
                    </div>
                  </div>

                  <div class="col">
                    <div class="form-group">
                      <label>Ativa</label>
                      <select
                          name="gender"
                          v-model="form.active"
                          id="active"
                          required="required"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('active') }"
                      >
                        <option :selected="form.active == 1" value="1">
                          Ativa
                        </option>
                        <option :selected="form.active == 0" value="0">
                          Desativada
                        </option>
                      </select>
                      <has-error :form="form" field="gender"></has-error>
                    </div>
                  </div>
                </div>
                <!--                <div v-show="editmode" class="form-group text-center">-->
                <!--                  <el-transfer-->
                <!--                    filterable-->
                <!--                    :filter-method="filterMethod"-->
                <!--                    :titles="['Disponívels', 'Atribuidos']"-->
                <!--                    :button-texts="['Remover', 'Atribuir']"-->
                <!--                    filter-placeholder="Digite um nicho"-->
                <!--                    v-model="destination"-->
                <!--                    @change="onChangeList"-->
                <!--                    :data="source"-->
                <!--                  >-->
                <!--                  </el-transfer>-->
                <!--                </div>-->
              </div>
              <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                  Fechar
                </button>
                <button v-show="editmode" type="submit" class="btn btn-success">
                  Atualizar
                </button>
                <button
                    v-show="!editmode"
                    type="submit"
                    class="btn btn-primary"
                >
                  Criar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import DualListBox from "dual-listbox-vue";
import "dual-listbox-vue/dist/dual-listbox.css";

export default {
  components: {
    DualListBox,
  },
  data() {
    return {
      editmode: false,
      facebookAccounts: {},
      form: new Form({
        id: "",
        name: "",
        login: "",
        password: "",
        gender: "",
        active: "",
        secret_2fa: "",
      }),
      source: [],
      destination: [],
    };
  },
  methods: {
    // onChangeList: function (value, direction, movedKeys) {
    //   let niches = {};
    //   niches.data = [];
    //
    //   value.forEach((element) => {
    //     niches.data.push({ id: element });
    //   });
    //
    //   axios
    //     .put(`api/facebook-accounts/${this.form.id}/niches`, niches)
    //     .then((response) => {})
    //     .catch((error) => {
    //       Toast.fire({
    //         icon: "error",
    //         title: "Some error occured! Please try again",
    //       });
    //     });
    // },
    getResults(page = 1) {
      this.$Progress.start();

      axios
          .get("api/facebook-accounts?page=" + page)
          .then(({data}) => (this.facebookAccounts = data.data));

      this.$Progress.finish();
    },
    loadFacebookAccounts() {
      this.$Progress.start();
      if (this.$gate.isAdmin()) {
        axios
            .get("api/facebook-accounts")
            .then(({data}) => (this.facebookAccounts = data.data));
      }
      this.$Progress.finish();
    },
    createFacebookAccount() {
      if (this.$gate.isAdmin()) {
        this.source = [];
        this.destination = [];

        this.form
            .post("api/facebook-accounts")
            .then((response) => {
              if (response.data.success) {
                Toast.fire({
                  icon: "success",
                  title: response.data.message,
                });

                // axios
                //   .get(
                //     `api/facebook-accounts/${response.data.data.id}?include=niches`
                //   )
                //   .then(({ data }) => {
                //     data = data.data["niches"].map(function (niche) {
                //       return niche.id;
                //     });
                //     for (let index = 0; index < data.length; index++) {
                //       this.destination.push(data[index]);
                //     }
                //   });

                // axios.get(`api/niches/list`).then(({ data }) => {
                //   data = data.map(function (niche) {
                //     return {
                //       key: niche.id,
                //       label: niche.name,
                //     };
                //   });
                //
                //   this.source = data;
                // });

                this.editmode = true;
                this.$Progress.finish();
                this.loadFacebookAccounts();
              } else {
                Toast.fire({
                  icon: "error",
                  title: "Some error occured! Please try again",
                });

                this.$Progress.failed();
              }
            })
            .catch((error) => {
              console.log(error);
              Toast.fire({
                icon: "error",
                title: "Some error occured! Please try again",
              });
            });
      }
    },
    updateFacebookAccount() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
          .put("api/facebook-accounts/" + this.form.id)
          .then((response) => {
            // success
            $("#addNew").modal("hide");
            Toast.fire({
              icon: "success",
              title: response.data.message,
            });
            this.$Progress.finish();
            //  Fire.$emit('AfterCreate');

            this.loadFacebookAccounts();
          })
          .catch(() => {
            this.$Progress.fail();
          });
    },
    editModal(facebookAccount) {
      this.editmode = true;
      this.form.reset();
      console.log(facebookAccount);
      // axios
      //   .get(`api/facebook-accounts/${facebookAccount.id}?include=niches`)
      //   .then(({ data }) => {
      //     data = data.data["niches"].map(function (niche) {
      //       return niche.id;
      //     });
      //     for (let index = 0; index < data.length; index++) {
      //       this.destination.push(data[index]);
      //     }
      //   });

      // axios.get(`api/niches/list`).then(({ data }) => {
      //   data = data.map(function (niche) {
      //     return {
      //       key: niche.id,
      //       label: niche.name,
      //     };
      //   });
      //
      //   this.source = data;
      // });

      $("#addNew").modal("show");
      this.form.fill(facebookAccount);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteFacebookAccount(id) {
      Swal.fire({
        title: "Tem certeza?",
        text: "Não será possível reverter!",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sim, apague isso!",
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.form
              .delete("api/facebook-accounts/" + id)
              .then(() => {
                Swal.fire("Apagado!", "Item foi apagado.", "success");
                // Fire.$emit('AfterCreate');
                this.loadFacebookAccounts();
              })
              .catch((data) => {
                Swal.fire("Failed!", data.message, "warning");
              });
        }
      });
    },
    filterMethod(query, item) {
      return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
    },
  },
  mounted() {
    console.log("FacebookAccount Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadFacebookAccounts();
    this.$Progress.finish();
  },
};
</script>
