<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card" v-if="$gate.isAdmin()">
            <div class="card-header text-center">
              <div class="row-cols-1">
                <h3 class="card-title float-left">Vínculo Nicho X Conta do Facebook</h3>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <form
                  @submit.prevent="update()"
              >
                <div class="modal-body align-items-center">
                  <div class="row d-flex justify-content-center text-center">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="niches">Nicho</label>
                        <select
                            class="form-control"
                            id="niches"
                            @change="selectNiche"
                            v-model="form.niche"
                        >
                          <option disabled>Selecione um nicho</option>
                          <option
                              v-for="niche in niches.data"
                              v-bind:key="niche.id"
                              v-bind:value="niche.id"
                          >
                            {{ niche.name }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group text-center" v-show="editmode">
                    <el-transfer
                        filterable
                        :filter-method="filterMethod"
                        :titles="['Disponívels', 'Atribuidos']"
                        :button-texts="['Remover', 'Atribuir']"
                        filter-placeholder="Digite um nicho"
                        v-model="destination"
                        @change="onChangeList"
                        :data="source"
                    >
                    </el-transfer>
                  </div>
                </div>
                <div class="modal-footer">
                </div>
              </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer text-center">

            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>

      <div v-if="!$gate.isAdmin()">
        <not-found></not-found>
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
      niches: {},
      form: new Form({
        id: "",
        name: "",
        login: "",
        password: "",
        gender: "",
        secret_2fa: "",
      }),
      source: [],
      destination: [],
    };
  },
  methods: {
    onChangeList: function (value, direction, movedKeys) {
      let facebookAccounts = {};
      facebookAccounts.data = [];

      value.forEach((element) => {
        facebookAccounts.data.push({id: element});
      });

      axios
          .put(`api/niches/${this.form.niche}/facebook-accounts`, facebookAccounts)
          .then((response) => {
          })
          .catch((error) => {
            Toast.fire({
              icon: "error",
              title: "Some error occured! Please try again",
            });
          });
    },
    getResults(page = 1) {
      this.$Progress.start();

      axios
          .get("api/facebook-accounts?page=" + page)
          .then(({data}) => (this.facebookAccounts = data.data));

      this.$Progress.finish();
    },
    loadFacebookAccounts(niche) {
      this.$Progress.start();
      axios
          .get(`api/niches/${niche}?include=facebookAccounts`)
          .then(({data}) => {
            data = data.data["facebook_accounts"].map(function (facebookAccount) {
              return facebookAccount.id;
            });
            for (let index = 0; index < data.length; index++) {
              this.destination.push(data[index]);
            }
          });

      axios.get(`facebook-accounts/list`).then(({data}) => {
        data = data.map(function (facebookAccount) {
          return {
            key: facebookAccount.id,
            label: facebookAccount.name,
          };
        });

        this.source = data;
      });


      if (this.$gate.isAdmin()) {
        axios
            .get("api/facebook-accounts")
            .then(({data}) => (this.facebookAccounts = data.data));
      }
      this.$Progress.finish();
    },
    loadNiches() {
      this.$Progress.start();
      if (this.$gate.isAdmin()) {
        axios.get("api/niches").then(({data}) => (this.niches = data.data));
      }
      this.$Progress.finish();
    },
    filterMethod(query, item) {
      return item.label.toLowerCase().indexOf(query.toLowerCase()) > -1;
    },
    selectNiche() {
      let niche = this.form.niche;
      this.destination = [];
      this.loadFacebookAccounts(niche);
      this.editmode = true;
    }
  },
  mounted() {
    this.$gtag.pageview({page_title: 'Niche Facebook-Account'})
    console.log("FacebookAccount Component mounted.");
  },
  created() {
    this.$Progress.start();
    this.loadNiches();
    this.$Progress.finish();
  },
};
</script>
