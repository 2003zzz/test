<template>
  <v-card class="w-100 h-100 justify-start align-start d-flex flex-column">
    <v-dialog v-model="dialogDelete" persistent max-width="500px">
      <v-card>
        <v-card-title class="headline text-center justify-center">
          Вы действительно хотите удалить
          <br />
          "{{ editedItem ? editedItem.name : "" }}"?
        </v-card-title>
        <v-card-actions>
          <v-spacer />
          <v-btn color="blue darken-1" text @click="closeDelete">
            Отменить
          </v-btn>
          <v-btn color="red darken-1" text @click="deleteItemConfirm">
            Удалить
          </v-btn>
          <v-spacer />
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-card-title class="d-flex flex-row w-100 border-0">
      <div class="d-flex flex-row w-100 align-center justify-center">
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

        <v-spacer />

        <RouterLink
          to="/products"
          class="mx-3"
          v-if="['PTT05A', 'PTT05A_1'].includes(user.role)"
        >
          <v-btn color="primary">
            <v-icon class="white--text text-h5">mdi-plus</v-icon>
            <span class="d-none d-lg-block">
              Добавить
            </span>
          </v-btn>
        </RouterLink>
        <v-btn
          color="primary"
          @click="saveCardFromExcel()"
          v-if="['PTT05A', 'PTT05A_1'].includes(user.role)"
        >
          <input
            type="file"
            accept=".xls,.xlsx"
            style="display: none"
            ref="fileUploader"
            @change="onUploadFile($event)"
          />
          <v-icon class="white--text text-h5">mdi-import</v-icon>
          <span class="d-none d-lg-block">
            Загрузить
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
                    v-model="search.code_detail"
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
                    disabled
                    dense
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
      @dblclick:row="showItem"
      dense
    >
      <template v-slot:item.actions="{ item }">
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              class="mr-2"
              @click="showItem(null, { item })"
              v-bind="attrs"
              v-on="on"
              small
              >mdi-eye</v-icon
            >
          </template>
          <span>
            Просмотр
          </span>
        </v-tooltip>
        <v-tooltip top>
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              class="mr-2"
              @click="showItemCard(null, { item })"
              v-bind="attrs"
              v-on="on"
              small
              >mdi-note-text</v-icon
            >
          </template>
          <span>
            Просмотр карточки
          </span>
        </v-tooltip>
        <v-tooltip top v-if="['PTT05A', 'PTT05A_1'].includes(user.role)">
          <template v-slot:activator="{ on, attrs }">
            <v-icon
              class="mr-2"
              @click="deleteItem(item)"
              v-bind="attrs"
              v-on="on"
              small
              >mdi-delete</v-icon
            >
          </template>
          <span>
            Удалить
          </span>
        </v-tooltip>
      </template>
    </v-data-table>

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
import { mapState } from "vuex";
import { objectHasNonEmptyStrings } from "@/helpers/string";

export default {
  name: "SearchCardsPage",
  data: () => ({
    searchType: "",
    paginationItems: [
      { text: 5, value: 5 },
      { text: 10, value: 10 },
      { text: 15, value: 15 },
      { text: 30, value: 30 },
      { text: "Все", value: -1 }
    ],
    tableLoading: false,
    advancedSearch: false,
    nowDate: new Date().toISOString().substr(0, 10),
    dialogDelete: false,
    editedItem: [],
    editedIndex: -1,
    items: [],
    query: "",
    search: {
      designation: "",
      code_detail: "",
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
    pageCount: 1,
    total: 0,
    options: {
      page: 1,
      itemsPerPage: 30,
      sortBy: [],
      sortDesc: []
    },
    headers: [
      { text: "Индекс", align: "start", value: "designation", visible: true },
      { text: "Код", value: "code_detail", visible: true },
      { text: "Наименование", value: "name", visible: true },
      { text: "Цех", value: "workshop", visible: true },
      { text: "№ основного ТП", value: "cipher_main_td", visible: true },
      { text: "Нормировщик", value: "norm", visible: true },
      { text: "Дата создания", value: "created_date", visible: true },
      { text: "Дата изменения", value: "updated_date", visible: true },
      { text: "Статус", value: "status", visible: true },
      { text: "Действия", value: "actions", sortable: false, visible: true }
    ]
  }),
  watch: {
    dialogDelete(val) {
      val || this.closeDelete();
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
    ...mapState(["user"]),
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
  async mounted() {
    if (localStorage.getItem("columnsVisible-" + this.$route.path)) {
      this.headers = JSON.parse(
        localStorage.getItem("columnsVisible-" + this.$route.path)
      );
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return null;

      const [year, month, day] = date.split("-");
      return `${day}.${month}.${year}`;
    },
    showItem(event, { item }) {
      this.$router.push({ path: "/cards/view/" + item.id_v01 });
    },
    showItemCard(event, { item }) {
      this.$router.push({ path: "/cards/show/" + item.id_v01 });
    },
    deleteItem(item) {
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialogDelete = true;
    },
    deleteItemConfirm() {
      this.items.splice(this.editedIndex, 1);
      this.closeDelete();
    },
    closeDelete() {
      this.dialogDelete = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
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
    },
    saveCardFromExcel() {
      this.$refs.fileUploader.click();
    },
    onUploadFile(event) {
      const file = event.target.files[0];
      const formData = new FormData();
      formData.append("document", file);
      Api.createCardFromDocument(formData)
        .then(() => {
          this.$root.snackbar.showInfo("КНВ успешно добавлена!");
          this.$root.logger.log("В систему добавлена новая КНВ: " + file.name);
        })
        .catch(error => {
          this.$root.snackbar.showError(
            `Ошибка загрузки КНВ из файла\nОбратитесь к разработчику или попробуйте еще раз`
          );
          this.$root.logger.log(
            "Во время загрузки КНВ из файла произошла ошибка: " + error
          );
        });
    }
  }
};
</script>

<style lang="scss" scoped src="./SearchCardsPage.scss" />
