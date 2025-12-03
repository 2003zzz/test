export default {
  namespaced: true,
  state: {
    focusedOperationIndex: null,
    focusedField: null,
  },
  mutations: {
    focusOperation(state, index) {
      state.focusedOperationIndex = index;
    },
    unfocusOperation(state) {
      state.focusedOperationIndex = null;
    },
    focusField(state, field) {
      state.focusedField = field;
    },
    unfocusField(state) {
      state.focusedField = null;
    },
  },
  getters: {},
  actions: {},
};