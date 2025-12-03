<template>
  <v-card class="w-100 h-100 justify-start align-start d-flex flex-column">
    <v-card-title class="d-flex w-100 align-end border-0">
      <CardListDialog
        :show="visible"
        :item="selectedItem"
        @add-card="addCard"
        @edit-card="editCard"
        @copy-card="copyCard"
        @delete-card="deleteCard"
        @close="visible = false"
      />

      <div class="d-flex flex-row w-100 align-center">
        <v-text-field
          v-model="query"
          label="Введите строку для поиска"
          single-line
          hide-details
          class="my-3 lh-0 w-100 mt-0"
          style="max-width: 500px"
          :disabled="advancedSearch"
          v-on:keyup.enter="searchProductsCommon"
        />
        <v-btn
          tile
          color="primary"
          class="ms-3 mt-0"
          :disabled="advancedSearch"
          @click="searchProductsCommon"
        >
          <v-icon left>mdi-magnify</v-icon>
          <span class="d-none d-lg-block">Поиск</span>
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
                <v-col cols="12" md="5">
                  <v-text-field
                    v-model="search.c006"
                    label="Индекс"
                    v-on:keyup.enter="searchProductsAdvanced"
                    dense
                  />
                  <v-text-field
                    v-model="search.code_detail"
                    label="Код"
                    v-on:keyup.enter="searchProductsAdvanced"
                    dense
                  />
                  <v-text-field
                    v-model="search.p0081"
                    label="Наименование"
                    v-on:keyup.enter="searchProductsAdvanced"
                    dense
                  />

                  <v-btn
                    color="primary"
                    class
                    :disabled="!advancedSearch"
                    @click="searchProductsAdvanced"
                  >
                    <v-icon left>mdi-magnify</v-icon>Поиск
                  </v-btn>
                </v-col>
              </v-row>
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
          :style="`opacity: ${item.visible ? 1 : 0.35}`"
          @click="item.visible = !item.visible"
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
      :options.sync="options"
      :loading="tableLoading"
      @page-count="pageCount = $event"
      @dblclick:row="showCards"
      loading-text="Загрузка данных..."
      class="elevation-0 w-100 px-4"
      style="white-space: nowrap"
      hide-default-footer
      dense
    />

    <v-row class="pt-2 px-2 w-100">
      <v-pagination
        v-model="options.page"
        :length="pageCount"
        :total-visible="7"
      />
      <v-spacer />
      <v-select
        :items="paginationItems"
        :value="options.itemsPerPage"
        label="Строк на странице"
        @change="options.itemsPerPage = parseInt($event, 10)"
        style="max-width: 200px;"
        class="pe-3"
      />
    </v-row>
  </v-card>
</template>

<script>
import Api from "@/services/api";
import CardListDialog from "./CardListDialog";
import { objectHasNonEmptyStrings } from "@/helpers/string";

export default {
  name: "SearchProductsPage",
  components: {
    CardListDialog
  },
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
    selectedItem: {},
    visible: false,
    pageCount: 1,
    total: 0,
    options: {
      page: 1,
      itemsPerPage: 30,
      sortBy: [],
      sortDesc: []
    },
    query: "",
    search: {
      c006: "",
      code_detail: "",
      p0081: ""
    },
    items: [],
    headers: [
      {
        text: "Индекс",
        value: "p006",
        visible: true,
        align: "start",
        sortable: true
      },
      {
        text: "Код",
        value: "code_detail",
        visible: true
      },
      {
        text: "Наименование",
        value: "p0081",
        visible: true
      }
    ]
  }),
  async mounted() {
    if (localStorage.getItem("columnsVisible-" + this.$route.path)) {
      this.headers = JSON.parse(
        localStorage.getItem("columnsVisible-" + this.$route.path)
      );
    }
  },
  watch: {
    showHeaders() {
      localStorage.setItem(
        "columnsVisible-" + this.$route.path,
        JSON.stringify(this.headers)
      );
    },
    options: {
      handler() {
        if (this.searchType === "common") {
          this.searchProductsCommon();
        } else if (this.searchType === "advanced") {
          this.searchProductsAdvanced();
        }
      },
      deep: true
    }
  },
  computed: {
    showHeaders() {
      return this.headers.filter(header => header.visible);
    }
  },
  methods: {
    showCards(event, { item }) {
      this.$root.snackbar.close();
      this.selectedItem = item;
      this.visible = true;
    },
    editCard(item) {
      this.$root.logger.log(
        "Переход на страницу редактирования КНВ: " + item.id_v01
      );
      this.$router.push({ path: "/products/edit/" + item.id_v01 });
    },
    copyCard(item) {
      this.$root.logger.log(
        "Переход на страницу копирования КНВ: " + item.id_v01
      );
      this.selectedItem.copy = item.id_v01;
      this.$router.push({ path: "/products/create", query: this.selectedItem });
    },
    addCard() {
      this.$root.logger.log("Переход на страницу создания новой КНВ");
      this.$router.push({ path: "/products/create", query: this.selectedItem });
    },
    deleteCard() {},
    searchProductsCommon() {
      if (this.query.trim().length) {
        this.$root.snackbar.close();
        this.searchType = "common";
        this.searchData({ query: this.query });
      }
    },
    searchProductsAdvanced() {
      if (objectHasNonEmptyStrings(this.search)) {
        this.$root.snackbar.close();
        this.searchType = "advanced";
        this.searchData({ search: this.search });
      }
    },
    searchData(query) {
      this.tableLoading = true;
      Api.searchProducts(query, {
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
            `Выполнен поиск изделий по запросу: ${query} | Найдено изделий: ${data.length}`
          );
        })
        .catch(error => {
          this.$root.snackbar.showError(
            `Ошибка выполнения операции: ${error}\nОбратитесь к разработчику или попробуйте еще раз`
          );
          this.$root.logger.log(
            `Во время поиска изделий по запросу ${query} произошла ошибка: ${error}`
          );
        })
        .finally(() => (this.tableLoading = false));
    }
  }
};
</script>

<style lang="scss" scoped src="./SearchProductsPage.scss" />
