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
              <div class="row-cols-7">
                <button
                    type="button"
                    class="btn btn-sm btn-danger float-right"
                    @click="deleteAll()"
                >
                  <i class="fa fa-trash"></i>
                  Limpar Histórico
                </button>
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
                  <th>Debug</th>
                  <th>Ação</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="commnetLog in commnetLogs.data" :key="commnetLog.id">
                  <td>{{ commnetLog.id }}</td>
                  <td>{{ commnetLog.created_at | myDate }}</td>
                  <td>{{ commnetLog.facebook_account_login }}</td>
                  <td>{{ commnetLog.post_url }}</td>
                  <td>{{ commnetLog.comment }}</td>
                  <td>{{ commnetLog.status }}
                  </td>
                  <td>
                    <div v-if="commnetLog.debug_json != null" class="text-center">
                      <a href="#" @click="debugError(commnetLog.debug_json.error_screenshots)">
                        <i class="fa fa-file-image blue"></i>
                      </a>
                    </div>
                  </td>
                  <td>
                    <a href="#" @click="deleteCommentLog(commnetLog.id)">
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
      <!-- Modal -->
      <div
          class="modal fade"
          id="carrousel"
          tabindex="-1"
          role="dialog"
          aria-labelledby="carrousel"
          aria-hidden="true"
      >
        <div class="modal-dialog modal-lg" role="log">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">
                Debug Imagens
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

            <div>
              <template>
                <el-carousel
                    indicator-position="outside"
                             :key="screenshots[0]">
                  <el-carousel-item align="center" v-for="screenshot in screenshots" :key="screenshot">
                    <img :src="screenshot" style="max-width: 100%; object-fit: contain;">
                  </el-carousel-item>
                </el-carousel>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Echo from "laravel-echo"

window.Pusher = require('pusher-js');

export default {
  data() {
    return {
      commnetLogs: {},
      screenshots: {},
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
      // console.log(this.commnetLogs);
      this.$Progress.finish();
    },
    loadCommentLog() {
      this.$Progress.start();
      if (this.$gate.isAdmin()) {
        axios.get("api/comment-logs").then(({data}) => (this.commnetLogs = data.data));
      }
      // console.log(this.commnetLogs);
      this.$Progress.finish();
    },
    deleteCommentLog(id) {
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
    deleteAll() {
      Swal.fire({
        title: "Tem certeza?",
        text: "Não será possível reverter!",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sim, apague tudo!",
      }).then((result) => {
        // Send request to the server
        if (result.value) {
          this.form.delete("api/comment-logs/delete/all")
              .then(() => {
                Swal.fire("Apagado!", "Histórico apagado.", "success");
                this.loadCommentLog();
              })
              .catch((data) => {
                Swal.fire("Failed!", data.message, "warning");
              });
        }
      });
    },

    debugError(screnshots) {
      this.screenshots = screnshots;
      $("#carrousel").modal("show");
    },
  },
  mounted() {
    console.log("Comment Log Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadCommentLog();

    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: 'websocketkey',
      wsPath: '/broadcast/comment-log',
      wsHost: 'localhost:6001',
      forceTLS: false,
      disableStats: true,
    });

    window.Echo.channel('comment-log')
        .listen('CommentLogEvent', (e) => {
          console.log(e);
          this.commnetLogs.data.unshift(e.commentLog);
        });

    this.$Progress.finish();
  },
};
</script>
