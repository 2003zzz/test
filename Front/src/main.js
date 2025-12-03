import "core-js/stable"; //  библиотека совместимости с IE
import "regenerator-runtime/runtime"; //  библиотека совместимости с IE
import Vue from "vue";
import App from "./App.vue";
import "babel-polyfill";
import "vuetify/dist/vuetify.min.css";
import router from "./router";
import vuetify from "./plugins/vuetify";
import VueMask from "v-mask";
import store from "./store";

import axios from "axios";
axios.defaults.baseURL = process.env.VUE_APP_BASE_URL;

Vue.config.productionTip = false;
axios.defaults.withCredentials = true;

Vue.use(vuetify);
Vue.use(VueMask);
export const $eventBus = new Vue();

async function initApp() {
  try {
    await store.dispatch("user/loadInitialData");
  } catch (error) {
    console.error(`Ошибка загрузки данных: ${error}`);
  }
  new Vue({
    vuetify,
    VueMask,
    router,
    store,
    render: h => h(App)
  }).$mount("#app");
}

initApp();
