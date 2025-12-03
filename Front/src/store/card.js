import Api from "@/services/api";
import { Card, Operation } from "@/models";
import logger from "@/logger";

export default {
  namespaced: true,
  state: {
    card: new Card(),
    operations: [new Operation()],
    workshops: [],
    isLoading: false,
    isSaving: false,
    error: null,
    status: {
      operation: {
        loading: false,
        error: null
      },
      excel: {
        loading: false,
        error: null
      }
    }
  },
  mutations: {
    SET_CARD(state, card) {
      state.card = card;
    },
    SET_OPERATIONS(state, operations) {
      state.operations = operations;
    },
    SET_OPERATION_FIELD(state, { index, field, value }) {
      state.operations[index][field] = value;
    },
    SET_WORKSHOPS(state, workshops) {
      state.workshops = workshops;
    },
    SET_LOADING(state, isLoading) {
      state.isLoading = isLoading;
    },
    SET_SAVING(state, isSaving) {
      state.isSaving = isSaving;
    },
    SET_ERROR(state, error) {
      state.error = error;
    },
    UPDATE_OPERATION_IDS(state, ids) {
      state.operations.forEach((operation, index) => {
        operation.id_v02 = ids[index];
      });
    },
    INSERT_OPERATION(state, { index, item }) {
      state.operations.splice(index + 1, 0, item);
    },
    INSERT_OPERATION_WITH_TP(state, { index, item }) {
      const previousOperation = state.operations[index];
      item.cipher_of_the_reference_tp =
        previousOperation.cipher_of_the_reference_tp;
      state.operations.splice(index + 1, 0, item);
    },
    PUSH_OPERATION(state, item) {
      state.operations.push(item);
    },
    PUSH_OPERATION_WITH_TP(state, item) {
      const lastIndex = state.operations.length - 1;
      if (lastIndex >= 0) {
        const lastOperation = state.operations[lastIndex];

        item.cipher_of_the_reference_tp =
          lastOperation.cipher_of_the_reference_tp;
      }
      state.operations.push(item);
    },
    DELETE_OPERATION(state, index) {
      state.operations.splice(index, 1);
    },
    SET_EXCEL_LOADING(state, loading) {
      state.status.excel.loading = loading;
    },
    SET_EXCEL_ERROR(state, error) {
      state.status.excel.error = error;
    },
    SET_OPERATION_LOADING(state, loading) {
      state.status.operation.loading = loading;
    },
    SET_OPERATION_ERROR(state, error) {
      state.status.operation.error = error;
    },
    RENUMERATE_OPERATIONS(state) {
      state.operations.forEach((operation, index) => {
        operation.end_to_end_operation_number = (index + 1) * 5;
      });
    }
  },
  actions: {
    async fetchCardById({ commit }, cardID) {
      try {
        commit("SET_LOADING", true);
        const response = await Api.fetchCardById(cardID);
        if (!response.data) {
          commit(
            "SET_ERROR",
            'Данные КНВ не были получены - неизвестная карточка.\nНажмите кнопку "Отменить" для выхода со страницы'
          );
          return;
        }
        const { card, operations, workshops } = response.data;

        commit("SET_CARD", new Card(card));

        if (Array.isArray(operations) && operations.length > 0) {
          commit(
            "SET_OPERATIONS",
            operations.map(operation =>
              Operation.createOperationForPush(operation)
            )
          );
        } else {
          commit("SET_OPERATIONS", [new Operation()]);
        }

        if (Array.isArray(workshops) && workshops.length > 0) {
          commit("SET_WORKSHOPS", workshops);
        } else {
          commit("SET_WORKSHOPS", []);
        }
      } catch (error) {
        commit(
          "SET_ERROR",
          "Не удалось получить информацию КНВ\nОбратитесь к разработчику или попробуйте еще раз"
        );
      } finally {
        commit("SET_LOADING", false);
      }
    },
    async saveCard({ state, commit }) {
      try {
        commit("SET_SAVING", true);
        const response = await Api.saveCard({
          card: state.card,
          operations: state.operations.map(Operation.toRequestFormat)
        });

        if (Array.isArray(response.data)) {
          // FIXME: Небезопасная реализация API, если порядок операций не будет совпадать, что не гарантируется,
          // могут перезаписаться id_v02 операции, и при повторных сохранениях операции будут сравниваться с другими, что нарушит историю ведения КНВ
          commit("UPDATE_OPERATION_IDS", response.data);
        }
        return true;
      } catch (error) {
        if (
          error.response &&
          error.response.data.message === "The given data was invalid."
        ) {
          commit(
            "SET_ERROR",
            "В одной или нескольких операциях не заполнены обязательные поля!"
          );
        } else {
          commit(
            "SET_ERROR",
            "Данные не были сохранены\nОбратитесь к разработчику или попробуйте еще раз"
          );
        }
        return false;
      } finally {
        commit("SET_SAVING", false);
      }
    },
    async deleteCard({ commit }, cardID) {
      try {
        commit("SET_LOADING", true);
        const response = await Api.deleteCard(cardID);

        logger.log(
          `Попытка удаления КНВ (id_v01: ${this.main.id_v01}): ${response.data}`
        );
        return true;
      } catch (error) {
        commit(
          "SET_ERROR",
          "Ошибка удаления КНВ\nОбратитесь к разработчику или попробуйте еще раз"
        );
        logger.log(`Ошибка при удалении КНВ (id_v01: ${cardID}): ${error}`);
        return false;
      } finally {
        commit("SET_LOADING", false);
      }
    },
    async insertOperation({ commit }, { index, item }) {
      commit("INSERT_OPERATION", { index, item });

      logger.log(
        `Добавлена пустая операция в КНВ: (порядковый номер: ${index + 1})`
      );
    },
    async insertEmptyOperationWithTP({ commit }, index) {
      commit("INSERT_OPERATION_WITH_TP", { index, item: new Operation() });

      logger.log(
        `Добавлена пустая операция в КНВ: (порядковый номер: ${index + 1})`
      );
    },
    async pushOperation({ commit }, item) {
      commit("PUSH_OPERATION", item);

      logger.log(`Добавлена операция в конец КНВ`);
    },
    async pushEmptyOperation({ commit }) {
      commit("PUSH_OPERATION", new Operation());

      logger.log(`Добавлена пустая операция в конец КНВ`);
    },
    async pushEmptyOperationWithTP({ commit }) {
      commit("PUSH_OPERATION_WITH_TP", new Operation());

      logger.log(`Добавлена пустая операция в конец КНВ (с ТП)`);
    },
    async copyOperation({ commit }, { index, item }) {
      const copiedOperation = new Operation({
        ...item,
        end_to_end_operation_number: item.end_to_end_operation_number + 5,
        id_v02: null
      });
      commit("INSERT_OPERATION", { index, item: copiedOperation });

      logger.log(
        `Скопирована операция в КНВ (id_v02: ${
          item.id_v02
        }, порядковый номер: ${index + 1})`
      );
    },
    async deleteOperation({ commit }, { index, item }) {
      try {
        commit("SET_OPERATION_LOADING", true);

        if (item.id_v02 !== null) {
          const response = await Api.deleteOperation(item.id_v02);
          logger.log(
            `В редактируемой КНВ удалена операция (id_v02: ${response.data})`
          );
        }

        commit("DELETE_OPERATION", index);
      } catch (error) {
        commit(
          "SET_OPERATION_ERROR",
          `Ошибка удаления операции\nОбратитесь к разработчику или попробуйте еще раз`
        );
        logger.log(
          `Ошибка при удалении операции в редактируемой КНВ (id_v02: ${item.id_v02}): ${error}`
        );
      } finally {
        commit("SET_OPERATION_LOADING", false);
      }
    },
    async deleteOperationAndRenumerate({ commit }, { index, item }) {
      try {
        commit("SET_OPERATION_LOADING", true);

        if (item.id_v02 !== null) {
          const response = await Api.deleteOperation(item.id_v02);
          logger.log(
            `В редактируемой КНВ удалена операция (id_v02: ${response.data})`
          );
        }

        commit("RENUMERATE_OPERATIONS");

        commit("DELETE_OPERATION", index);
      } catch (error) {
        commit(
          "SET_OPERATION_ERROR",
          `Ошибка удаления операции\nОбратитесь к разработчику или попробуйте еще раз`
        );
        logger.log(
          `Ошибка при удалении операции в редактируемой КНВ (id_v02: ${item.id_v02}): ${error}`
        );
      } finally {
        commit("SET_OPERATION_LOADING", false);
      }
    },
    async copyPreviousOperationFields(
      { state, commit },
      { currentIndex, fields }
    ) {
      if (currentIndex < 1) return;

      const previousOperation = state.operations[currentIndex - 1];

      for (const field of fields) {
        commit("SET_OPERATION_FIELD", {
          index: currentIndex,
          field,
          value: previousOperation[field]
        });
      }
    },
    async getExcelFromCard({ commit }, cardID) {
      try {
        commit("SET_EXCEL_LOADING", true);
        const response = await Api.fetchDocumentByCardId(cardID);
        return response.data;
      } catch (error) {
        commit(
          "SET_EXCEL_ERROR",
          `Ошибка при выгрузке КНВ\nОбратитесь к разработчику или попробуйте еще раз`
        );
        logger.log(`Ошибка при выгрузке КНВ (id_v01: ${cardID}): ${error}`);
      } finally {
        commit("SET_EXCEL_LOADING", false);
      }
    },
    async duplicateOperationToCards({ commit }, { operation, cardIds }) {
      try {
        commit("SET_OPERATION_LOADING", true);

        const response = await Api.duplicateOperationToCards(
          Operation.toRequestFormat(operation),
          cardIds
        );

        return response.data;
      } catch (error) {
        commit(
          "SET_OPERATION_ERROR",
          `Ошибка копирования операции\nОбратитесь к разработчику или попробуйте еще раз`
        );
        logger.log(`Ошибка при копировании операции: ${error}`);
      } finally {
        commit("SET_OPERATION_LOADING", false);
      }
    }
  }
};
