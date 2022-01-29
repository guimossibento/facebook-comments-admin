<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header text-center">
              <div class="row-cols-2">
                <h3 class="card-title float-left">Log de Comentários</h3>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <div class="float-right">
                <pagination
                    :data="commnetLogs"
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
                  <th>Data</th>
                  <th>Login</th>
                  <th>Post</th>
                  <th>Comentário</th>
                  <th>Status</th>
                  <th>Ação</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="commnetLog in commnetLogs.data" :key="commnetLog.id">
                  <td>{{ commnetLog.id }}</td>
                  <td>{{ commnetLog.created_at | myDate}}</td>
                  <td>{{ commnetLog.facebook_account_login }}</td>
                  <td>{{ commnetLog.post_url }}</td>
                  <td>{{ commnetLog.comment }}</td>
                  <td>{{ commnetLog.status }}</td>
                  <td>
                    <a href="#" @click="deleteNiche(commnetLog.id)">
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
                    :data="commnetLogs"
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
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      commnetLogs: {},
      form: new Form({
        id: "",
        name: "",
      }),
    };
  },
  methods: {
    getResults(page = 1) {
      this.$Progress.start();

      axios
          .get("api/comment-logs?page=" + page)
          .then(({data}) => (this.commnetLogs = data.data));
      console.log(this.commnetLogs);
      this.$Progress.finish();
    },
    loadCommentLog() {
      this.$Progress.start();
      if (this.$gate.isAdmin()) {
        axios.get("api/comment-logs").then(({data}) => (this.commnetLogs = data.data));
      }
      console.log(this.commnetLogs);
      this.$Progress.finish();
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
          this.form.delete("api/comment-logs/" + id)
              .then(() => {
                Swal.fire("Apagado!", "Item foi apagado.", "success");
                this.loadCommentLog();
              })
              .catch((data) => {
                Swal.fire("Failed!", data.message, "warning");
              });
        }
      });
    },
  },
  mounted() {
    console.log("Comment Log Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadCommentLog();
    this.$Progress.finish();
  },
};
</script>
