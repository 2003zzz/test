import Api from "@/services/api";
import { Card, Operation } from "@/models";

export default {
  namespaced: true,
  state: {
    currentVersion: null,
    versions: [],
    loading: false,
    error: null,
    forms: [
      {
        version: null,
        card: {},
        operations: [],
        loading: false,
        error: null
      },
      {
        version: null,
        card: {},
        operations: [],
        loading: false,
        error: null
      }
    ]
  },
  mutations: {
    SET_CURRENT_VERSION(state, currentVersion) {
      state.currentVersion = currentVersion;
    },
    SET_VERSIONS(state, versions) {
      state.versions = versions;
    },
    SET_LOADING(state, loading) {
      state.loading = loading;
    },
    SET_ERROR(state, error) {
      state.error = error;
    },
    SET_FORM_VERSION(state, { formIndex, version }) {
      state.forms[formIndex].version = version;
    },
    SET_FORM_CARD(state, { formIndex, card }) {
      state.forms[formIndex].card = card;
    },
    SET_FORM_OPERATIONS(state, { formIndex, operations }) {
      state.forms[formIndex].operations = operations;
    },
    SET_FORM_LOADING(state, { formIndex, loading }) {
      state.forms[formIndex].loading = loading;
    },
    SET_FORM_ERROR(state, { formIndex, error }) {
      state.forms[formIndex].error = error;
    }
  },
  actions: {
    async getCardVersionList({ commit }, cardID) {
      try {
        commit("SET_LOADING", true);

        const response = await Api.fetchListArchiveCards(cardID);
        if (!Array.isArray(response.data)) {
          commit(
            "SET_ERROR",
            `Ошибка в получении данных версий КНВ.\nНажмите кнопку "Отменить" для выхода со страницы`
          );
          return;
        }

        const versions = response.data.map(v => v.id_version);
        // Для последней версии нет updated_at поля, поэтому берем вместо него время создания карты
        const dates = response.data.map(v =>
          new Date(
            v.updated_at ? v.updated_at : v.date_of_create
          ).toLocaleString()
        );
        // И сдвигаем на один элемент так, чтобы рядом с каждым номером версии лежала дата её появления
        dates.unshift(dates.pop());

        const result = [];
        for (let i = 0; i < dates.length; i++) {
          result.push({ version: versions[i], created: dates[i] });
        }

        commit("SET_CURRENT_VERSION", result[result.length - 1].version);

        commit("SET_VERSIONS", result);
      } catch (error) {
        commit(
          "SET_ERROR",
          `Не удалось получить список версий КНВ.\nОбратитесь к разработчику или попробуйте еще раз`
        );
      } finally {
        commit("SET_LOADING", false);
      }
    },
    async getCardVersion(
      { state, commit },
      { formIndex, cardID, versionID = state.currentVersion }
    ) {
      try {
        commit("SET_FORM_LOADING", { formIndex, loading: true });

        let response;
        if (state.currentVersion === versionID) {
          response = await Api.fetchCardById(cardID);
        } else {
          response = await Api.fetchArchiveCardById(cardID, versionID);
        }

        if (!response.data) {
          commit("SET_FORM_ERROR", {
            formIndex,
            error: `Данные КНВ не были получены - неизвестная карточка.\nНажмите кнопку "Отменить" для выхода со страницы`
          });
          return;
        }

        const { card, operations } = response.data;

        commit("SET_FORM_CARD", { formIndex, card: new Card(card) });

        if (Array.isArray(operations) && operations.length > 0) {
          commit("SET_FORM_OPERATIONS", {
            formIndex,
            operations: operations.map(operation =>
              Operation.createOperationForPush(operation)
            )
          });
        }

        commit("SET_FORM_VERSION", { formIndex, version: versionID });
      } catch (error) {
        commit("SET_FORM_ERROR", {
          formIndex,
          error: `Не удалось получить данные версии КНВ.\nОбратитесь к разработчику или попробуйте еще раз`
        });
      } finally {
        commit("SET_FORM_LOADING", { formIndex, loading: false });
      }
    }
  }
};
