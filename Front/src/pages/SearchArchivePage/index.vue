<template>
  <v-card class="w-100 h-100 justify-start align-start d-flex flex-column">
    <v-card-title class="d-flex w-100 align-end border-0">
      <div class="d-flex flex-row w-100 align-center">
        <v-text-field
          v-model="query"
          label="Введите строку для поиска"
          single-line
          hide-details
          class="my-3 lh-0 w-100 mt-0"
          style="max-width: 500px"
          :disabled="advancedSearch"
          v-on:keyup.enter="searchCardsCommon()"
        />
        <v-btn
          color="primary"
          class="ms-3 mt-0"
          :disabled="advancedSearch"
          @click="searchCardsCommon()"
        >
          <v-icon left>mdi-magnify</v-icon>
          <span class="d-none d-lg-block">
            Поиск
          </span>
        </v-btn>
      </div>

      <v-expansion-panels>
        <v-expansion-panel focusable @change="advancedSearch = !advancedSearch">
          <v-expansion-panel-header>
            Расширенный поиск
          </v-expansion-panel-header>
          <v-expansion-panel-content>
            <v-form class="w-100 pa-4 form">
              <v-row>
                <v-col cols="12" md="3" lg="2">
                  <v-text-field
                    v-model="search.designation"
                    label="Индекс"
                    dense
                    v-on:keyup.enter="searchCardsAdvanced()"
                  />
                  <v-text-field
                    v-model="search.code_detail_detail_detail"
                    label="Код"
                    dense
                    v-on:keyup.enter="searchCardsAdvanced()"
                  />
                  <v-text-field
                    v-model="search.name"
                    label="Наименование"
                    dense
                    v-on:keyup.enter="searchCardsAdvanced()"
                  />
                  <v-text-field
                    v-model="search.workshop"
                    label="Цех"
                    type="number"
                    dense
                    v-on:keyup.enter="searchCardsAdvanced()"
                  />
                </v-col>
                <v-col cols="12" md="3" lg="2">
                  <v-text-field
                    v-model="search.cipher_main_td"
                    label="№ основного ТП"
                    dense
                    v-on:keyup.enter="searchCardsAdvanced()"
                    v-mask="'#####.#####'"
                  />
                  <v-text-field
                    v-model="search.cipher_of_the_reference_tp"
                    label="№ ссылочного ТП"
                    dense
                    disabled
                    v-on:keyup.enter="searchCardsAdvanced()"
                    v-mask="'#####.#####'"
                  />
                  <v-text-field
                    v-model="search.norm"
                    label="Нормировщик"
                    dense
                    v-on:keyup.enter="searchCardsAdvanced()"
                  />
                </v-col>
                <v-col cols="12" md="6" lg="4">
                  <v-row>
                    <v-col cols="12" class="py-0">
                      <div class="font-weight-medium text-body-1">
                        Дата заведения
                      </div>
                    </v-col>
                    <v-col cols="12" md="6" lg="5" class="pt-1">
                      <v-menu
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="computedDateCreatedFromFormatted"
                            label="Дата с"
                            prepend-icon="mdi-calendar"
                            v-bind="attrs"
                            v-on="on"
                            readonly
                            dense
                            hide-details
                            class="pt-0"
                            v-on:keyup.enter="searchCardsAdvanced()"
                          />
                        </template>
                        <v-date-picker
                          v-model="search.dateCreatedFrom"
                          no-title
                          dense
                          :max="
                            search.dateCreatedTo
                              ? search.dateCreatedTo
                              : nowDate
                          "
                        />
                      </v-menu>
                    </v-col>
                    <v-col cols="12" md="6" lg="5" class="pt-1">
                      <v-menu
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="computedDateCreatedToFormatted"
                            label="Дата по"
                            prepend-icon="mdi-calendar"
                            v-bind="attrs"
                            v-on="on"
                            readonly
                            dense
                            hide-details
                            class="pt-0"
                            v-on:keyup.enter="searchCardsAdvanced()"
                          />
                        </template>
                        <v-date-picker
                          v-model="search.dateCreatedTo"
                          no-title
                          :max="nowDate"
                          :min="search.dateCreatedFrom"
                        />
                      </v-menu>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col cols="12" class="py-0">
                      <div class="font-weight-medium text-body-1 mt-lg-1">
                        Дата изменения
                      </div>
                    </v-col>
                    <v-col cols="12" md="6" lg="5" class="pt-1">
                      <v-menu
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="computedDateEditedFromFormatted"
                            label="Дата с"
                            prepend-icon="mdi-calendar"
                            v-bind="attrs"
                            v-on="on"
                            readonly
                            dense
                            hide-details
                            class="pt-0"
                            v-on:keyup.enter="searchCardsAdvanced()"
                          />
                        </template>
                        <v-date-picker
                          v-model="search.dateEditedFrom"
                          no-title
                          :max="
                            search.dateEditedTo ? search.dateEditedTo : nowDate
                          "
                        />
                      </v-menu>
                    </v-col>
                    <v-col cols="12" md="6" lg="5" class="pt-1">
                      <v-menu
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="computedDateEditedToFormatted"
                            label="Дата по"
                            prepend-icon="mdi-calendar"
                            v-bind="attrs"
                            v-on="on"
                            readonly
                            dense
                            hide-details
                            class="pt-0"
                            v-on:keyup.enter="searchCardsAdvanced()"
                          />
                        </template>
                        <v-date-picker
                          v-model="search.dateEditedTo"
                          no-title
                          :max="nowDate"
                          :min="search.dateEditedFrom"
                        />
                      </v-menu>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
              <v-btn
                color="primary"
                class
                :disabled="!advancedSearch"
                @click="searchCardsAdvanced()"
              >
                <v-icon left>mdi-magnify</v-icon>
                <span>Поиск</span>
              </v-btn>
            </v-form>
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-card-title>

    <v-menu offset-y :close-on-content-click="false">
      <template v-slot:activator="{ on, attrs }">
        <v-btn color="primary" dark v-bind="attrs" v-on="on" class="ms-4 mb-4">
          <v-icon color="white" class="mt-1">mdi-table-edit</v-icon>
        </v-btn>
      </template>

      <v-list>
        <v-list-item
          v-for="(item, index) of headers"
          :key="index"
          @click="item.visible = !item.visible"
          :style="`opacity: ${item.visible ? 100 : 0.35}`"
        >
          <v-list-item-action>
            <v-icon>
              {{ item.visible ? "mdi-eye" : "mdi-eye-off" }}
            </v-icon>
          </v-list-item-action>
          <v-list-item-title>
            {{ item.text }}
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-menu>

    <v-data-table
      item-key="index"
      :headers="showHeaders"
      :items="items"
      :server-items-length="total"
      class="elevation-0 w-100 px-4"
      style="white-space: nowrap"
      :loading="tableLoading"
      loading-text="Загрузка данных..."
      :options.sync="options"
      hide-default-footer
      @page-count="pageCount = $event"
      @dblclick:row="archiveCard"
      dense
    />

    <div
      class="text-center pt-2 px-2 d-flex w-100 align-center justify-space-between"
    >
      <v-pagination
        v-model="options.page"
        :length="pageCount"
        :total-visible="7"
      />
      <v-select
        :items="paginationItems"
        :value="options.itemsPerPage"
        label="Строк на странице"
        @change="options.itemsPerPage = parseInt($event, 10)"
        style="max-width: 200px;"
        class="pe-3"
      />
    </div>
  </v-card>
