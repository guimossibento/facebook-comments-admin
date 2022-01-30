<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card" v-if="$gate.isAdmin()">
            <div class="card-header text-center">
              <div class="row-cols-2">
                <h3 class="card-title float-left">Listagem de Comentários</h3>
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

            <!-- <form @submit.prevent="createComment"> -->

            <form
              @submit.prevent="editmode ? updateComment() : createComment()"
            >
              <div class="modal-body">
                <div class="form-group">
                  <label>Name</label>
                  <textarea
                    v-model="form.text"
                    type="text"
                    name="text"
                    class="form-control"
                    :class="{ 'is-invalid': form.errors.has('text') }"
                  />
                  <has-error :form="form" field="text"></has-error>
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
// import DualListBox from "dual-listbox-vue";
// import "dual-listbox-vue/dist/dual-listbox.css";

export default {
  // components: {
  //   DualListBox,
  // },
  data() {
    return {
      editmode: false,
      comments: {},
      form: new Form({
        id: "",
        text: "",
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
    //     .put(`api/comments/${this.form.id}/niches`, niches)
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
        .get("api/comments?page=" + page)
        .then(({ data }) => (this.comments = data.data));

      this.$Progress.finish();
    },
    loadComments() {
      this.$Progress.start();
      if (this.$gate.isAdmin()) {
        axios
          .get("api/comments")
          .then(({ data }) => (this.comments = data.data));
      }
      this.$Progress.finish();
    },
    createComment() {
      if (this.$gate.isAdmin()) {
        this.source = [];
        this.destination = [];
        this.form
          .post("api/comments")
          .then((response) => {
            if (response.data.success) {
              Toast.fire({
                icon: "success",
                title: response.data.message,
              });

              this.form.id = response.data.data.id;
              this.form.text = response.data.data.text;
              //
              // axios
              //   .get(`api/comments/${response.data.data.id}?include=niches`)
              //   .then(({ data }) => {
              //     data = data.data["niches"].map(function (niche) {
              //       return niche.id;
              //     });
              //     for (let index = 0; index < data.length; index++) {
              //       this.destination.push(data[index]);
              //     }
              //   });
              //
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
      // console.log('Editing data');
      this.form
        .put("api/comments/" + this.form.id)
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
      // axios
      //   .get(`api/comments/${comment.id}?include=niches`)
      //   .then(({ data }) => {
      //     data = data.data["niches"].map(function (niche) {
      //       return niche.id;
      //     });
      //     for (let index = 0; index < data.length; index++) {
      //       this.destination.push(data[index]);
      //     }
      //   });
      //
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
            .delete("api/comments/" + id)
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
  },
  mounted() {
    console.log("Comment Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadComments();
    this.$Progress.finish();
  },
};
</script>
