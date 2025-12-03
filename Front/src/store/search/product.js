import Api from "@/services/api";
import logger from "@/logger";

export default {
  namespaced: true,
  state: {
    products: {
      items: [],
      pageCount: 0,
      totalResults: 0,
      loading: false,
      error: null
    },
    selectedProduct: {
      data: {},
      cards: {
        items: [],
        loading: false,
        error: null
      }
    }
  },
  mutations: {
    SET_PRODUCTS(state, products) {
      state.products.items = products;
    },
    SET_PAGE_COUNT(state, pageCount) {
      state.products.pageCount = pageCount;
    },
    SET_TOTAL_RESULTS(state, totalResults) {
      state.products.totalResults = totalResults;
    },
    SET_PRODUCT_LOADING(state, loading) {
      state.products.loading = loading;
    },
    SET_PRODUCT_ERROR(state, error) {
      state.products.error = error;
    },
    SET_SELECTED_PRODUCT(state, product) {
      state.selectedProduct.data = product;
    },
    SET_SELECTED_PRODUCT_CARDS(state, cards) {
      state.selectedProduct.cards.items = cards;
    },
    SET_SELECTED_PRODUCT_CARDS_LOADING(state, loading) {
      state.selectedProduct.cards.loading = loading;
    },
    SET_SELECTED_PRODUCT_CARDS_ERROR(state, error) {
      state.selectedProduct.cards.error = error;
    }
  },
  getters: {},
  actions: {
    async searchProducts({ commit }, { query, options }) {
      try {
        commit("SET_PRODUCT_LOADING", true);
        const { data } = await Api.searchProducts(query, {
          params: {
            page: options.page,
            per_page: options.itemsPerPage,
            sort_by: options.sortBy[0],
            sort_direction: options.sortDesc[0] ? "desc" : "asc"
          }
        });

        commit("SET_PRODUCTS", data.data);
        commit("SET_PAGE_COUNT", data.last_page);
        commit("SET_TOTAL_RESULTS", data.total);

        logger.log(
          `Выполнен поиск изделий по запросу: ${query} | Найдено изделий: ${data.data.length}`
        );

        return true;
      } catch (error) {
        commit("SET_PRODUCTS", []);
        commit("SET_PAGE_COUNT", 0);
        commit("SET_TOTAL_RESULTS", 0);
        commit(
          "SET_PRODUCT_ERROR",
          `Ошибка поиска ДСЕ\nОбратитесь к разработчику или попробуйте еще раз`
        );
        logger.log(
          `Во время поиска изделий по запросу ${query} произошла ошибка: ${error}`
        );
      } finally {
        commit("SET_PRODUCT_LOADING", false);
      }
    },
    async searchProductCards({ commit }, productDto) {
      try {
        commit("SET_SELECTED_PRODUCT_CARDS_LOADING", true);
        const { data } = await Api.fetchProductCards(productDto);

        commit("SET_SELECTED_PRODUCT_CARDS", data);

        logger.log(
          `Просмотрены все созданные КНВ для изделия: ${productDto.p006} | Найдено КНВ: ${data.length}`
        );

        return true;
      } catch (error) {
        commit(
          "SET_SELECTED_PRODUCT_CARDS_ERROR",
          `Ошибка поиска КНВ\nОбратитесь к разработчику или попробуйте еще раз`
        );
        logger.log(
          `Во время загрузки созданных КНВ для изделия ${productDto.p006} произошла ошибка: ${error}`
        );
      } finally {
        commit("SET_SELECTED_PRODUCT_CARDS_LOADING", false);
      }
    },
    async clearSearchResults({ commit }) {
      commit("SET_PRODUCTS", []);
    }
  }
};
