<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card" v-if="$gate.isAdmin()">
            <div class="card-header text-center">
              <div class="row-cols-2">
                <h3 class="card-title float-left">Listagem de Nichos</h3>
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
                    :data="niches"
                    :limit="-1"
                    @pagination-change-page="getResults"
                >
                  <span slot="prev-nav">Anterior</span>
                  <span slot="next-nav">Próxima</span>
                </pagination>
              </div>
              <table class="table table-hover p-0">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Nicho</th>
                  <th class="text-center">Comentários</th>
                  <th>Ação</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="niche in niches.data" :key="niche.id">
                  <td>{{ niche.id }}</td>
                  <td>{{ niche.name }}</td>
                  <td class="text-center">
                    <span> {{niche.comments.length}}</span>
                    <a href="#" @click="loadComments(niche.id)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                  </td>
                  <td>
                    <a href="#" @click="editModal(niche)">
                      <i class="fa fa-edit blue"></i>
                    </a>

                    <a href="#" @click="deleteNiche(niche.id)">
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
                    :data="niches"
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
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode">Criar Novo Nicho</h5>
              <h5 class="modal-title" v-show="editmode">Atualizar Nicho</h5>
              <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <!-- <form @submit.prevent="createNiche"> -->

            <form @submit.prevent="editmode ? updateNiche() : createNiche()">
              <div class="modal-body">
                <div class="form-group">
                  <label>Name</label>
                  <input
                      v-model="form.name"
                      type="text"
                      name="name"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('name') }"
                  />
                  <has-error :form="form" field="text"></has-error>
                </div>
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

export default {
  data() {
    return {
      editmode: false,
      niches: {},
      form: new Form({
        id: "",
        name: "",
      }),
    };
  },
  methods: {
    onChangeList: function ({source, destination}) {
      niches["data"] = destination;
      axios
          .put(`api/comments/${this.form.id}/niches`, niches["data"])
          .then((response) => {
            Toast.fire({
              icon: "success",
              title: response.data.message,
            });

            element.innerHTML = response.data.id;
          })
          .catch((error) => {
            element.parentElement.innerHTML = `Error: ${error.message}`;
            console.error("There was an error!", error);

            Toast.fire({
              icon: "error",
              title: "Some error occured! Please try again",
            });
          });
      this.source = source;
      this.destination = destination;
    },
    getResults(page = 1) {
      this.$Progress.start();

      axios
          .get("api/niches?page=" + page)
          .then(({data}) => (this.niches = data.data));

      this.$Progress.finish();
    },
    loadNiches() {
      this.$Progress.start();
      if (this.$gate.isAdmin()) {
        axios.get("api/niches?include=comments").then(({data}) => (this.niches = data.data));
      }
      this.$Progress.finish();
    },
    loadComments(niche_id) {
      this.$Progress.start();
      this.$router.push({
        path: '/comments/' + niche_id
      });
      this.$Progress.finish();
    },
    createNiche() {
      if (this.$gate.isAdmin()) {
        this.form
            .post("api/niches")
            .then((data) => {
              if (data.data.success) {
                $("#addNew").modal("hide");

                Toast.fire({
                  icon: "success",
                  title: data.data.message,
                });
                this.$Progress.finish();
                this.loadNiches();
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
    updateNiche() {
      this.$Progress.start();
      // console.log('Editing data');
      this.form
          .put("api/niches/" + this.form.id)
          .then((response) => {
            // success
            $("#addNew").modal("hide");
            Toast.fire({
              icon: "success",
              title: response.data.message,
            });
            this.$Progress.finish();
            //  Fire.$emit('AfterCreate');

            this.loadNiches();
          })
          .catch(() => {
            this.$Progress.fail();
          });
    },
    editModal(niche) {
      this.editmode = true;
      this.form.reset();
      $("#addNew").modal("show");
      this.form.fill(niche);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteNiche(id) {
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
              .delete("api/niches/" + id)
              .then(() => {
                Swal.fire("Apagado!", "Item foi apagado.", "success");
                this.loadNiches();
              })
              .catch((data) => {
                Swal.fire("Failed!", data.message, "warning");
              });
        }
      });
    },
  },
  mounted() {
    this.$gtag.pageview({page_title: 'Niches'})
    console.log("Niche Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadNiches();
    this.$Progress.finish();
  },
};
</script>
