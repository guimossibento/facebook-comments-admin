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
                      class="form-control positiveNumber"
                      id="comment_amount"
                      value="1"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="niches">Nicho</label>
                    <select
                      required
                      class="form-control"
                      id="niches"
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
    </div>
    <!--/. container-fluid -->
  </section>
</template>

<script>
export default {
  data() {
    return {
      niches: {},
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
        .then(({ data }) => (this.niches = data))
        .catch((error) => {
          console.log(error);
        });

      this.$Progress.finish();
    },
    executeComment() {
      this.$Progress.start();
      axios
        .put("api/dashboard/execute-comments", this.form, {
          headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
          },
        })
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
          this.$Progress.fail();
        });
      this.$Progress.finish();
    },
  },
  mounted() {
    console.log("Dashboard mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadNiches();
    $(document).ready(function () {
      $(".select2").select2();
    });
    this.$Progress.finish();
  },
};
</script>
