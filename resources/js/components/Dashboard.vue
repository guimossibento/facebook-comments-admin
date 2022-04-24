<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- Left col -->
        <div class="col-md-12">
          <!-- MAP & BOX PANE -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Executar Comentários</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <form @submit.prevent="executeComment()">
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="url">Link do post</label>
                    <input
                        required
                        v-model="form.url"
                        type="url"
                        class="form-control"
                        id="url"
                        placeholder=""
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="gender">Gênero</label>
                    <select
                        name="gender"
                        v-model="form.gender"
                        id="gender"
                        class="form-control"
                    >
                      <option value="M">Masculino</option>
                      <option value="F">Feminino</option>
                      <option value="A">Aleatório</option>
                    </select>
                  </div>
                </div>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="comment_amount"
                    >Quantidade de comentários</label
                    >
                    <input
                        required
                        v-model="form.comment_amount"
                        type="text"
                        class="form-control commentAmount"
                        id="comment_amount"
                        name="comment_amount"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="niches">Nicho</label>
                    <select
                        required
                        class="form-control"
                        id="niches"
                        name="niches"
                        v-model="form.niche"
                    >
                      <option disabled>Selecione um nicho</option>
                      <option
                          v-for="niche in niches"
                          v-bind:key="niche.id"
                          v-bind:value="niche.id"
                      >
                        {{ niche.name }}
                      </option>
                    </select>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary">Executar</button>
              </form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-header text-center">
              <div class="row-cols-2">
                <h3 class="card-title float-left">Log de Solicitações</h3>
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
                    :data="commentRequestLogs"
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
                  <th>Nicho</th>
                  <th>Post Url</th>
                  <th>Sucesso</th>
                  <th>Erro</th>
                  <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="commentRequest in commentRequestLogs.data" :key="commentRequest.id">
                  <td>{{ commentRequest.id }}</td>
                  <td>{{ commentRequest.created_at | myDate }}</td>
                  <td v-if="commentRequest.niche !== null">{{ commentRequest.niche.name }}</td>
                  <td v-if="commentRequest.niche === null">Nicho Apagado</td>
                  <td>{{ commentRequest.post_url }}</td>
                  <td>
                    {{ getTotalCommentLogsSuccess(commentRequest.comment_logs) }}
                  </td>
                  <td>
                    {{ getTotalCommentLogsError(commentRequest.comment_logs) }}
                  </td>
                  <td>{{ commentRequest.total_request }}</td>
                </tr>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center">
              <div class="float-right">
                <pagination
                    :data="commentRequestLogs"
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
    <!--/. container-fluid -->
  </section>
</template>

<script>
import Echo from "laravel-echo";

export default {
  data() {
    return {
      niches: {},
      commentRequestLogs: {},
      form: {
        url: "",
        comment_amount: "",
        niche: "",
        gender: "",
      },
    };
  },
  methods: {
    loadNiches() {
      this.$Progress.start();
      axios
          .get("niches/list")
          .then(({data}) => (this.niches = data))
          .catch((error) => {
            console.log(error);
          });

      this.$Progress.finish();
    },
    getResults(page = 1) {
      this.$Progress.start();
      axios
          .get("api/comment-request-logs?include=commentLogs,niche&filter[user]=1&page=" + page)
          .then(({data}) => (this.commentRequestLogs = data.data))
          .catch((error) => {
            console.log(error);
          });

      this.$Progress.finish();
    },
    loadCommentRequestLogs() {
      this.$Progress.start();
      axios
          .get("api/comment-request-logs?include=commentLogs,niche&filter[user]=1")
          .then(({data}) => {
            this.commentRequestLogs = data.data
          })
          .catch((error) => {
            console.log(error);
          });

      this.$Progress.finish();
    },
    executeComment() {
      this.$Progress.start();

      // if(this.form.comment_amount > 25){
      //   Toast.fire({
      //     icon: "error",
      //     title: "Quantidade de comentários não pode ser maior que 25.",
      //   });
      //   return;
      // }

      axios
          .put("api/dashboard/execute-comments", this.form, {
            headers: {
              "Content-Type": "application/json",
              Accept: "application/json",
            },
          })
          .then((response) => {
            if (response.status === 200) {
              Toast.fire({
                icon: "success",
                title: "Aguarde, processando comentários!",
              });

              this.resetFormFields()

              this.$gtag.purchase({
                "transaction_id": Math.random().toString(),
                "affiliation": "Execute Comment",
                "value": 2.5
              })
            }
          })
          .catch((error) => {
            Toast.fire({
              icon: "error",
              title: error.response.data.message,
            });
            this.$Progress.fail();
          });
      this.$Progress.finish();
    },
    getTotalCommentLogsSuccess(commentLogs) {
      return commentLogs.filter(
          function (commentLog) {
            return commentLog.status === 'Sucesso'
          }
      ).length
    },
    getTotalCommentLogsError(commentLogs) {
      return commentLogs.filter(
          function (commentLog) {
            return commentLog.status === 'Erro'
          }
      ).length
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
          axios.delete("api/comment-request-logs/delete/all")
              .then(() => {
                Swal.fire("Apagado!", "Histórico apagado.", "success");
                this.loadCommentRequestLogs();
              })
              .catch((data) => {
                Swal.fire("Failed!", data.message, "warning");
              });
        }
      });
    },
    resetFormFields() {
      this.form = { ...this.formCopy};
    },
  },
  mounted() {
    this.formCopy = { ...this.form};
    this.$gtag.pageview({page_title: 'Dashboard'})
    console.log("Dashboard mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadNiches();
    this.loadCommentRequestLogs();
    $(document).ready(function () {
      $(".select2").select2();
    });

    window.Echo = new Echo({
      broadcaster: 'pusher',
      key: 'websocketkey',
      wsHost: window.location.hostname,
      wsPort: 6001,
      wssPort: 6001,
      disableStats: true,
      forceTLS: true,
      enabledTransports: ['ws', 'wss']
    });


    window.Echo.channel("comment-log")
        .listen("CommentRequestLogEvent", e => {
          this.loadCommentRequestLogs();
        });

    window.Echo.channel("comment-log")
        .listen("CommentLogEvent", e => {
          this.loadCommentRequestLogs();
        });

    this.$Progress.finish();
  },
};
</script>
