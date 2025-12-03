<template>
  <div class="v-card w-100 h-100 justify-start align-start d-flex flex-column">
    <PageLoadingDialog v-model="pageLoading" @input="$router.back()" />
    <CardActionBar>
      <v-btn
        color="primary"
        class="me-3 mb-3"
        :loading="getExcelLoading"
        @click="onGetExcel()"
      >
        <v-icon class="white--text text-h5">mdi-file-chart</v-icon>
        <span>Выгрузить</span>
      </v-btn>
    </CardActionBar>

    <v-form class="w-100 pa-4 form" disabled>
      <v-row>
        <v-col cols="12" md="3">
          <span class="text-h5 grey--text text--darken-1"> Цех </span>
          <v-slide-group
            v-model="main.workshop"
            show-arrows
            center-active
            mandatory
          >
            <v-slide-item
              v-for="(ws, index) of workshops"
              :key="index"
              v-slot="{ active, toggle }"
              :value="ws.workshop"
            >
              <!-- если у цеха один id_v01  -->
              <v-btn
                v-if="ws.variants.length === 1"
                class="px-1 mx-1"
                style="min-width: 35px !important"
                :class="{
                  'primary white--text active': ws.workshop === main.workshop,
                }"
                :input-value="active"
                active-class="primary white--text"
                depressed
                fab
                rounded
                small
                @click="goToVariant(ws.variants[0], toggle)"
              >
                {{ ws.workshop }}
              </v-btn>
              <!--  если id_v01 у цеха несколько, показываем меню -->
              <v-menu v-else offset-y>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    depressed
                    fab
                    rounded
                    small
                    :input-value="active"
                    v-bind="attrs"
                    v-on="on"
                    :class="{
                      'primary white--text active':
                        ws.workshop === main.workshop,
                    }"
                  >
                    {{ ws.workshop }}
                  </v-btn>
                </template>

                <v-list dense>
                  <v-list-item
                    v-for="variant in ws.variants"
                    :key="variant.id_v01"
                    @click="goToVariant(variant, toggle)"
                  >
                    <v-list-item-content>
                      {{ ws.workshop }} Цех
                      <v-divider class="my-1"> </v-divider>
                    </v-list-item-content>
                  </v-list-item>
                </v-list>
              </v-menu>
            </v-slide-item>
          </v-slide-group>
          <v-text-field
            v-model="main.party"
            label="Партия"
            dense
            type="number"
          />
          <v-text-field
            v-model="main.number_technological_notification"
            label="Технол. извещение"
            dense
          />
          <v-text-field
            v-model="main.cipher_main_TD"
            label="№ тех. док / ВТД"
            dense
            v-mask="'#####.##### / #'"
          />
        </v-col>
        <v-col cols="12" md="3">
          <v-text-field
            v-model="main.note"
            :counter="30"
            label="Примечание"
            dense
            disabled
          />
          <v-text-field
            v-model="main.designation"
            label="Индекс"
            dense
            class="mt-0"
            disabled
          />
          <v-text-field v-model="main.code_detail" label="Код" dense disabled />
        </v-col>
        <v-col cols="12" md="1">
          <v-text-field v-model="sheet" label="Лист" dense disabled />
          <v-text-field v-model="sheets" label="Листов" dense disabled />
        </v-col>
      </v-row>
      <!-- Расчетная  -->
      <v-row dense>
        <v-col cols="12" md="3">
          <v-text-field
            label="Трудоёмкость на ДСЕ / с Кзо"
            dense
            v-mask="'#####.##### / #'"
          />
        </v-col>
        <v-col cols="12" md="3">
          <v-text-field
            label="Расценка (руб)/с Кзо"
            dense
            v-mask="'#####.##### / #'"
          />
        </v-col>
        <v-col cols="12" md="3">
          <v-text-field
            label="Суммарная трудоёмкость по цехам / с Кзо"
            dense
            v-mask="'#####.##### / #'"
          />
        </v-col>
        <v-col cols="12" md="3">
          <v-text-field
            label="Расценка / с Кзо"
            dense
            v-mask="'#####.##### / #'"
          />
        </v-col>
      </v-row>
      <!-- конец расчётной -->
    </v-form>
    <OperationForm />
    <CardStatusSelector v-model="main.id_status" disabled />
  </div>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { saveDownloadedFile } from "@/helpers/browser";
import { countCardPages } from "@/helpers/operation";
import PageLoadingDialog from "@/components/PageLoadingDialog";
import CardActionBar from "@/components/CardActionBar";
import CardStatusSelector from "@/components/CardStatusSelector";
import OperationForm from "./OperationForm";

export default {
  name: "ViewCardPage",
  components: {
    OperationForm,
    PageLoadingDialog,
    CardStatusSelector,
    CardActionBar,
  },
  data: () => ({
    cardID: null,
  }),
  computed: {
    ...mapState("card", {
      main: (state) => state.card,
      operations: (state) => state.operations,
      workshops: (state) => state.workshops,
      pageLoading: (state) => state.isLoading,
      getExcelLoading: (state) => state.status.excel.loading,
      // error: state => state.error,
    }),
    sheet() {
      return 1;
    },
    sheets() {
      return countCardPages(this.operations);
    },
  },
  watch: {
    async "$route.params.id"() {
      await this.fetchCardById(this.cardID);
    },
  },
  async mounted() {
    await this.fetchCardById(this.cardID);
  },
  created() {
    this.cardID = this.$route.params.id;
  },
  methods: {
    ...mapActions("card", ["fetchCardById", "getExcelFromCard"]),
    primaryVariant(ws) {
      return ws.variants[0];
    },
    goToVariant(variant, toggle) {
      if (typeof toggle === "function") {
        toggle();
      }
      const newCardId = variant.id_v01;
      this.cardID = newCardId;
      const targetPath = `/cards/view/${newCardId}`;
      if (this.$route.path !== targetPath) {
        try {
          this.$router.replace({ path: targetPath });
        } catch (err) {
          if (err && err.name === "NavigationDuplicated") {
            return;
          }
          throw err;
        }
      }
    },
    async onGetExcel() {
      const data = await this.getExcelFromCard(this.cardID);
      saveDownloadedFile(data, `Карта норм времени.xlsx`);
    },
  },
};
</script>
