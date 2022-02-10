<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card" v-if="$gate.isAdmin()">
            <div class="card-header text-center">
              <div class="row-cols-2">
                <h3 class="card-title float-left">Listagem de Comentários
                  {{ (nicheId) ? "do Nicho: " + this.niche.name : "" }}
                </h3>
              </div>
              <div class="row-cols-7">
                <button
                    type="button"
                    v-show="(typeof nicheId !=  'undefined')"
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
                    :data="comments"
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
                  <th>Comentário</th>
                  <th>Ação</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="comment in comments.data" :key="comment.id">
                  <td>{{ comment.id }}</td>
                  <td>{{ comment.text }}</td>
                  <td>
                    <a href="#" @click="editModal(comment)">
                      <i class="fa fa-edit blue"></i>
                    </a>

                    <a href="#" @click="deleteComment(comment.id)">
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
                    :data="comments"
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
          v-show="(typeof nicheId !=  'undefined')"
          aria-labelledby="addNew"
          aria-hidden="true"
      >
        <div class="modal-dialog modal-lg" role="document">

          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" v-show="!editmode">
                Criar Novo Comentário
              </h5>
              <h5 class="modal-title" v-show="editmode">
                Atualizar Comentário
              </h5>
              <button
                  type="button"
                  class="close"
                  data-dismiss="modal"
                  aria-label="Close"
              >
                <span aria-hidden="true">&times;</span>
              </button>
            </div>

            <form

                @submit.prevent="editmode ? updateComment() : createComment()"
            >
              <div class="modal-body">
                <div class="form-group">
                  <label> Comentário</label>
                  <textarea
                      v-model="form.text"
                      type="text"
                      name="text"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('text') }"
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
  name: 'comments',
  props: ['nicheId'],
  data() {
    return {
      editmode: false,
      comments: {},
      csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      niche: {},
      form: new Form({
        id: "",
        text: "",
      }),
      source: [],
      destination: [],
    };
  },
  methods: {
    getResults(page = 1) {
      this.$Progress.start();
      if (this.nicheId) {
        axios
            .get(window.location.origin + "/api/comments?filter[niche_id]=" + this.nicheId + "&page=" + page)
            .then(({data}) => (this.comments = data.data));

        this.getNicheData(this.nicheId)
        return;
      }

      axios
          .get(window.location.origin + "/api/comments?page=" + page)
          .then(({data}) => (this.comments = data.data));


      this.$Progress.finish();
    },
    loadComments() {
      this.$Progress.start();
      console.log(window)
      if (this.$gate.isAdmin()) {
        if (this.nicheId) {
          axios
              .get(window.location.origin + "/api/comments?filter[niche_id]=" + this.nicheId)
              .then(({data}) => (this.comments = data.data));

          this.getNicheData(this.nicheId)
          return;
        }
        axios
            .get(window.location.origin + "/api/comments")
            .then(({data}) => (this.comments = data.data));
      }
      this.$Progress.finish();
    },
    getNicheData(niche_id) {
      this.$Progress.start();
      axios
          .get(window.location.origin + "/api/niches?filter[id]=" + niche_id)
          .then(({data}) => (this.niche = data.data.data[0]));
      console.log(this.niche);
      this.$Progress.finish();
    },
    createComment() {
      if (this.$gate.isAdmin()) {
        this.source = [];
        this.destination = [];
        this.form.niche_id = this.nicheId;
        this.form
            .post(window.location.origin + "/api/comments")
            .then((response) => {
              if (response.data.success) {
                $("#addNew").modal("hide");
                Toast.fire({
                  icon: "success",
                  title: response.data.message,
                });
                this.$Progress.finish();
                this.loadComments();
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
    updateComment() {
      this.$Progress.start();
      this.form.niche_id = this.nicheId;
      this.form
          .put(window.location.origin + "/api/comments/" + this.form.id)
          .then((response) => {
            // success
            $("#addNew").modal("hide");
            Toast.fire({
              icon: "success",
              title: response.data.message,
            });
            this.$Progress.finish();
            //  Fire.$emit('AfterCreate');

            this.loadComments();
          })
          .catch(() => {
            this.$Progress.fail();
          });
    },
    editModal(comment) {
      this.editmode = true;
      this.form.reset();

      $("#addNew").modal("show");
      this.form.fill(comment);
    },
    newModal() {
      this.editmode = false;
      this.form.reset();
      $("#addNew").modal("show");
    },
    deleteComment(id) {
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
              .delete(window.location.origin + "/api/comments/" + id)
              .then(() => {
                Swal.fire("Apagado!", "Item foi apagado.", "success");
                // Fire.$emit('AfterCreate');
                this.loadComments();
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
  }
  ,
  mounted() {
    console.log("Comment Component mounted.");
  }
  ,
  created() {
    this.$Progress.start();
    this.loadComments();
    this.$Progress.finish();
  }
  ,
}
;
</script>
