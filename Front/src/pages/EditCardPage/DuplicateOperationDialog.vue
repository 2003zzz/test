<template>
  <v-dialog :value="value" @input="$emit('input')" persistent width="1000">
    <PageLoadingDialog v-model="cardsLoading" />

    <SelectProductCardDialog
      v-model="isSelectProductCardDialogShow"
      :cards="cards"
      :selection="selection"
      @select="changeCardIds"
    />

    <v-card>
      <v-card-title>
        <span class="headline">
          Копирование операции
        </span>
      </v-card-title>

      <div class="px-6">
        <div class="d-flex flex-row w-100 align-center">
          <v-text-field
            v-model="query"
            label="Введите строку для поиска"
            single-line
            hide-details
            class="mb-3 mt-0"
            style="max-width: 500px"
            :disabled="advancedSearch"
            v-on:keyup.enter="searchProductsCommon"
          />
          <v-btn
            tile
            color="primary"
            class="ms-3"
            :disabled="advancedSearch"
            @click="searchProductsCommon"
          >
            <v-icon left>mdi-magnify</v-icon>
            <span class="d-none d-lg-block">Поиск</span>
          </v-btn>
        </div>

        <v-expansion-panels>
          <v-expansion-panel
            focusable
            @change="advancedSearch = !advancedSearch"
          >
            <v-expansion-panel-header>
              Расширенный поиск
            </v-expansion-panel-header>
            <v-expansion-panel-content>
              <v-form class="w-100 pa-4">
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
                  class="align-self-center"
                  :disabled="!advancedSearch"
                  @click="searchProductsAdvanced"
                >
                  <v-icon left>mdi-magnify</v-icon>Поиск
                </v-btn>
              </v-form>
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>

        <v-row no-gutters>
          <v-col lg="8">
            <v-data-table
              item-key="index"
              :headers="headers"
              :items="products"
              :server-items-length="totalResults"
              :options.sync="options"
              :loading="productsLoading"
              loading-text="Загрузка данных..."
              class="elevation-0 w-100 px-4 pt-4"
              hide-default-footer
              dense
              @dblclick:row="onProductSelected"
            />
          </v-col>
          <v-col lg="4">
            <v-card-text>
              Список ДСЕ для копирования ({{ selectedProductCards.length }}):
            </v-card-text>

            <v-virtual-scroll
              :items="selectedProductCards"
              item-height="30"
              height="300"
            >
              <template v-slot:default="{ item }">
                <v-list-item :key="item.product.code_detail">
                  <v-list-item-content>
                    <v-list-item-title>{{
                      item.product.p006
                    }}</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </template>
            </v-virtual-scroll>
          </v-col>
        </v-row>

        <v-row class="pt-4 w-100">
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
          />
        </v-row>
      </div>

      <v-divider />

      <v-card-actions>
        <v-btn text @click="onClose()">
          Закрыть
        </v-btn>
        <v-spacer />
        <v-btn color="primary" @click="onCopy()">
          Скопировать
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapState, mapActions } from "vuex";
import { objectHasNonEmptyStrings } from "@/helpers/string";
import PageLoadingDialog from "@/components/PageLoadingDialog";
import SelectProductCardDialog from "./SelectProductCardDialog";

export default {
  name: "DuplicateOperationDialog",
  components: {
    PageLoadingDialog,
    SelectProductCardDialog
  },
  props: {
    value: Boolean
  },
  data: () => ({
    isSelectProductCardDialogShow: false,
    selectedProductCards: [],
    selectedProduct: null,
    selection: [],
    searchType: "",
    advancedSearch: false,
    options: {
      page: 1,
      itemsPerPage: 10,
      sortBy: [],
      sortDesc: []
    },
    query: "",
    search: {
      c006: "",
      code_detail: "",
      p0081: ""
    },
    paginationItems: [
      { text: 5, value: 5 },
      { text: 10, value: 10 },
      { text: 15, value: 15 }
    ],
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
  computed: {
    ...mapState("search/product", {
      pageCount: state => state.products.pageCount,
      totalResults: state => state.products.totalResults,
      products: state => state.products.items,
      productsLoading: state => state.products.loading,
      productsError: state => state.products.error,
      cards: state => state.selectedProduct.cards.items,
      cardsLoading: state => state.selectedProduct.cards.loading,
      cardsError: state => state.selectedProduct.cards.error
    }),
    selectedCardIds() {
      return this.selectedProductCards.reduce(
        (prev, current) => (prev = [...prev, ...current.cardIds]),
        []
      );
    }
  },
  watch: {
    value: {
      handler() {
        this.clearSearchResults();
        this.clearDialogData();
      }
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
  methods: {
    ...mapActions("search/product", [
      "searchProducts",
      "searchProductCards",
      "clearSearchResults"
    ]),
    async onProductSelected(event, { item }) {
      if (this.cardsLoading) return;

      this.selectedProduct = item;
      const success = await this.searchProductCards(item);
      if (!success) {
        this.$root.snackbar.showError(this.cardsError);
        return;
      }

      const index = this.selectedProductCards.findIndex(
        productCard => productCard.product.code_detail === item.code_detail
      );
      const productCard = this.selectedProductCards[index];
      this.selection = productCard ? productCard.cardIds : [];

      this.isSelectProductCardDialogShow = true;
    },
    changeCardIds(selectedCardIds) {
      const index = this.selectedProductCards.findIndex(
        productCard =>
          productCard.product.code_detail === this.selectedProduct.code_detail
      );

      if (index < 0) {
        if (selectedCardIds.length > 0) {
          this.selectedProductCards.push({
            product: this.selectedProduct,
            cardIds: selectedCardIds
          });
        }
        return;
      }

      if (selectedCardIds.length === 0) {
        this.selectedProductCards.splice(index, 1);
      } else {
        this.selectedProductCards[index].cardIds = selectedCardIds;
      }
    },
    async searchProductsCommon() {
      if (this.productsLoading) return;

      if (this.query.trim().length) {
        this.searchType = "common";
        const success = await this.searchProducts({
          query: { query: this.query },
          options: this.options
        });
        if (!success) {
          this.$root.snackbar.showError(this.productsError);
        }
      }
    },
    async searchProductsAdvanced() {
      if (this.productsLoading) return;

      if (objectHasNonEmptyStrings(this.search)) {
        this.searchType = "advanced";
        const success = await this.searchProducts({
          query: { search: this.search },
          options: this.options
        });
        if (!success) {
          this.$root.snackbar.showError(this.productsError);
        }
      }
    },
    clearDialogData() {
      this.selectedProductCards = [];
      this.selectedProduct = null;
      this.searchType = "";
      this.advancedSearch = false;
      this.options = {
        page: 1,
        itemsPerPage: 10,
        sortBy: [],
        sortDesc: []
      };
      this.query = "";
      this.search = {
        c006: "",
        code_detail: "",
        p0081: ""
      };
    },
    onClose() {
      this.$emit("input");
    },
    onCopy() {
      if (this.selectedProductCards.length === 0) return;

      this.$emit("duplicate", this.selectedCardIds);
      this.$emit("input");
    }
  }
};
</script>