</template>

<script>
import Api from "@/services/api";
import { objectHasNonEmptyStrings } from "@/helpers/string";

export default {
  name: "SearchArchivePage",
  data: () => ({
    searchType: "",
    paginationItems: [
      { text: 5, value: 5 },
      { text: 10, value: 10 },
      { text: 15, value: 15 },
      { text: 30, value: 30 },
      { text: "Все", value: -1 }
    ],
    tableLoading: true,
    advancedSearch: false,
    query: "",
    nowDate: new Date().toISOString().substr(0, 10),
    selectedItem: [],
    search: {
      designation: "",
      code_detail_detail: "",
      name: "",
      workshop: "",
      cipher_main_td: "",
      cipher_of_the_reference_tp: "",
      norm: "",
      dateCreatedFrom: "",
      dateCreatedTo: "",
      dateEditedFrom: "",
      dateEditedTo: ""
    },
    items: [],
    pageCount: 1,
    total: 0,
    options: {
      page: 1,
      itemsPerPage: 30,
      sortBy: [],
      sortDesc: []
    },
    headers: [
      {
        text: "Индекс",
        align: "start",
        sortable: true,
        value: "designation",
        visible: true
      },
      { text: "Код", value: "code_detail", visible: true },
      { text: "Наименование", value: "name", visible: true },
      { text: "Цех", value: "workshop", visible: true },
      { text: "№ основного ТП", value: "cipher_main_td", visible: true },
      { text: "Нормировщик", value: "norm", visible: true },
      { text: "Дата создания", value: "created_date", visible: true },
      { text: "Дата изменения", value: "updated_date", visible: true },
      { text: "Статус", value: "status", visible: true }
    ]
  }),
  async mounted() {
    if (localStorage.getItem("columnsVisible-" + this.$route.path)) {
      this.headers = JSON.parse(
        localStorage.getItem("columnsVisible-" + this.$route.path)
      );
    }
  },
  created() {
    this.tableLoading = false;
  },
  watch: {
    dateFrom() {
      this.dateFromFormatted = this.formatDate(this.search.dateFrom);
    },
    dateTo() {
      this.dateToFormatted = this.formatDate(this.search.dateTo);
    },
    showHeaders() {
      localStorage.setItem(
        "columnsVisible-" + this.$route.path,
        JSON.stringify(this.headers)
      );
    },
    options: {
      handler() {
        if (this.searchType === "common") {
          this.searchCardsCommon();
        } else if (this.searchType === "advanced") {
          this.searchCardsAdvanced();
        }
      },
      deep: true
    },
    deep: true
  },
  computed: {
    computedDateCreatedFromFormatted() {
      return this.formatDate(this.search.dateCreatedFrom);
    },
    computedDateCreatedToFormatted() {
      return this.formatDate(this.search.dateCreatedTo);
    },
    computedDateEditedFromFormatted() {
      return this.formatDate(this.search.dateEditedFrom);
    },
    computedDateEditedToFormatted() {
      return this.formatDate(this.search.dateEditedTo);
    },
    showHeaders() {
      return this.headers.filter(header => header.visible);
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return null;
      const [year, month, day] = date.split("-");
      return `${day}.${month}.${year}`;
    },
    archiveCard(event, { item }) {
      this.$root.logger.log(
        `Переход на страницу просмотра архива КНВ: ${item.id_v01}`
      );
      this.$router.push({ path: "/archive/" + item.id_v01 });
    },
    searchCardsCommon() {
      if (this.query.trim().length) {
        this.$root.snackbar.close();
        this.searchType = "common";
        this.searchData({ query: this.query });
      }
    },
    searchCardsAdvanced() {
      if (objectHasNonEmptyStrings(this.search)) {
        this.$root.snackbar.close();
        this.searchType = "advanced";
        this.searchData({ search: this.search });
      }
    },
    searchData(query) {
      this.tableLoading = true;

      Api.searchCards(query, {
        params: {
          page: this.options.page,
          per_page: this.options.itemsPerPage,
          sort_by: this.options.sortBy[0],
          sort_direction: this.options.sortDesc[0] ? "desc" : "asc"
        }
      })
        .then(({ data }) => {
          this.items = data.data;
          this.pageCount = data.last_page;
          this.total = data.total;
          this.$root.logger.log(
            `Выполнен поиск карт по запросу: ${query} | Найдено карт: ${data.length}`
          );
        })
        .catch(error => {
          this.$root.snackbar.showError(
            `Ошибка выполнения операции: ${error}\nОбратитесь к разработчику или попробуйте еще раз`
          );
          this.$root.logger.log(
            `Во время поиска карт по запросу ${query} произошла ошибка: ${error}`
          );
        })
        .finally(() => (this.tableLoading = false));
    }
  }
};
</script>
