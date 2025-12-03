import Api from "@/services/api";

export default {
  namespaced: true,
  state: {
    statuses: [],
    loading: false,
    error: null
  },
  mutations: {
    SET_STATUSES(state, statuses) {
      state.statuses = statuses;
    },
    SET_LOADING(state, loading) {
      state.loading = loading;
    },
    SET_ERROR(state, error) {
      state.error = error;
    }
  },
  getters: {},
  actions: {
    async getCardStatusList({ commit }) {
      try {
        commit("SET_LOADING", true);

        const response = await Api.fetchListStatuses();

        commit("SET_STATUSES", response.data);
      } catch (error) {
        commit(
          "SET_ERROR",
          `Не удалось получить список статусов КНВ.\nОбратитесь к разработчику или попробуйте еще раз`
        );
      } finally {
        commit("SET_LOADING", false);
      }
    }
  }
};
