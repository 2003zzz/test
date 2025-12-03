import Vue from 'vue'
//import Vuetify from 'vuetify/lib'
import Vuetify from 'vuetify';

import 'vuetify/dist/vuetify.min.css';
// import config from '../../src/config.js'
import 'font-awesome/css/font-awesome.css'
import 'mdi/css/materialdesignicons.css'
import ru from 'vuetify/es5/locale/ru';
// import { colors } from "vuetify/lib";

Vue.use(Vuetify);

const localStorageTheme = localStorage.getItem('vuetify-theme');
// Проверяем, есть ли уже тема в localStorage
if (localStorageTheme === null) {
  localStorage.setItem('vuetify-theme', 'light'); // Устанавливаем значение по умолчанию
}
const initialTheme = localStorageTheme === 'dark' ? true : false;

const theme = {
  dark: initialTheme,
  themes: {
    light: {
      secondary: '#424242',
      accent: '#82B1FF',
      error: '#FF5252',
      info: '#2196F3',
      success: '#1CDA94',
      warning: '#FFC107',
    },
    dark: {
      primary: '#1976D2',
      secondary: '#424242',
      accent: '#82B1FF',
      error: '#FF5252',
      info: '#2196F3',
      success: '#4CAF50',
      warning: '#FFC107',
    },
  },
};

// Vuetify options
const vuetify = new Vuetify({
  lang: {
    locales: { ru },
    current: 'ru',
  },
  theme,
  options: {
    themeCache: {
      get: (key) => localStorage.getItem(key),
      set: (key, value) => localStorage.setItem(key, value),
    },
  },
});

export default vuetify;

/*export default new Vuetify({
    theme: {
        themes: {
            light: config.light
        }
    }
});*/
