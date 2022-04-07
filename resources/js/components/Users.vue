<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card" v-if="$gate.isAdmin()">
            <div class="card-header text-center">
              <div class="row-cols-2">
                <h3 class="card-title float-left">Listagem de Usuários</h3>
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
              <table class="table table-hover">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Perfil</th>
                  <th>Nome</th>
                  <th>Email</th>
                  <th>Ação</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users.data" :key="user.id">
                  <td>{{ user.id }}</td>
                  <td class="text-capitalize">{{ user.type }}</td>
                  <td class="text-capitalize">{{ user.name }}</td>
                  <td>{{ user.email }}</td>

                  <td>
                    <a href="#" @click="editModal(user)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteUser(user.id)">
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
                    :data="users"
                    :limit="-1"
                    @pagination-change-page="getResults"
                ></pagination>
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
          class="modal hide fade in"
          id="addNew"
          tabindex="-1"
          role="dialog"
          aria-labelledby="addNew"
          aria-hidden="true"
      >
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode">Criar novo usuário</h5>
              <h5 class="modal-title" v-show="editmode">Atualizar usuário</h5>
              <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <!-- <form @submit.prevent="createUser"> -->

            <form @submit.prevent="editmode ? updateUser() : createUser()">
              <div class="modal-body">
                <div class="form-group">
                  <label>Nome</label>
                  <input
                      v-model="form.name"
                      type="text"
                      name="name"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input
                      v-model="form.email"
                      type="text"
                      name="email"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('email') }"
                  />
                  <has-error :form="form" field="email"></has-error>
                </div>

                <div v-show="!editmode" class="form-group">
                  <label>Senha</label>
                  <input
                      v-model="form.password"
                      type="password"
                      name="password"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('password') }"
                      autocomplete="false"
                  />
                  <has-error :form="form" field="password"></has-error>
                </div>

                <div class="form-group">
                  <label>Perfl</label>
                  <select
                      name="type"
                      v-model="form.type"
                      id="type"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('type') }"
                  >
                    <option disabled value="">Selecionar perfil</option>
                    <option value="admin">Admin</option>
                    <option value="user">Standard User</option>
                  </select>
                  <has-error :form="form" field="type"></has-error>
                </div>
                <div  class="form-group">
                  <label>Senha</label>
                  <input
                      v-model="form.password"
                      type="password"
                      name="password"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('password') }"
                      autocomplete="false"
                  />
                  <has-error :form="form" field="password"></has-error>
                </div>
              </div>
              <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal"
                >
                  Close
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
export default {
  data() {
    return {
      editmode: false,
      users: {},
      user_session: {},
      form: new Form({
        id: "",
        type: "",
        name: "",
        email: "",
        password: "",
        email_verified_at: "",
      }),
    };
  },
  methods: {
    getResults(page = 1) {
      this.$Progress.start();
      axios
          .get("api/user?page=" + page)
          .then(({data}) => (this.users = data.data));

      this.$Progress.finish();
    },
    updateUser() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
          .put("api/user/" + this.form.id)
          .then((response) => {
            // success
            $("#addNew").modal("hide");
            Toast.fire({
              icon: "success",
              title: response.data.message,
            });
            this.$Progress.finish();
            //  Fire.$emit('AfterCreate');

            this.loadUsers();
          })
          .catch(() => {
            this.$Progress.fail();
          });
    },
    editModal(user) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(user);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteUser(id) {
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
          axios
              .delete("api/user/" + id)
              .then(() => {
                Swal.fire("Apagado!", "Item foi apagado.", "success");
                // Fire.$emit('AfterCreate');
                this.loadUsers();
              })
              .catch((data) => {
                Swal.fire("Failed!", data.message, "warning");
              });
        }
      });
    },
    loadUsers() {
      this.$Progress.start();

      axios
          .get("api/me")
          .then((response) => {
            this.user_session = response.data
          })
          .catch((err) => {
            console.log(err)
          });

      if (this.$gate.isAdmin()) {
        axios.get("api/user").then(({data}) => (this.users = data.data));
      }

      this.$Progress.finish();
    },

    createUser() {
      this.form
          .post("api/user")
          .then((response) => {
            $("#addNew").modal("hide");

            Toast.fire({
              icon: "success",
              title: response.data.message,
            });

            this.$Progress.finish();
            this.loadUsers();
          })
          .catch(() => {
            Toast.fire({
              icon: "error",
              title: "Some error occured! Please try again",
            });
          });
    },
  },
  mounted() {
    this.$gtag.pageview({page_title: 'Users'})
    console.log("User Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadUsers();
    this.$Progress.finish();
  },
};
</script>
